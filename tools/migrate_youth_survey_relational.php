<?php
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
require_once __DIR__.'/../program/admin/indyEngine/dbControl.php';
require_once __DIR__.'/../program/api/youth_survey_storage.php';
mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);
$db=(new DBControl())->connect;
$backup=$argv[1]??'';
if(!$backup || file_exists($backup))throw new RuntimeException('Provide a new backup file outside the web root');
$rows=$db->query('SELECT * FROM youth_survey_responses ORDER BY id')->fetch_all(MYSQLI_ASSOC);
if(file_put_contents($backup,json_encode($rows,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR),LOCK_EX)===false)throw new RuntimeException('Backup failed');
chmod($backup,0600);
$sql=file_get_contents(__DIR__.'/../database/migrations/20261028_youth_survey_relational.sql');
foreach(explode(';',$sql) as $statement)if(trim($statement)!=='')$db->query($statement);
$db->begin_transaction();
try {
 foreach([
 ['thung_saliam','ทุ่งเสลี่ยม','ทุ่งเสลี่ยม','สุโขทัย'],
 ['nam_phai','น้ำไผ่','น้ำปาด','อุตรดิตถ์'],
 ['legacy_unknown','ไม่ระบุในข้อมูลเดิม','ไม่ระบุ','ไม่ระบุ']
 ] as $a)youth_sql($db,'INSERT INTO youth_survey_areas(code,subdistrict,district,province) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE id=id','ssss',$a);
 foreach(array_keys(youth_areas()) as $area)youth_form_id($db,$area,youth_schema($area));
 $rows=$db->query('SELECT * FROM youth_survey_responses ORDER BY id FOR UPDATE')->fetch_all(MYSQLI_ASSOC);
 foreach($rows as $row){
  $area=$row['area_code']?:'legacy_unknown';
  $answers=json_decode($row['answers'],true,512,JSON_THROW_ON_ERROR);
  $schema=youth_historical_schema($row['schema_version'],$area);
  youth_store_relational($db,(int)$row['id'],$area,$schema,$answers);
 }
 $db->commit();echo 'Migration complete: '.count($rows)." existing responses preserved and normalized.\n";
}catch(Throwable $e){$db->rollback();throw $e;}
