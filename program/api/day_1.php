<?
include_once('../admin/indyEngine/dbControl.php');
$query = new DBControl();

$user_id = $_COOKIE["user_id"];
$day = $_POST['day'];
$n = $_POST['n'];
$q1 = $_POST['q1'];

$date = date('Y-m-d');

$sql = 'INSERT INTO day(user_id,day,n,date) VALUES ('.$user_id.','.$day.','.$n.',"'.$date.'")';
$query->queryDB($sql);

if($n==0){
    echo '
    <script> 
        Swal.fire({
            html: "<h2><b>ไชโย ! </b></h2> <br>คุณทำได้คุณหยุดสูบบุหรี่ได้สำเร็จ",
            type: "success",
            confirmButtonText: " ตกลง ",
            confirmButtonColor: "#3085d6",
            timer: 3000, 
        }).then((result) => {
            location.href = "home.php";
        });
    </script>
    ';
}
else if($n<$q1){
    echo '
    <script> 
        Swal.fire({
            html: "<h2><b>ดีใจด้วยวันนี้</b></h2> <br>วันนี้คุณทำสำเร็จ คุณสูบบุหรี่ลดลง !",
            type: "success",
            confirmButtonText: " ตกลง ",
            confirmButtonColor: "#3085d6",
            timer: 3000, 
        }).then((result) => {
            location.href = "home.php";
        });
    </script>
    ';
}
else{
    echo '
    <script> 
        Swal.fire({
            html: "<h3>วันนี้คุณยังไประสบความสำเร็จ</h3> <br> แต่ไม่เป็นไร อย่าตำหนิตัวเอง <br> <b>พรุ่งนี้เริ่มต้นใหม่ !</b>",
            type: "error",
            confirmButtonText: " ตกลง ",
            confirmButtonColor: "#3085d6",
            timer: 3000, 
        }).then((result) => {
            location.href = "home.php";
        });
    </script>
    '; 
}
?>