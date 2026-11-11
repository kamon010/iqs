<?php
// A keyed reference allows repeated submissions to be recognized without storing account IDs.
function youth_respondent_key($db) {
    $userId=filter_var($_COOKIE['user_id']??'',FILTER_VALIDATE_INT);
    if(!$userId || $userId<1)return null;
    $stmt=$db->prepare('SELECT user_id FROM user WHERE user_id=? LIMIT 1');
    $stmt->bind_param('i',$userId);$stmt->execute();
    if(!$stmt->get_result()->fetch_assoc())return null;
    $secret=require __DIR__.'/../admin/indyEngine/youth_survey_key.php';
    return hash_hmac('sha256','youth-account:'.$userId,$secret);
}
