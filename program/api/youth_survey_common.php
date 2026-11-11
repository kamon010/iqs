<?php
function youth_session() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_name('TRC_YOUTH');
        session_start(['cookie_httponly'=>true,'cookie_samesite'=>'Strict','cookie_secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off','use_strict_mode'=>true]);
    }
    header('Cache-Control: no-store, private');
    header('Referrer-Policy: no-referrer');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
}
function youth_areas() {
    return ['thung_saliam'=>'ต.ทุ่งเสลี่ยม อ.ทุ่งเสลี่ยม จ.สุโขทัย', 'nam_phai'=>'ต.น้ำไผ่ อ.น้ำปาด จ.อุตรดิตถ์'];
}
function youth_schema($area = 'thung_saliam') {
    $schema = json_decode(file_get_contents(__DIR__.'/youth_survey_schema.json'),true,512,JSON_THROW_ON_ERROR);
    $schema['version'] = '2026-10-28-v5';
    if ($area === 'thung_saliam') {
        foreach ($schema['questions'] as &$question) {
            if ($question['id'] === 's1q5') {
                $question['options'] = [
                    'หมู่ที่ 1, 8: เหมืองนา',
                    'หมู่ที่ 2: ท่าชุม',
                    'หมู่ที่ 3: ทุ่งเสลี่ยม',
                    'หมู่ที่ 4: วังธาร',
                    'หมู่ที่ 5: เทพนม',
                    'หมู่ที่ 6: ท่าเดื่อ',
                    'หมู่ที่ 7: เด่นดีหมี',
                    'หมู่ที่ 9: ท่าชุมเหนือ',
                    'หมู่ที่ 10: น้ำดิบ',
                    'หมู่ที่ 11: ลุกขมูล',
                    'หมู่ที่ 12: โซกม่วง',
                    'หมู่ที่ 13: ภูแก้ว'
                ];
            }
        }
        unset($question);
    }
    if ($area === 'nam_phai') {
        foreach ($schema['questions'] as &$question) {
            if ($question['id'] === 's1q5') {
                $question['type'] = 'single';
                $question['options'] = [
                    'หมู่ที่ 1: นาผักฮาด',
                    'หมู่ที่ 2: ห้วยเดื่อ',
                    'หมู่ที่ 3: ต้นขนุน',
                    'หมู่ที่ 4: ห้วยคอม',
                    'หมู่ที่ 5: ห้วยเนียม',
                    'หมู่ที่ 6: เพีย',
                    'หมู่ที่ 7: กกหม่อนแก้ว',
                    'หมู่ที่ 8: ปางขามป้อม'
                ];
            }
        }
        unset($question);
    }
    return $schema;
}
function youth_validate($input, $area = 'thung_saliam') {
    if (!is_array($input)) throw new InvalidArgumentException('รูปแบบคำตอบไม่ถูกต้อง');
    $schema=youth_schema($area); $result=[]; $known=array_column($schema['questions'],'id');
    if (array_diff(array_keys($input), $known)) throw new InvalidArgumentException('พบข้อคำถามที่ไม่อยู่ในแบบสอบถาม');
    foreach ($schema['questions'] as $q) {
        $id=$q['id']; $v=$input[$id]??null;
        if (isset($q['depends']) && ($input[$q['depends']]??null)==='2') {
            $result[$id]=['status'=>'not_applicable','value'=>null]; continue;
        }
        if ($v===null || $v==='' || $v===[]) { $result[$id]=['status'=>'skipped','value'=>null]; continue; }
        if ($q['type']==='text') {
            if (!is_string($v) || !mb_check_encoding($v,'UTF-8') || mb_strlen($v)>2000) throw new InvalidArgumentException('ข้อความต้องไม่เกิน 2,000 ตัวอักษร');
            $v=trim($v);
        } else {
            $allowed=array_map('strval',range(1,count($q['options'])));
            if ($q['type']==='multiple') {
                if (!is_array($v) || count($v)>count($allowed)) throw new InvalidArgumentException('ตัวเลือกไม่ถูกต้อง');
                foreach ($v as $x) if (!is_string($x) || !in_array($x,$allowed,true)) throw new InvalidArgumentException('ตัวเลือกไม่ถูกต้อง');
                $v=array_values(array_unique($v));
                if (in_array('1',$v,true) && count($v)>1) throw new InvalidArgumentException('“ไม่ได้รับ” เลือกร่วมกับแหล่งอื่นไม่ได้');
            } elseif (!is_string($v) || !in_array($v,$allowed,true)) throw new InvalidArgumentException('ตัวเลือกไม่ถูกต้อง');
        }
        $result[$id]=['status'=>$v===''?'skipped':'answered','value'=>$v===''?null:$v];
    }
    return $result;
}

function youth_information_for_area($text, $area) {
    if ($area === 'nam_phai') {
        return strtr($text, [
            'ตำบลทุ่งเสลี่ยม'=>'ตำบลน้ำไผ่',
            'อำเภอทุ่งเสลี่ยม'=>'อำเภอน้ำปาด',
            'จังหวัดสุโขทัย'=>'จังหวัดอุตรดิตถ์'
        ]);
    }
    return $text;
}
// Store readable labels alongside validated codes, preserving the exact question version.
function youth_record_answers($input, $area) {
    $answers = youth_validate($input, $area);
    $schema = youth_schema($area);
    foreach ($schema['questions'] as $q) {
        $id = $q['id'];
        $answers[$id]['section'] = $q['section'];
        $answers[$id]['number'] = $q['number'];
        $answers[$id]['question'] = $q['text'];
        $answers[$id]['type'] = $q['type'];
        $answers[$id]['answer_text'] = null;
        if ($answers[$id]['status'] === 'answered') {
            $value = $answers[$id]['value'];
            if ($q['type'] === 'text') {
                $answers[$id]['answer_text'] = $value;
            } elseif ($q['type'] === 'multiple') {
                $answers[$id]['answer_text'] = array_map(function ($code) use ($q) {
                    return $q['options'][(int)$code - 1];
                }, $value);
            } else {
                $answers[$id]['answer_text'] = $q['options'][(int)$value - 1];
            }
        }
    }
    return $answers;
}
