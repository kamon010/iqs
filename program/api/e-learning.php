<?
include_once('../admin/indyEngine/dbControl.php');
$query = new DBControl();

$user_id = $_COOKIE["user_id"];
$q1_1 = $_POST["q1_1"];
$q1_2 = $_POST["q1_2"];
$q1_3 = $_POST["q1_3"];
$q1_4 = $_POST["q1_4"];
$q1_5 = $_POST["q1_5"];
$q1_6 = $_POST["q1_6"];
$q1_7 = $_POST["q1_7"];
$q1_8 = $_POST["q1_8"];
$q1_9 = $_POST["q1_9"];
$q1_10 = $_POST["q1_10"];
$q1_11 = $_POST["q1_11"];
$q1_12 = $_POST["q1_12"];
$q1_13 = $_POST["q1_13"];
$q1_14 = $_POST["q1_14"];

$q2 = $_POST["q2"];
$q3 = $_POST["q3"];
$q4 = $_POST["q4"];
$q5 = $_POST["q5"];
$q6 = $_POST["q6"];
$q7 = $_POST["q7"];
$q8 = $_POST["q8"];
$q9 = $_POST["q9"];
$q10 = $_POST["q10"];
$q11 = $_POST["q11"];
$q12 = $_POST["q12"];
$q13 = $_POST["q13"];
$q14 = $_POST["q14"];
$q15 = $_POST["q15"];
$q16 = $_POST["q16"];
$q17 = $_POST["q17"];


$score = 0;
$date = date('Y-m-d H:i:s');
$q1 = '';
// if($q2!='' && $q3!='' && $q4!='' && $q5!='' && $q6!='' && $q7!='' && $q8!='' && $q9!='' && $q10!='' && $q11!='' && $q12!='' && $q3!='' && $q14!='' && $q15!='' && $q16!='' && $q17!=''){

    if($q1_1==1){
        $score-=1;
        $q1 .= ',1';
    }
    if($q1_2==1){
        $score-=1;
        $q1 .= ',2';
    }
    if($q1_3==1){
        $score-=1;
        $q1 .= ',3';
    }
    if($q1_4==1){
        $score+=1;
        $q1 .= ',4';
    }
    if($q1_5==1){
        $score+=1;
        $q1 .= ',5';
    }
    if($q1_6==1){
        $score-=1;
        $q1 .= ',6';
    }
    if($q1_7==1){
        $score-=1;
        $q1 .= ',7';
    }
    if($q1_8==1){
        $score-=1;
        $q1 .= ',8';
    }
    if($q1_9==1){
        $score-=1;
        $q1 .= ',9';
    }
    if($q1_10==1){
        $score+=1;
        $q1 .= ',10';
    }
    if($q1_11==1){
        $score+=1;
        $q1 .= ',11';
    }
    if($q1_12==1){
        $score-=1;
        $q1 .= ',12';
    }
    if($q1_13==1){
        $score-=1;
        $q1 .= ',13';
    }
    if($q1_14==1){
        $score+=1;
        $q1 .= ',14';
    }
    if($q1_15==1){
        $score-=1;
        $q1 .= ',15';
    }
    if($q1_16==1){
        $score-=1;
        $q1 .= ',16';
    }
    if($q1_17==1){
        $score-=1;
        $q1 .= ',17';
    }

    $q1 = substr($q1,1);
    
    if($q2==4){
        $score+=1;
    }
    if($q3==3){
        $score+=1;
    }
    if($q4==2){
        $score+=1;
    }
    if($q5==3){
        $score+=1;
    }
    if($q6==4){
        $score+=1;
    }
    if($q7==4){
        $score+=1;
    }
    if($q8==4){
        $score+=1;
    }
    if($q9==1){
        $score+=1;
    }
    if($q10==4){
        $score+=1;
    }
    if($q11==4){
        $score+=1;
    }
    if($q12==2){
        $score+=1;
    }
    if($q13==2){
        $score+=1;
    }
    if($q14==2){
        $score+=1;
    }
    if($q15==3){
        $score+=1;
    }
    if($q16==4){
        $score+=1;
    }
    if($q17==3){
        $score+=1;
    }


    $sql = '
    INSERT INTO elearning (
        user_id,score,q1,q2,q3,q4,q5,q6,q7,q8,q9,q10,q11,q12,q13,q14,q15,q16,q17,date
    ) VALUES ('
        .$user_id.','.$score.','
        .'"'.$q1.'","'.$q2.'","'.$q3.'","'.$q4.'","'.$q5.'","'
        .$q6.'","'.$q7.'","'.$q8.'","'.$q9.'","'.$q10.'","'
        .$q11.'","'.$q12.'","'.$q13.'","'.$q14.'","'.$q15.'","'
        .$q16.'","'.$q17.'","'.$date.'"
    )';
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
            location.href = "report_elearning.php";
        });
    </script>
    ';
// }
// else{
//     echo '
// 	<script>
// 		Swal.fire({
// 			html: "กรุณาตอบแบบสอบถามให้ครบ !",
// 			type: "error",
// 			confirmButtonText: " ตกลง ",
// 			confirmButtonColor: "#3085d6",
// 			timer: 5000, 
// 		});
// 	</script>
// 	';
// }
?>