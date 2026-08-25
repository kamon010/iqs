<?php
include_once('../admin/indyEngine/dbControl.php');
$query = new DBControl();

$user_id = $_COOKIE["user_id"] ?? 0;

$q1 = $_POST["q1"] ?? '';
$q2 = $_POST["q2"] ?? '';
$q3 = $_POST["q3"] ?? '';
$q4 = $_POST["q4"] ?? '';
$q5 = $_POST["q5"] ?? '';

$date = date('Y-m-d H:i:s');

if ($user_id == 0) {
	echo '
	<script>
		Swal.fire({
			html: "กรุณาเข้าสู่ระบบก่อน !",
			type: "error",
			confirmButtonText: "ตกลง",
			confirmButtonColor: "#3085d6"
		}).then((result) => {
			location.href = "login.php";
		});
	</script>
	';
	exit;
}

if ($q1 != '' && $q2 != '' && $q3 != '' && $q4 != '' && $q5 != '') {

	$user_id = intval($user_id);

	$q1 = intval($q1);
	$q2 = intval($q2);
	$q3 = intval($q3);
	$q4 = intval($q4);
	$q5 = intval($q5);

	$score = $q1 + $q2 + $q3 + $q4 + $q5;

	$old_week = intval($query->queryDB_to_string(
		'SELECT week FROM test_b WHERE user_id=' . $user_id . ' ORDER BY id DESC LIMIT 1'
	));

	$week = $old_week + 1;

	$sql = '
		INSERT INTO test_b (
			user_id,
			week,
			score,
			q1,
			q2,
			q3,
			q4,
			q5,
			date
		) VALUES (
			' . $user_id . ',
			' . $week . ',
			' . $score . ',
			' . $q1 . ',
			' . $q2 . ',
			' . $q3 . ',
			' . $q4 . ',
			' . $q5 . ',
			"' . $date . '"
		)
	';

	$chk = $query->chkQuery($sql);

	if ($chk === true) {
		echo '
		<script>
			Swal.fire({
				html: "บันทึกแบบประเมินของท่านเรียบร้อย !",
				type: "success",
				confirmButtonText: "ตกลง",
				confirmButtonColor: "#3085d6"
			}).then((result) => {
				location.href = "report_b.php";
			});
		</script>
		';
	} else {
		echo '
		<script>
			Swal.fire({
				html: "ไม่สามารถบันทึกข้อมูลได้<br><small>' . addslashes($chk) . '</small>",
				type: "error",
				confirmButtonText: "ตกลง",
				confirmButtonColor: "#3085d6"
			});
		</script>
		';
	}

} else {
	echo '
	<script>
		Swal.fire({
			html: "กรุณาตอบแบบสอบถามให้ครบ !",
			type: "error",
			confirmButtonText: "ตกลง",
			confirmButtonColor: "#3085d6"
		});
	</script>
	';
}
?>