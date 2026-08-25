<?
include_once('../admin/indyEngine/dbControl.php');
$query = new DBControl();

$id = $_POST["id"];
$day = $_POST["day"];
$chk = $_POST["chk"];

$sql = 'UPDATE target SET day'.$day.'='.$chk.' WHERE id='.$id;
$chk = $query->queryDB($sql);

$count = $query->queryDB_to_string('SELECT day1+day2+day3+day4+day5+day6+day7 FROM target WHERE id='.$id);
$data = $query->queryDB_to_row('SELECT goal_id,goal_type FROM target WHERE id='.$id);
$goal = $query->queryDB_to_string('SELECT goal_condition FROM goal_'.$data['goal_type'].' WHERE id='.$data['goal_id']);

if($count>=$goal){
echo '
    <script> 
        getWin(); 
        $("#p_t'.$id.'").hide();
        $("#p_t'.$id.'").html(\'<img src="img/check.png" width="100px" height="100px">\');
        $("#p_t'.$id.'").fadeIn();
    </script>
';
}
else{
    echo '
    <script> 
        $("#p_t'.$id.'").hide();
        $("#p_t'.$id.'").html(\'<img src="img/cross.png" width="100px" height="100px">\');
        $("#p_t'.$id.'").fadeIn();
    </script>
';
}


// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';
?>