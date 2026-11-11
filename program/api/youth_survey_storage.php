<?php
require_once __DIR__.'/youth_survey_common.php';
function youth_sql($db,$sql,$types='',$params=[]) {
    $stmt=$db->prepare($sql);
    if($types!=='') $stmt->bind_param($types,...$params);
    $stmt->execute(); return $stmt;
}
// Preserve old versions: v2 Nam Phai used free text; v1-v3 Thung Saliam had original labels.
function youth_historical_schema($version,$area) {
    $schema=youth_schema($area);
    $schema['version']=$version;
    $original=json_decode(file_get_contents(__DIR__.'/youth_survey_schema.json'),true,512,JSON_THROW_ON_ERROR);
    foreach($schema['questions'] as &$q) {
        if($q['id']!=='s1q5') continue;
        if($area==='legacy_unknown' || ($area==='thung_saliam' && in_array($version,['2026-10-28-v1','2026-10-28-v2','2026-10-28-v3'],true))) $q=$original['questions'][4];
        if($area==='nam_phai' && $version==='2026-10-28-v2') {$q['type']='text';$q['options']=[];}
    }
    unset($q); return $schema;
}
function youth_form_id($db,$area,$schema) {
    $areaId=youth_sql($db,'SELECT id FROM youth_survey_areas WHERE code=?','s',[$area])->get_result()->fetch_assoc()['id']??null;
    if(!$areaId) throw new RuntimeException('Unknown survey area');
    youth_sql($db,'INSERT INTO youth_survey_forms(area_id,version) VALUES (?,?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)','is',[$areaId,$schema['version']]);
    $formId=$db->insert_id;
    foreach($schema['sections'] as $index=>$title) {
        youth_sql($db,'INSERT INTO youth_survey_sections(form_id,section_number,title) VALUES (?,?,?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)','iis',[$formId,$index+1,$title]);
        $sectionId=$db->insert_id;
        foreach($schema['questions'] as $q) {
            if($q['section']!==$index+1)continue;
            youth_sql($db,'INSERT INTO youth_survey_questions(section_id,code,question_number,question_text,answer_type) VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)','isiss',[$sectionId,$q['id'],$q['number'],$q['text'],$q['type']]);
            $questionId=$db->insert_id;
            foreach($q['options'] as $n=>$label) youth_sql($db,'INSERT INTO youth_survey_options(question_id,option_code,option_text) VALUES (?,?,?) ON DUPLICATE KEY UPDATE id=id','iss',[$questionId,(string)($n+1),$label]);
        }
    }
    return $formId;
}
// Caller owns the transaction: header, answers and choices commit or roll back together.
function youth_store_relational($db,$responseId,$area,$schema,$answers) {
    $existing=youth_sql($db,'SELECT COUNT(*) AS n FROM youth_survey_answers WHERE response_id=?','i',[$responseId])->get_result()->fetch_assoc()['n'];
    if((int)$existing===count($schema['questions']))return;
    if((int)$existing!==0)throw new RuntimeException('Partial relational response');
    if(count($answers)!==count($schema['questions']))throw new RuntimeException('Incomplete response snapshot');
    $formId=youth_form_id($db,$area,$schema);
    youth_sql($db,'INSERT INTO youth_survey_response_details(response_id,form_id) VALUES (?,?)','ii',[$responseId,$formId]);
    foreach($schema['questions'] as $q) {
        $item=$answers[$q['id']]??null;
        if(!$item || !in_array($item['status'],['answered','skipped','not_applicable'],true))throw new RuntimeException('Invalid answer status');
        $question=youth_sql($db,'SELECT q.id,q.answer_type FROM youth_survey_questions q JOIN youth_survey_sections s ON s.id=q.section_id WHERE s.form_id=? AND q.code=?','is',[$formId,$q['id']])->get_result()->fetch_assoc();
        $questionId=$question['id'];$status=$item['status'];$value=$item['value'];
        $text=$status==='answered' && $question['answer_type']==='text'?$value:null;
        youth_sql($db,'INSERT INTO youth_survey_answers(response_id,question_id,status,text_value) VALUES (?,?,?,?)','iiss',[$responseId,$questionId,$status,$text]);
        $answerId=$db->insert_id;
        if($status!=='answered' || $question['answer_type']==='text')continue;
        $codes=$question['answer_type']==='multiple'?$value:[$value];
        if(!is_array($codes) || !$codes)throw new RuntimeException('Missing selected options');
        foreach($codes as $code) {
            $option=youth_sql($db,'SELECT id FROM youth_survey_options WHERE question_id=? AND option_code=?','is',[$questionId,(string)$code])->get_result()->fetch_assoc();
            if(!$option)throw new RuntimeException('Unknown historical option');
            youth_sql($db,'INSERT INTO youth_survey_answer_choices(answer_id,question_id,option_id) VALUES (?,?,?)','iii',[$answerId,$questionId,$option['id']]);
        }
    }
}
