<?php
require_once('admin/indyEngine/dbControl.php');

$query = new DBControl();

$user_id = $_COOKIE["user_id"] ?? 0;

if ($user_id == 0) {
	die('กรุณาเข้าสู่ระบบ');
}

$user = $query->queryDB_to_row(
	'SELECT * FROM user WHERE user_id=' . intval($user_id)
);

$user_name = $user['user_name'] ?? 'ไม่พบชื่อ';

$date_text = 'ให้ไว้ ณ วันที่ ' . date("d") . ' ' . getThaiMonth(date("m")) . ' ' . (date("Y") + 543);

$background = __DIR__ . '/img/t3.png';

if (!file_exists($background)) {
	die('ไม่พบไฟล์เกียรติบัตร img/t3.png');
}

$image = imagecreatefrompng($background);

if (!$image) {
	die('เปิดไฟล์รูปไม่ได้');
}

$black = imagecolorallocate($image, 0, 0, 0);

$font = __DIR__ . '/fonts/Prompt-Regular.ttf';

if (!file_exists($font)) {
	die('ไม่พบฟอนต์ Prompt-Regular.ttf');
}

$image_width = imagesx($image);

$name_size = 34;
$name_y = 720;

$bbox = imagettfbbox($name_size, 0, $font, $user_name);
$text_width = $bbox[2] - $bbox[0];
$name_x = ($image_width - $text_width) / 2;

imagettftext(
	$image,
	$name_size,
	0,
	$name_x,
	$name_y,
	$black,
	$font,
	$user_name
);

$date_size = 22;
$date_y = 910;

$bbox2 = imagettfbbox($date_size, 0, $font, $date_text);
$text_width2 = $bbox2[2] - $bbox2[0];
$date_x = ($image_width - $text_width2) / 2;

imagettftext(
	$image,
	$date_size,
	0,
	$date_x,
	$date_y,
	$black,
	$font,
	$date_text
);

if (ob_get_length()) {
	ob_clean();
}

header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="certificate_elearning.png"');

imagepng($image);
imagedestroy($image);
exit;

function getThaiMonth($month)
{
	$thai_months = [
		'01' => 'มกราคม',
		'02' => 'กุมภาพันธ์',
		'03' => 'มีนาคม',
		'04' => 'เมษายน',
		'05' => 'พฤษภาคม',
		'06' => 'มิถุนายน',
		'07' => 'กรกฎาคม',
		'08' => 'สิงหาคม',
		'09' => 'กันยายน',
		'10' => 'ตุลาคม',
		'11' => 'พฤศจิกายน',
		'12' => 'ธันวาคม'
	];

	return $thai_months[$month];
}
?>