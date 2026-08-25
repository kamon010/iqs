<?
include_once('../admin/indyEngine/dbControl.php');
$query = new DBControl();

$user_id = $_COOKIE["user_id"];
$date = $_POST["sodium_date"];
$foods = $_POST["food"];
$sodiums = $_POST["sodium"];

$sql = 'SELECT COUNT(id) FROM sodium_record WHERE user_id ='.$user_id.' AND date="'.$date.'"';
$chk = intval($query->queryDB($sql));
if($chk>0){
    $sql = 'DELETE FROM sodium_record WHERE user_id ='.$user_id.' AND date="'.$date.'"';
    $query->queryDB($sql);
}

$chk_input = 0;
$max = sizeof($foods);
for($i=0;$i<$max;$i++){
    if($foods[$i]!='' || $sodiums[$i]!=''){
        $sql = 'INSERT INTO sodium_record (user_id, food, sodium, date) VALUES ('.$user_id.',"'.$foods[$i].'","'.$sodiums[$i].'","'.$date.'")';
        $chk = $query->queryDB($sql);
    
        $chk_input++;
    }
}

if($chk_input>0){
echo '
<script> 
    closeModel();
    Swal.fire({
        html: "ระบบทำการบันทึกข้อมูลของท่านแล้ว !",
        type: "success",
        confirmButtonText: " ตกลง ",
        confirmButtonColor: "#3085d6",
        timer: 3000, 
    }).then((result) => {
        location.href = "report.php";
    });
</script>
';
}
else{
echo '
    <script> 
       $("#error_text").html("กรูณากรองข้อมูลให้ครบ !");
    </script>
    ';  
}

// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';

?>