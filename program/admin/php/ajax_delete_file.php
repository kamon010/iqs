<?
include_once('../indyEngine/dbControl.php');

$file  = isset($_POST['file']) ? trim($_POST['file']) : '';
$field = isset($_POST['field']) ? trim($_POST['field']) : '';
$tb    = isset($_POST['tb']) ? trim($_POST['tb']) : '';
$key   = isset($_POST['key']) ? trim($_POST['key']) : '';
$id    = isset($_POST['id']) ? trim($_POST['id']) : '';

$path = __DIR__ . '/../../uploads/images/';

if ($file == '') {
	echo '
	<script>
		console.log("ไม่มีชื่อไฟล์ที่ต้องการลบ");
	</script>
	';
	exit;
}

/* ป้องกันชื่อไฟล์แปลก ๆ หรือ path traversal */
$file = basename($file);
$fullPath = $path . $file;

/* ลบไฟล์เฉพาะกรณีที่ไฟล์มีอยู่จริง */
if (file_exists($fullPath) && is_file($fullPath)) {

	$chk = unlink($fullPath);

	if ($chk) {
		$name = pathinfo($file, PATHINFO_FILENAME);

		echo '
		<script>
			$("#' . addslashes($name) . '").fadeOut();
		</script>
		';
	}

} else {
	echo '
	<script>
		console.log("ไม่พบไฟล์: ' . addslashes($fullPath) . '");
	</script>
	';
}

/* ถ้ามี id ส่งมา ให้เคลียร์ชื่อไฟล์ในฐานข้อมูล */
if ($id != '' && $tb != '' && $field != '' && $key != '') {

	$query = new DBControl();

	$tb_safe = preg_replace('/[^a-zA-Z0-9_]/', '', $tb);
	$field_safe = preg_replace('/[^a-zA-Z0-9_]/', '', $field);
	$key_safe = preg_replace('/[^a-zA-Z0-9_]/', '', $key);
	$id_safe = addslashes($id);

	$sql = 'UPDATE `' . $tb_safe . '` 
			SET `' . $field_safe . '` = "" 
			WHERE `' . $key_safe . '` = "' . $id_safe . '"';

	$query->queryDB($sql);
}