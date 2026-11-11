<?php
require_once __DIR__.'/youth_survey_storage.php';
require_once __DIR__.'/youth_survey_respondent.php';
youth_session();
header('Content-Type: application/json; charset=utf-8');
function reply($code,$data) { http_response_code($code); echo json_encode($data,JSON_UNESCAPED_UNICODE); exit; }
if ($_SERVER['REQUEST_METHOD']!=='POST') reply(405,['error'=>'ใช้ POST เท่านั้น']);
if ((int)($_SERVER['CONTENT_LENGTH']??0)>24000) reply(413,['error'=>'ข้อมูลมีขนาดใหญ่เกินไป']);
try {
    $data=json_decode(file_get_contents('php://input'),true,32,JSON_THROW_ON_ERROR);
    if (!is_array($data)) throw new InvalidArgumentException('ข้อมูลไม่ถูกต้อง');
    if (!is_string($data['csrf']??null) || !isset($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'],$data['csrf'])) reply(403,['error'=>'เซสชันหมดอายุ กรุณาเปิดหน้าใหม่']);
    require_once __DIR__.'/../admin/indyEngine/dbControl.php';
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $db=(new DBControl())->connect;
    $action=$data['action']??'submit';
    if ($action!=='submit') throw new InvalidArgumentException('คำสั่งไม่ถูกต้อง');
    foreach (['assent','guardian','eligible'] as $field) if (($data[$field]??null)!==true) throw new InvalidArgumentException('กรุณาตรวจคุณสมบัติและความยินยอมก่อนเริ่ม');
    $area=$data['area']??'';
    if (!is_string($area) || !isset(youth_areas()[$area])) throw new InvalidArgumentException('กรุณาเลือกพื้นที่ที่กำหนด');
    $submission=$data['submission']??'';
    if (!is_string($submission) || !isset($_SESSION['submissions'][$submission])) reply(403,['error'=>'รหัสการส่งไม่ถูกต้อง กรุณาเปิดหน้าใหม่']);
    $receipt=$_SESSION['submissions'][$submission];
    $receiptHash=hash('sha256',$receipt); $submissionHash=hash('sha256',$submission);
    $answerData=youth_record_answers($data['answers']??null,$area);
    $answers=json_encode($answerData,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR);
    $version=youth_schema($area)['version']; $consent='2026-10-28-v3';
    $respondentKey=youth_respondent_key($db);
    $db->begin_transaction();
    $stmt=$db->prepare('INSERT INTO youth_survey_responses (receipt_hash,submission_hash,schema_version,consent_version,guardian_confirmed,answers,area_code,respondent_key) VALUES (?,?,?,?,1,?,?,?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)');
    $stmt->bind_param('sssssss',$receiptHash,$submissionHash,$version,$consent,$answers,$area,$respondentKey); $stmt->execute();
    $responseId=$db->insert_id;
    // Reuse the committed original snapshot on retries; never overwrite prior answers.
    $saved=youth_sql($db,'SELECT area_code,schema_version,answers FROM youth_survey_responses WHERE id=? FOR UPDATE','i',[$responseId])->get_result()->fetch_assoc();
    $savedArea=$saved['area_code']?:'legacy_unknown';
    youth_store_relational($db,$responseId,$savedArea,youth_historical_schema($saved['schema_version'],$savedArea),json_decode($saved['answers'],true,512,JSON_THROW_ON_ERROR));
    $db->commit();
    reply(200,['message'=>'บันทึกคำตอบแล้ว']);
} catch (JsonException | InvalidArgumentException $e) { if(isset($db)) $db->rollback(); reply(422,['error'=>$e instanceof JsonException?'รูปแบบข้อมูลไม่ถูกต้อง':$e->getMessage()]); }
catch (Throwable $e) { if(isset($db)) $db->rollback(); error_log('Youth survey request failed: '.get_class($e)); reply(500,['error'=>'ยังบันทึกไม่ได้ กรุณาลองอีกครั้ง คำตอบบนหน้านี้ยังอยู่']); }
