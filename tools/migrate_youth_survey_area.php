<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once __DIR__.'/../program/admin/indyEngine/dbControl.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db=(new DBControl())->connect;
$column=$db->query("SHOW COLUMNS FROM youth_survey_responses LIKE 'area_code'");
if ($column->num_rows===0) $db->query(file_get_contents(__DIR__.'/../database/migrations/20261028_youth_survey_area.sql'));
echo "Area migration ready; existing responses unchanged.\n";
