<?php
include('api/app.php');
$query = new DBControl();
chkLogin();

$user_id = $_COOKIE["user_id"] ?? 0;
?>

<html>

<head>
    <?php getHead(); ?>
</head>

<body class="pages">

    <div class="top_menu">
        <div class="top_header">ประวัติผลบุหรี่ไฟฟ้า</div>

        <div class="bt_back pointer" onclick="history.back()">
            <i class="fas fa-angle-left"></i>
        </div>
    </div>

    <div class="main">
        <div class="container p-3">

            <?php

            $list = $query->queryDB_to_array(
                'SELECT * FROM test_e 
                 WHERE user_id=' . intval($user_id) . ' 
                 ORDER BY id DESC'
            );

            if (!empty($list)) {

                foreach ($list as $row) {

                    $score = intval($row['score']);

                    echo '

                    <div class="box_white mb-3">

                        <div class="row">

                            <div class="col-12 text-center">

                                <p style="
                                    font-size:18px;
                                    font-weight:bold;
                                    color:#004b73;
                                    margin-bottom:15px;
                                ">
                                    การติดนิโคตินจากบุหรี่ไฟฟ้า
                                </p>

                                <div class="pb-2">
                                    ' . getEmujiE($score) . '
                                </div>

                                <div style="
                                    color:#ff9900;
                                    font-size:24px;
                                    font-weight:bold;
                                    padding-bottom:15px;
                                ">
                                    ' . getTestE($score) . '
                                </div>

                                <div style="
                                    background:#f5f7fa;
                                    border-radius:30px;
                                    padding:5px;
                                    margin-bottom:15px;
                                ">

                                    <p style="
                                        font-size:15px;
                                        color:#666;
                                        margin-bottom:8px;
                                    ">
                                        วันที่ทำแบบประเมิน  ' . date("d-m-Y", strtotime($row['date'])) . '
                                    </p>

                                </div>

                                <p style="
                                    font-size:18px;
                                    margin-bottom:5px;
                                ">
                                    คะแนนรวมของท่านคือ
                                </p>

                                <div style="
                                    font-size:40px;
                                    font-weight:bold;
                                    color:#004b73;
                                    line-height:1;
                                ">
                                    ' . $score . '
                                </div>

                                <p style="
                                    font-size:18px;
                                    font-weight:bold;
                                    margin-top:8px;
                                ">
                                    คะแนน
                                </p>

                            </div>

                        </div>

                    </div>

                    ';
                }

            } else {

                echo '

                <div class="box_white text-center">

                    <div style="
                        padding:30px;
                    ">

                        <i class="fa fa-folder-open" style="
                            font-size:50px;
                            color:#ccc;
                            margin-bottom:15px;
                        "></i>

                        <p style="
                            font-size:18px;
                            color:#777;
                            margin:0;
                        ">
                            ยังไม่มีประวัติผลประเมิน
                        </p>

                    </div>

                </div>

                ';
            }


            function getTestE($score)
            {
                if ($score < 3) {
                    return 'ต่ำมาก';
                }
                else if ($score < 5) {
                    return 'ต่ำ';
                }
                else if ($score < 7) {
                    return 'ปานกลาง';
                }
                else if ($score < 9) {
                    return 'สูง';
                }
                else {
                    return 'สูงมาก';
                }
            }


            function getEmujiE($score)
            {
                if ($score < 3) {
                    return '<img src="img/emoji/1.png?v=' . time() . '" height="110px">';
                }
                else if ($score < 5) {
                    return '<img src="img/emoji/3.png?v=' . time() . '" height="110px">';
                }
                else if ($score < 7) {
                    return '<img src="img/emoji/5.png?v=' . time() . '" height="110px">';
                }
                else if ($score < 9) {
                    return '<img src="img/emoji/7.png?v=' . time() . '" height="110px">';
                }
                else {
                    return '<img src="img/emoji/10.png?v=' . time() . '" height="110px">';
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
                    <i class="fa fa-home"></i>
                </div>

            </a>

            <div class="menu menu-3 text-left"></div>

        </div>

    </div>

</body>

</html>