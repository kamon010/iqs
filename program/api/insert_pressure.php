<?
include_once('../admin/indyEngine/dbControl.php');
$query = new DBControl();

$user_id = $_COOKIE["user_id"];
$top = $_POST['top'];
$bottom = $_POST['bottom'];

$date = date('Y-m-d H:i:s');

$sql = 'INSERT INTO pressure (user_id,top,bottom,date) VALUES ('.$user_id.','.$top.','.$bottom.',"'.$date.'")';
$chk = $query->queryDB($sql);

echo '
<script> 
    Swal.fire({
        html: "ระบบทำการบันทึกข้อมูลของท่านแล้ว !",
        type: "success",
        confirmButtonText: " ตกลง ",
        confirmButtonColor: "#3085d6",
        timer: 3000, 
    }).then((result) => {
        location.reload();
    });
</script>
';
?>