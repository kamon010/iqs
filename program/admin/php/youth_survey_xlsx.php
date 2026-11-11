<?php
// Native XLSX export. All user text is an inline string, never an Excel formula.
function yx_xml($value) {
    $value=preg_replace('/[^\x{9}\x{A}\x{D}\x{20}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]/u','',(string)$value);
    return htmlspecialchars($value,ENT_XML1|ENT_QUOTES,'UTF-8');
}
function yx_col($n){$s='';do{$s=chr(65+$n%26).$s;$n=intdiv($n,26)-1;}while($n>=0);return $s;}
function yx_sheet($zip,$name,$rows,$widths=[]){
    $path=tempnam(sys_get_temp_dir(),'trc-sheet-');$f=fopen($path,'wb');
    try {
        fwrite($f,'<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews><cols>');
        foreach($widths as $i=>$width)fwrite($f,'<col min="'.($i+1).'" max="'.($i+1).'" width="'.$width.'" customWidth="1"/>');
        fwrite($f,'</cols><sheetData>');$r=0;$columns=1;
        foreach($rows as $values){$r++;$columns=max($columns,count($values));fwrite($f,'<row r="'.$r.'">');foreach(array_values($values) as $i=>$value)fwrite($f,'<c r="'.yx_col($i).$r.'" t="inlineStr" s="'.($r===1?'1':'0').'"><is><t xml:space="preserve">'.yx_xml($value).'</t></is></c>');fwrite($f,'</row>');}
        fwrite($f,'</sheetData><autoFilter ref="A1:'.yx_col($columns-1).max(1,$r).'"/></worksheet>');fclose($f);$f=null;
        if(!$zip->addFile($path,$name))throw new RuntimeException('Cannot add worksheet');
        return $path;
    }catch(Throwable $e){if(is_resource($f))fclose($f);unlink($path);throw $e;}
}
function youth_export_xlsx($db,$join,$where,$types,$params,$area,$status){
    $tmp=tempnam(sys_get_temp_dir(),'trc-export-');$sheetFiles=[];$zip=new ZipArchive();
    try {
        $db->begin_transaction(MYSQLI_TRANS_START_READ_ONLY);
        $responses=youth_sql($db,'SELECT r.id,r.created_at,r.withdrawn,ar.code,ar.subdistrict,ar.district,ar.province,f.version'.$join.$where.' ORDER BY r.id DESC',$types,$params)->get_result()->fetch_all(MYSQLI_ASSOC);
        $result=youth_sql($db,'SELECT v.* FROM youth_survey_answer_report v WHERE v.response_id IN (SELECT r.id'.$join.$where.') ORDER BY v.response_id DESC,v.section_number,v.question_number,CAST(v.option_code AS UNSIGNED)',$types,$params)->get_result();
        $answers=[];
        while($row=$result->fetch_assoc()){
            $rid=$row['response_id'];$code=$row['question_code'];
            if(!isset($answers[$rid][$code])){$answers[$rid][$code]=$row;$answers[$rid][$code]['labels']=[];$answers[$rid][$code]['codes']=[];}
            if($row['option_code']!==null){$answers[$rid][$code]['labels'][]=$row['option_code'].'. '.$row['option_text'];$answers[$rid][$code]['codes'][]=$row['option_code'];}
        }
        $db->commit();
        if($zip->open($tmp,ZipArchive::OVERWRITE)!==true)throw new RuntimeException('Cannot create Excel file');
        $codes=[];foreach([5,9,7,5,4] as $s=>$count)foreach(range(1,$count) as $q)$codes[]='s'.($s+1).'q'.$q;
        $label=function($a){if(!$a)return 'ไม่พบข้อมูล';if($a['status']==='skipped')return 'ไม่ได้ตอบ';if($a['status']==='not_applicable')return 'ไม่เข้าเงื่อนไข';return $a['answer_type']==='text'?$a['text_value']:implode(' | ',$a['labels']);};
        $questions=[];
        foreach(youth_schema($area?:'thung_saliam')['questions'] as $q)$questions[$q['id']]=$q['text'];
        // Prefer question wording saved with the exported response version.
        if($responses)foreach($answers[$responses[0]['id']]??[] as $code=>$a)$questions[$code]=$a['question_text'];
        $wide=(function()use($responses,$answers,$codes,$label,$questions){
            $headers=[];
            foreach($codes as $code){
                preg_match('/^s(\d+)q(\d+)$/',$code,$parts);
                $headers[]='ส่วนที่ '.$parts[1].' ข้อที่ '.$parts[2].': '.$questions[$code];
            }
            yield array_merge($headers,['วันที่บันทึกข้อมูล (เวลาไทย)']);
            foreach($responses as $r){
                $values=[];
                foreach($codes as $code)$values[]=$label($answers[$r['id']][$code]??null);
                yield array_merge($values,[$r['created_at']]);
            }
        })();
        $long=(function()use($responses,$answers,$label){yield ['ชุดคำตอบ','วันที่ส่ง (เวลาไทย)','รหัสพื้นที่','ตำบล','อำเภอ','จังหวัด','รุ่นแบบสอบถาม','สถานะชุดคำตอบ','ส่วนที่','ชื่อส่วน','รหัสข้อ','ข้อที่','คำถาม','ประเภท','สถานะคำตอบ','รหัสตัวเลือก','คำตอบ'];foreach($responses as $r)foreach($answers[$r['id']]??[] as $a)yield [$r['id'],$r['created_at'],$r['code'],$r['subdistrict'],$r['district'],$r['province'],$r['version'],$r['withdrawn']?'ไม่นำไปวิเคราะห์':'ใช้วิเคราะห์',$a['section_number'],$a['section_title'],$a['question_code'],$a['question_number'],$a['question_text'],$a['answer_type'],$a['status'],implode(' | ',$a['codes']),$label($a)];})();
        $meta=[['รายการ','ค่าที่ใช้ส่งออก'],['พื้นที่',$area?:'ทุกพื้นที่'],['สถานะ',['active'=>'ใช้วิเคราะห์','withdrawn'=>'ไม่นำไปวิเคราะห์ / ข้อมูลทดสอบ','all'=>'ทั้งหมด'][$status]],['จำนวนชุดคำตอบ',count($responses)],['ส่งออกเมื่อ (เวลาไทย)',date('Y-m-d H:i:s')],['ขอบเขต','เฉพาะชุดล่าสุดต่อผู้ใช้และตำบล ภายใต้ตัวกรองทุกหน้า; ข้อมูลเก่าที่ไม่เชื่อมผู้ใช้คงแยกชุด'],['หมายเหตุ','ไม่ได้ตอบและไม่เข้าเงื่อนไขแยกจากคำตอบจริง; หลายตัวเลือกคั่นด้วย |']];
        $sheetFiles[]=yx_sheet($zip,'xl/worksheets/sheet1.xml',$wide,array_merge(array_fill(0,30,42),[25]));
        $sheetFiles[]=yx_sheet($zip,'xl/worksheets/sheet2.xml',$long,[14,23,20,20,20,20,25,24,10,40,12,10,70,14,20,20,60]);
        $sheetFiles[]=yx_sheet($zip,'xl/worksheets/sheet3.xml',$meta,[30,90]);
        $zip->addFromString('[Content_Types].xml','<?xml version="1.0" encoding="UTF-8"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/><Override PartName="/xl/worksheets/sheet2.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/><Override PartName="/xl/worksheets/sheet3.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>');
        $zip->addFromString('_rels/.rels','<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');
        $zip->addFromString('xl/workbook.xml','<?xml version="1.0" encoding="UTF-8"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="คำตอบรายชุด" sheetId="1" r:id="rId1"/><sheet name="รายละเอียดรายข้อ" sheetId="2" r:id="rId2"/><sheet name="ตัวกรองที่ใช้" sheetId="3" r:id="rId3"/></sheets></workbook>');
        $zip->addFromString('xl/_rels/workbook.xml.rels','<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet2.xml"/><Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet3.xml"/><Relationship Id="rId4" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>');
        $zip->addFromString('xl/styles.xml','<?xml version="1.0" encoding="UTF-8"?><styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><color rgb="FFFFFFFF"/><sz val="11"/><name val="Calibri"/></font></fonts><fills count="3"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FF245A81"/><bgColor indexed="64"/></patternFill></fill></fills><borders count="1"><border/></borders><cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs><cellXfs count="2"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0" applyAlignment="1"><alignment vertical="top" wrapText="1"/></xf><xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1" applyAlignment="1"><alignment vertical="top" wrapText="1"/></xf></cellXfs><cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles></styleSheet>');
        if(!$zip->close())throw new RuntimeException('Cannot finalize Excel file');
        while(ob_get_level()>0) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="youth-survey-'.($area?:'all-areas').'-'.$status.'-'.date('Ymd-His').'.xlsx"');
        header('Content-Length: '.filesize($tmp));readfile($tmp);
    }finally{if(is_file($tmp))unlink($tmp);foreach($sheetFiles as $file)if(is_file($file))unlink($file);}
}
