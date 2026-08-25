<?php
include('api/app.php');
$query = new DBControl();
chkLogin();

$user_id = isset($_COOKIE["user_id"]) ? (int)$_COOKIE["user_id"] : 0;
?>
<html>
<head>
    <?php
        getHead();
    ?>
</head>
<body class="pages">

    <div class="top_menu">
        <div class="top_header">ผลการประเมิน</div>
        <a href="home.php">
            <div class="bt_back"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
        </a>
    </div>

    <div class="main">
        <div class="container p-3">
            <?php
                /*
                    ดึงผลประเมินล่าสุดของ user คนนี้เท่านั้น
                    เรียงจากวันที่ล่าสุดก่อน ถ้าวันที่ซ้ำกันให้เอา id ล่าสุด
                */
                $a = $query->queryDB_to_row('
                    SELECT * FROM test_a
                    WHERE user_id = '.$user_id.'
                    ORDER BY `date` DESC, id DESC
                    LIMIT 1
                ');

                if (!empty($a)) {

                    $score = (int)$a['score'];

                    if (checkTest($score)) {
                        $str = '
                        <div class="p-2">
                            <a href="howto_1.php">
                                <button id="sent" class="bt_big bg-dark">
                                    <i class="fa fa-exclamation-circle pr-2" aria-hidden="true"></i>
                                    วิธีเลิกบุหรี่
                                </button>
                            </a>
                        </div>';
                    } else {
                        $str = '
                        <div class="p-2">
                            <a href="https://www.thailandquitline.or.th/site/" target="_blank">
                                <button id="sent" class="bt_big bg-dark">
                                    <i class="fa fa-exclamation-circle pr-2" aria-hidden="true"></i>
                                    Quitline 1600
                                </button>
                            </a>
                        </div>';
                    }

                    echo '
                    <div class="box_white">
                        <div class="row">
                            <div class="col-12 text-center">
                                <p><b>คุณติดสารนิโคติน</b></p>
                                '.getEmuji($score).'<br>
                                <h4 class="pt-2">'.$score.' คะแนน</h4>
                                <p style="color:#ff9900">'.getTest($score).'</p>
                            </div>

                            <div class="col-12 text-left">
                                <b>คำแนะนำ</b><br>
                                <small>'.getTextTest($score).'</small>
                            </div>

                            <div class="col-12">
                                '.$str.'
                            </div>
                        </div>
                    </div>
                    ';

                } else {
                    echo '
                    <script>
                        Swal.fire({
                            html: "ขออภัย ! <br> ท่านยังไม่ได้ทำแบบสอบถาม !",
                            type: "error",
                            confirmButtonText: " ตกลง ",
                            confirmButtonColor: "#3085d6",
                            timer: 5000
                        }).then((result) => {
                            location.href = "screening.php";
                        });
                    </script>
                    ';
                }

                function checkTest($score) {
                    if ($score <= 5) {
                        return true;
                    } else {
                        return false;
                    }
                }

                function getTest($score) {
                    if ($score <= 3) {
                        return 'ไม่นับว่าคุณติดสารนิโคติน';
                    } else if ($score <= 5) {
                        return 'ระดับปานกลาง';
                    } else if ($score <= 7) {
                        return 'ระดับปานกลางและมีแนวโน้มอย่างมากในการพัฒนาไปเป็นระดับสูง';
                    } else if ($score <= 9) {
                        return 'ระดับสูง';
                    } else {
                        return 'ระดับสูงมาก';
                    }
                }

                function getTextTest($score) {
                    if ($score <= 3) {
                        return 'ไม่นับว่าติดสารนิโคติน คุณสามารถเลิกสูบบุหรี่ได้ด้วยตนเอง หากคุณเคยล้มเหลวจากการพยายามเลิกด้วยตนเอง คุณอาจลองใช้พฤติกรรมบำบัดบางอย่างช่วยเสริม';
                    } else if ($score <= 5) {
                        return 'ติดสารนิโคตินในระดับปานกลาง คุณสามารถเลิกสูบบุหรี่ได้ด้วยตนเอง หากคุณเคยล้มเหลวจากการพยายามเลิกด้วยตนเอง คุณอาจลองใช้พฤติกรรมบำบัดบางอย่างช่วยเสริม';
                    } else if ($score <= 7) {
                        return 'ติดสารนิโคตินระดับปานกลาง และมีแนวโน้มอย่างมากในการพัฒนาไปเป็นการติดสารนิโคตินในระดับสูง การเลิกสูบบุหรี่เสียตั้งแต่วันนี้ จะทำได้ง่ายกว่าการที่คุณจะยังสูบต่อไปและจะมีแผนเลิกในอนาคต หากคุณเคยล้มเหลวจากการพยายามเลิกบุหรี่ด้วยตนเอง หรือร่วมกับการใช้พฤติกรรมบำบัดเสริม คุณควรอย่างยิ่งที่จะไปพบแพทย์เพื่อรับคำปรึกษาเพื่อการเลิกสูบบุหรี่';
                    } else if ($score <= 9) {
                        return 'ติดสารนิโคตินในระดับสูง คุณควรใส่ใจและให้ความสำคัญกับสุขภาพของคุณ และเอาจริงเอาจังในการเลิกบุหรี่ เพื่อชีวิตที่ดีขึ้น สำหรับคุณการเลิกบุหรี่ด้วยตนเอง หรือการใช้แค่เพียงกำลังใจอย่างเดียวอาจไม่เพียงพอในการรักษา หากคุณเคยล้มเหลวจากการพยายามเลิกบุหรี่ด้วยตนเองหรือร่วมกับการใช้พฤติกรรมบำบัดเสริม หรือวิธีอื่น ๆ รวมทั้งการใช้แผ่นแปะนิโคตินหรือหมากฝรั่งนิโคตินมาแล้ว กรณีนี้ท่านควรพบแพทย์เพื่อรับคำปรึกษาเพื่อการเลิกบุหรี่';
                    } else {
                        return 'ติดสารนิโคตินในระดับสูงมาก คุณควรใส่ใจและให้ความสำคัญกับสุขภาพของคุณ และเอาจริงเอาจังในการเลิกบุหรี่ เพื่อชีวิตที่ดีขึ้น สำหรับคุณการเลิกบุหรี่ด้วยตนเอง หรือการใช้แค่เพียงกำลังใจอย่างเดียวอาจไม่เพียงพอ หากคุณเคยล้มเหลวจากการพยายามเลิกบุหรี่ด้วยตนเองหรือร่วมกับการใช้พฤติกรรมบำบัดเสริม หรือวิธีอื่น ๆ รวมทั้งการใช้แผ่นแปะนิโคตินมาแล้ว กรณีนี้ท่านควรพบแพทย์เพื่อรับคำปรึกษาเพื่อการเลิกบุหรี่';
                    }
                }

                function getEmuji($score) {
                    if ($score <= 3) {
                        return '<img src="img/emoji/1.png" height="60px">';
                    } else if ($score <= 5) {
                        return '<img src="img/emoji/3.png" height="60px">';
                    } else if ($score <= 7) {
                        return '<img src="img/emoji/5.png" height="60px">';
                    } else if ($score <= 9) {
                        return '<img src="img/emoji/7.png" height="60px">';
                    } else {
                        return '<img src="img/emoji/10.png" height="60px">';
                    }
                }
            ?>
        </div>
    </div>

    <div class="footer">
        <div class="container">

            <div class="menu menu-3 text-left"></div>
            
            <a href="home.php">
                <div class="menu menu-3m active">
                    <i class="fa fa-home" aria-hidden="true"></i>
                </div>
            </a>

            <div class="menu menu-3 text-left"></div>

        </div>
    </div>

</body>
<script>
    $(function(){

    });
</script>
</html>