<?php
include_once('../admin/indyEngine/dbControl.php');
$query = new DBControl();

$user_id = isset($_COOKIE["user_id"]) ? intval($_COOKIE["user_id"]) : 0;
$aws = isset($_POST["q1"]) ? intval($_POST["q1"]) : 0;
$date = date('Y-m-d H:i:s');

if ($user_id <= 0) {
    echo '
    <script>
        Swal.fire({
            html: "กรุณาเข้าสู่ระบบใหม่",
            type: "error",
            confirmButtonText: "ตกลง",
            confirmButtonColor: "#3085d6"
        }).then((result) => {
            location.href = "login.php";
        });
    </script>
    ';
    exit;
}

if ($aws <= 0) {
    echo '
    <script>
        Swal.fire({
            html: "กรุณาเลือกคำตอบคัดกรอง",
            type: "error",
            confirmButtonText: "ตกลง",
            confirmButtonColor: "#3085d6"
        });
    </script>
    ';
    exit;
}

$sql = '
    INSERT INTO screening (
        user_id,
        aws,
        date
    ) VALUES (
        '.$user_id.',
        '.$aws.',
        "'.$date.'"
    )
';

$chk = $query->queryDB($sql);

if (!$chk) {
    echo '
    <script>
        Swal.fire({
            html: "ไม่สามารถบันทึกข้อมูลคัดกรองได้",
            type: "error",
            confirmButtonText: "ตกลง",
            confirmButtonColor: "#3085d6"
        });
    </script>
    ';
    exit;
}

/*
    aws = 1 คือ สูบบุหรี่มวนสลับกับบุหรี่ไฟฟ้า / สูบทั้งสองอย่าง
    aws = 2 คือ สูบบุหรี่มวนอย่างเดียว
    aws = 3 คือ สูบบุหรี่ไฟฟ้าอย่างเดียว
    aws = 4 คือ ไม่สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า
*/

if ($aws == 1) {

    echo '
    <script>
        location.href = "test.php?aws=1";
    </script>
    ';

} else if ($aws == 2) {

    echo '
    <script>
        location.href = "test.php?aws=2";
    </script>
    ';

} else if ($aws == 3) {

    echo '
    <script>
        location.href = "test_e.php?aws=3";
    </script>
    ';

} else if ($aws == 4) {

    echo '
    <script>
        location.href = "test_b.php?aws=4";
    </script>
    ';

} else {

    echo '
    <script>
        location.href = "test_b.php";
    </script>
    ';

}
?>