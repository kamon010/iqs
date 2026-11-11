<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once __DIR__.'/../program/admin/indyEngine/dbControl.php';
$db = new DBControl();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$sql = file_get_contents(__DIR__.'/../database/migrations/20261028_youth_survey.sql');
$db->connect->query($sql);
echo "youth_survey_responses migration applied (existing tables unchanged).\n";
