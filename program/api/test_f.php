<?
include_once('../admin/indyEngine/dbControl.php');
$query = new DBControl();

$user_id = $_COOKIE["user_id"];
$q1 = $_POST["q1"];
$q2 = $_POST["q2"];
$q3 = $_POST["q3"];
$q4 = $_POST["q4"];
$q5 = $_POST["q5"];
$q6 = $_POST["q6"];
$score = 0;
$date = date('Y-m-d H:i:s');
$link = '';



if($q1!='' && $q2!='' && $q3!='' && $q4!='' && $q5!=''){
	// if($q1==4 && $q1==4){
	//     $link = 'certificate.php';
	// }
	// else if($q3==4 && $q4==4 && $q5==4){
	//     $link = 'certificate.php';
	// }
	// else{
	// 	$link = 'knowledge.php';
	// }

	$sql = 'INSERT INTO test_f (user_id,q1,q2,q3,q4,q5,q6,date) VALUES ('.$user_id.','.$q1.','.$q2.','.$q3.','.$q4.','.$q5.','.$q6.',"'.$date.'")';
	$chk = $query->queryDB($sql);
	echo '
	<script>
		Swal.fire({
			html: "บันทึกแบบประเมินของท่านเรียบร้อย !",
			type: "success",
			confirmButtonText: " ตกลง ",
			confirmButtonColor: "#3085d6",
			timer: 5000, 
		}).then((result) => {
			location.href = "report_e.php";
		});
	</script>
	';
}
else{
	echo '
	<script>
		Swal.fire({
			html: "กรุณาตอบแบบสอบถามให้ครบ !",
			type: "error",
			confirmButtonText: " ตกลง ",
			confirmButtonColor: "#3085d6",
			timer: 5000, 
		});
	</script>
	';
}
?>