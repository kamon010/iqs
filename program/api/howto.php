<?
include_once('../admin/indyEngine/dbControl.php');
$query = new DBControl();

$user_id = $_COOKIE["user_id"];
$other_name = $_POST['other_name'];
$date = date('Y-m-d');$sql = 'INSERT INTO howto (user_id,date) VALUES ('.$user_id.',"'.$date.'")';
$query->queryDB($sql);

$sql = 'UPDATE user SET user_other_name="'.$other_name.'" WHERE user_id='.$user_id;
$query->queryDB($sql);

echo '
<script> 
    Swal.fire({
        html: "ระบบทำการบันทึกข้อมูลของท่านแล้ว !",
        type: "success",
        confirmButtonText: " ตกลง ",
        confirmButtonColor: "#3085d6",
        timer: 3000, 
    }).then((result) => {
        location.href = "commit_1.php";
    });
</script>
';
?>