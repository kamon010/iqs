<?php
include_once('../admin/indyEngine/dbControl.php');
$query = new DBControl();

$user_id = isset($_COOKIE["user_id"]) ? intval($_COOKIE["user_id"]) : 0;

/*
    รับค่า aws จากหน้า test_e.php
    aws = 1 คือ สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า
    aws = 3 คือ สูบบุหรี่ไฟฟ้าอย่างเดียว
*/
$aws = isset($_POST["aws"]) ? intval($_POST["aws"]) : 0;

$q1 = isset($_POST["q1"]) ? intval($_POST["q1"]) : 0;
$q2 = isset($_POST["q2"]) ? intval($_POST["q2"]) : 0;
$q3 = isset($_POST["q3"]) ? intval($_POST["q3"]) : 0;
$q4 = isset($_POST["q4"]) ? intval($_POST["q4"]) : 0;
$q5 = isset($_POST["q5"]) ? intval($_POST["q5"]) : 0;
$q6 = isset($_POST["q6"]) ? intval($_POST["q6"]) : 0;

$date = date('Y-m-d H:i:s');
$week = 0;

if ($user_id <= 0) {
	echo '
	<script>
		Swal.fire({
			html: "กรุณาเข้าสู่ระบบใหม่ !",
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

if (
	$q1 > 0 &&
	$q2 > 0 &&
	$q3 > 0 &&
	$q4 > 0 &&
	$q5 > 0 &&
	$q6 > 0
) {

	$score = $q1 + $q2 + $q3 + $q4 + $q5 + $q6;

	/*
	    บันทึกข้อมูลลง test_e
	    ห้ามใส่ id เพราะให้ฐานข้อมูล AUTO_INCREMENT เอง
	*/
	$sql = '
		INSERT INTO test_e (
			user_id,
			week,
			score,
			q1,
			q2,
			q3,
			q4,
			q5,
			q6,
			date
		) VALUES (
			'.$user_id.',
			'.$week.',
			'.$score.',
			'.$q1.',
			'.$q2.',
			'.$q3.',
			'.$q4.',
			'.$q5.',
			'.$q6.',
			"'.$date.'"
		)
	';

	$chk = $query->queryDB($sql);

	if ($chk) {

		/*
		    ถ้าทำบุหรี่ไฟฟ้าเสร็จแล้ว ให้ไปหน้าผลบุหรี่ไฟฟ้า
		    กรณี aws = 1 คือทำต่อมาจากบุหรี่มวนแล้ว
		    ดังนั้นถือว่าทำครบทั้งสองแบบประเมินแล้ว
		*/
		echo '
		<script>
			Swal.fire({
				html: "บันทึกแบบประเมินบุหรี่ไฟฟ้าของท่านเรียบร้อย !",
				type: "success",
				confirmButtonText: "ตกลง",
				confirmButtonColor: "#3085d6"
			}).then((result) => {
				location.href = "report_e.php";
			});
		</script>
		';

	} else {

		echo '
		<script>
			Swal.fire({
				html: "ไม่สามารถบันทึกข้อมูลได้ !",
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