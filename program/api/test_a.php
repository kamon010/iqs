<?php
include_once('../admin/indyEngine/dbControl.php');
$query = new DBControl();

$user_id = isset($_COOKIE["user_id"]) ? intval($_COOKIE["user_id"]) : 0;

/*
    รับค่า aws จากหน้า test.php
    aws = 1 คือ สูบบุหรี่มวนสลับกับบุหรี่ไฟฟ้า / สูบทั้งสองอย่าง
    aws = 2 คือ สูบบุหรี่มวนอย่างเดียว
*/
$aws = isset($_POST["aws"]) ? intval($_POST["aws"]) : 0;

$q1 = isset($_POST["q1"]) ? intval($_POST["q1"]) : 0;
$q2 = isset($_POST["q2"]) ? intval($_POST["q2"]) : 0;
$q3 = isset($_POST["q3"]) ? intval($_POST["q3"]) : 0;
$q4 = isset($_POST["q4"]) ? intval($_POST["q4"]) : 0;
$q5 = isset($_POST["q5"]) ? intval($_POST["q5"]) : 0;
$q6 = isset($_POST["q6"]) ? intval($_POST["q6"]) : 0;

$score = 0;
$date = date('Y-m-d H:i:s');
$week = 0;

if ($user_id <= 0) {
    echo '
    <script>
        Swal.fire({
            html: "กรุณาเข้าสู่ระบบใหม่ !",
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

if (
    $q1 > 0 &&
    $q2 > 0 &&
    $q3 > 0 &&
    $q4 > 0 &&
    $q5 > 0 &&
    $q6 > 0
) {

    // คำนวณคะแนนข้อ 1
    if ($q1 == 1) {
        $score += 0;
    } else if ($q1 == 2) {
        $score += 1;
    } else if ($q1 == 3) {
        $score += 2;
    } else if ($q1 == 4) {
        $score += 3;
    }

    // คำนวณคะแนนข้อ 2
    if ($q2 == 1) {
        $score += 3;
    } else if ($q2 == 2) {
        $score += 2;
    } else if ($q2 == 3) {
        $score += 1;
    } else if ($q2 == 4) {
        $score += 0;
    }

    // คำนวณคะแนนข้อ 3
    if ($q3 == 1) {
        $score += 1;
    }

    // คำนวณคะแนนข้อ 4
    if ($q4 == 1) {
        $score += 1;
    }

    // คำนวณคะแนนข้อ 5
    if ($q5 == 1) {
        $score += 1;
    }

    // คำนวณคะแนนข้อ 6
    if ($q6 == 1) {
        $score += 1;
    }

    // บันทึกข้อมูล ห้ามใส่ id เพราะให้ฐานข้อมูลรัน AUTO_INCREMENT เอง
    $sql = '
        INSERT INTO test_a (
            user_id,
            week,
            score,
            q1,
            q2,
            q3,
            q4,
            q5,
            q6,
            date
        ) VALUES (
            '.$user_id.',
            '.$week.',
            '.$score.',
            '.$q1.',
            '.$q2.',
            '.$q3.',
            '.$q4.',
            '.$q5.',
            '.$q6.',
            "'.$date.'"
        )
    ';

    $chk = $query->queryDB($sql);

    if ($chk) {

        /*
            เงื่อนไขหลังทำแบบประเมินบุหรี่มวนเสร็จ

            aws = 1 คือสูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า
            ต้องไปทำแบบประเมินบุหรี่ไฟฟ้าต่อ

            aws = 2 คือสูบบุหรี่มวนอย่างเดียว
            ไปหน้าผลประเมินบุหรี่มวน
        */
        if ($aws == 1) {

            echo '
            <script>
                Swal.fire({
                    html: "บันทึกแบบประเมินบุหรี่มวนเรียบร้อยแล้ว<br>กรุณาทำแบบประเมินบุหรี่ไฟฟ้าต่อ",
                    type: "success",
                    confirmButtonText: "ตกลง",
                    confirmButtonColor: "#3085d6"
                }).then((result) => {
                    location.href = "test_e.php?aws=1";
                });
            </script>
            ';

        } else {

            echo '
            <script>
                Swal.fire({
                    html: "บันทึกแบบประเมินของท่านเรียบร้อย !",
                    type: "success",
                    confirmButtonText: "ตกลง",
                    confirmButtonColor: "#3085d6"
                }).then((result) => {
                    location.href = "report_a.php";
                });
            </script>
            ';

        }

    } else {

        echo '
        <script>
            Swal.fire({
                html: "ไม่สามารถบันทึกข้อมูลได้ !",
                type: "error",
                confirmButtonText: "ตกลง",
                confirmButtonColor: "#3085d6"
            });
        </script>
        ';

    }

} else {

    echo '
    <script>
        Swal.fire({
            html: "กรุณาตอบแบบสอบถามให้ครบ !",
            type: "error",
            confirmButtonText: "ตกลง",
            confirmButtonColor: "#3085d6"
        });
    </script>
    ';

}
?>