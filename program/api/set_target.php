<?
include_once('../admin/indyEngine/dbControl.php');
$query = new DBControl();

$user_id = $_COOKIE["user_id"];
$week_id = $_POST["week_id"];
$goal_type = $_POST["goal_type"];

$sql = 'SELECT COUNT(id) FROM target WHERE user_id ='.$user_id.' AND goal_type="'.$goal_type.'" AND week_id='.$week_id;
$chk = $query->queryDB($sql);
if($chk){
    $sql = 'DELETE FROM target WHERE user_id ='.$user_id.' AND goal_type="'.$goal_type.'" AND week_id='.$week_id;
    $query->queryDB($sql);
}

for($i=1;$i<100;$i++){
    if($_POST['goal_'.$i] == 1){
        $goal_id = $i;
        $sql = 'INSERT INTO target (user_id,week_id,goal_id,goal_type) VALUES ('.$user_id.','.$week_id.','.$goal_id.',"'.$goal_type.'")';
        $chk = $query->queryDB($sql);

        $score = $query->queryDB_to_string('SELECT score FROM score WHERE name="target"');
        $query->queryDB('UPDATE user SET user_score=user_score+'.$score.' WHERE user_id='.$user_id);
    }
}

if($goal_type=='food'){
    $page = 'sodium';
}
else{
    $page = 'increase';
}
echo '
<script> 
    Swal.fire({
        html: "ระบบทำการบันทึกข้อมูลของท่านแล้ว !",
        type: "success",
        confirmButtonText: " ตกลง ",
        confirmButtonColor: "#3085d6",
        timer: 3000, 
    }).then((result) => {
        location.href = "goal_'.$page.'.php?m='.$week_id.'";
    });
</script>
';

// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';
?>