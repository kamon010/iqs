<?
include_once('../admin/indyEngine/dbControl.php');

$query = new DBControl();

$user_id = isset($_COOKIE["user_id"]) ? intval($_COOKIE["user_id"]) : 0;
$day = isset($_POST['day']) ? intval($_POST['day']) : 0;
$n = isset($_POST['n']) ? intval($_POST['n']) : 0;

$date = date('Y-m-d');

if ($user_id <= 0) {
    echo '
    <script>
        Swal.fire({
            html: "<h3>กรุณาเข้าสู่ระบบใหม่</h3>",
            icon: "warning",
            confirmButtonText: "ตกลง",
            confirmButtonColor: "#3085d6"
        }).then(() => {
            location.href = "login.php";
        });
    </script>
    ';
    exit;
}

if ($day <= 0) {
    echo '
    <script>
        Swal.fire({
            html: "<h3>ข้อมูลวันที่ไม่ถูกต้อง</h3>",
            icon: "error",
            confirmButtonText: "ตกลง",
            confirmButtonColor: "#3085d6"
        });
    </script>
    ';
    exit;
}

if ($n < 0) {
    $n = 0;
}

/* เช็กก่อนว่าวันนี้ user คนนี้เคยบันทึกวันนี้ไปแล้วหรือยัง */
$already = $query->queryDB_to_row(
    'SELECT `id`, `n` 
     FROM `day` 
     WHERE `user_id` = '.$user_id.' 
     AND `day` = '.$day.' 
     AND `date` = "'.$date.'" 
     ORDER BY `id` DESC 
     LIMIT 1'
);

/* ถ้ามีข้อมูลแล้ว ไม่ INSERT ซ้ำ และให้กลับไปหน้าแสดงผล */
if (!empty($already) && isset($already['id'])) {
    echo '
    <script>
        Swal.fire({
            html: "<h3>วันนี้บันทึกผลไปแล้ว</h3><br>ระบบจะแสดงหน้าสรุปผลให้คุณ",
            icon: "info",
            confirmButtonText: "ตกลง",
            confirmButtonColor: "#3085d6",
            timer: 1800
        }).then(() => {
            location.href = "days.php?d='.$day.'&chk=1";
        });
    </script>
    ';
    exit;
}

/* ดึงค่าก่อนหน้าเพื่อใช้เปรียบเทียบว่าลดลงไหม */
$old = $query->queryDB_to_string(
    'SELECT `n` 
     FROM `day` 
     WHERE `user_id` = '.$user_id.' 
     ORDER BY `id` DESC 
     LIMIT 1'
);

$old = ($old === '' || $old === null) ? $n : intval($old);

/* INSERT แค่ครั้งแรกของวันนั้นเท่านั้น */
$sql = '
    INSERT INTO `day` (`user_id`, `day`, `n`, `date`) 
    VALUES ('.$user_id.', '.$day.', '.$n.', "'.$date.'")
';

$insert = $query->queryDB($sql);

if ($insert !== true) {
    echo '
    <script>
        Swal.fire({
            html: "<h3>บันทึกข้อมูลไม่สำเร็จ</h3><br>'.addslashes($insert).'",
            icon: "error",
            confirmButtonText: "ตกลง",
            confirmButtonColor: "#3085d6"
        });
    </script>
    ';
    exit;
}

if ($n == 0) {
    echo '
    <script> 
        Swal.fire({
            html: "<h2><b>ไชโย!</b></h2><br>คุณทำได้ คุณหยุดสูบบุหรี่ได้สำเร็จ",
            icon: "success",
            confirmButtonText: "ตกลง",
            confirmButtonColor: "#3085d6",
            timer: 3000
        }).then(() => {
            location.href = "days.php?d='.$day.'&chk=1";
        });
    </script>
    ';
} else if ($n < $old) {
    echo '
    <script> 
        Swal.fire({
            html: "<h2><b>ดีใจด้วยวันนี้</b></h2><br>วันนี้คุณทำสำเร็จ คุณสูบบุหรี่ลดลง!",
            icon: "success",
            confirmButtonText: "ตกลง",
            confirmButtonColor: "#3085d6"
        }).then(() => {
            location.href = "days.php?d='.$day.'&chk=1";
        });
    </script>
    ';
} else {
    echo '
    <script> 
        Swal.fire({
            html: "<h3>วันนี้คุณยังไม่ประสบความสำเร็จ</h3><br>แต่ไม่เป็นไร อย่าตำหนิตัวเอง<br><b>พรุ่งนี้เริ่มต้นใหม่!</b>",
            icon: "error",
            confirmButtonText: "ตกลง",
            confirmButtonColor: "#3085d6"
        }).then(() => {
            location.href = "days.php?d='.$day.'&chk=1";
        });
    </script>
    ';
}
?>