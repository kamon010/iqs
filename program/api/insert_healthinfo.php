<?
include_once('../admin/indyEngine/dbControl.php');
$query = new DBControl();

$user_id = $_COOKIE["user_id"];
$high = $_POST['high'];
$weight = $_POST['weight'];

$date = date('Y-m-d H:i:s');

echo $sql = 'INSERT INTO healthinfo (user_id,high,weight,date) VALUES ('.$user_id.','.$high.','.$weight.',"'.$date.'")';
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