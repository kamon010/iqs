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
$q7 = $_POST["q7"];
$q8 = $_POST["q8"];
$q9 = $_POST["q9"];
$q10 = $_POST["q10"];
$score = 0;
$date = date('Y-m-d H:i:s');

if($q1!='' && $q2!='' && $q3!='' && $q4!='' && $q5!='' && $q5!='' && $q5!='' && $q5!='' && $q5!='' && $q5!=''){

    if($q1==2){
        $score+=1;
    }
    if($q2==4){
        $score+=1;
    }
    if($q3==2){
        $score+=1;
    }
    if($q4==1){
        $score+=1;
    }
    if($q5==2){
        $score+=1;
    }
    if($q6==4){
        $score+=1;
    }
    if($q7==2){
        $score+=1;
    }
    if($q8==4){
        $score+=1;
    }
    if($q9==4){
        $score+=1;
    }
    if($q10==1){
        $score+=1;
    }
    

    $sql = 'INSERT INTO game (user_id,score,q1,q2,q3,q4,q5,q6,q7,q8,q9,q10,date) VALUES ('.$user_id.','.$score.','.$q1.','.$q2.','.$q3.','.$q4.','.$q5.','.$q6.','.$q7.','.$q8.','.$q9.','.$q10.',"'.$date.'")';
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
            location.href = "report_game.php";
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