<?php
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
require __DIR__.'/../program/admin/indyEngine/dbControl.php';
mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);$db=(new DBControl())->connect;
if(!$db->query("SHOW COLUMNS FROM youth_survey_responses LIKE 'respondent_key'")->num_rows)$db->query(file_get_contents(__DIR__.'/../database/migrations/20261028_youth_survey_respondent.sql'));
$path=__DIR__.'/../program/admin/indyEngine/youth_survey_key.php';
if(!file_exists($path)) {
 $f=fopen($path,'x');if(!$f)throw new RuntimeException('Cannot create key');
 fwrite($f,"<?php\nreturn '".bin2hex(random_bytes(32))."';\n");fclose($f);chmod($path,0600);
}
echo "Respondent reference migration ready; existing responses remain unlinked.\n";
