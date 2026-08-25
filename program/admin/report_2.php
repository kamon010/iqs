<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="shortcut icon" type="image/png" href="../img/icon.png" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.css">
    <link rel="stylesheet" href="dist/css/skins/skin-blue-light.css">

    <link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/TableExport/4.0.11/css/tableexport.css" rel="stylesheet">

    <?php
        include("php/htmlCore.php");
        include_once("../php/applicant_options.php");
        include('indyEngine/engineControl.php');

        setInclude('htmlControl,dbControl,IDUControl,sessionControl');
        setPlugin('jquery,jquery-ui,alert,loaderbar');
        setPermission('login');

        HtmlCore::getTitle();

        $query = new DBControl();
    ?>

    <!-- HTML5 Shim and Respond.js IE8 support -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="hold-transition skin-blue-light" style="font-family: 'Kanit', sans-serif;">
<div class="wrapper">

    <header class="main-header">
        <?php HtmlCore::getTextHeader(); ?>

        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <?php HtmlCore::getMenuHeader(); ?>
        </nav>
    </header>

    <aside class="main-sidebar">
        <section class="sidebar">
            <?php HtmlCore::getMenu('report_2'); ?>
        </section>
    </aside>

    <div class="content-wrapper">

        <section class="content-header">
            <h1>
                รายงาน
                <small>TRC admin</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li class="active">ข้อมูลสมาชิก</li>
            </ol>
        </section>

        <section class="content" style="font-family: 'Kanit'">
            <div class="row">
                <div class="col-md-12">

                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title"><b>ข้อมูลสมาชิก</b></h3>

                            <div class="box-tools pull-right">
                                <a href="user_edit.php">
                                    <button class="btn btn-warning"><i class="fa fa-plus"></i> เพิ่มข้อมูล</button>
                                </a>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">

                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped" id="db_table">
                                            <thead>
                                            <tr>
                                                <th class="text-center">ลำดับ</th>
                                                <th class="text-center">ประเภทผู้สมัคร</th>
                                                <th class="text-center">ชื่อ-นามสกุล</th>
                                                <th class="text-center">เพศ</th>
                                                <th class="text-center">อายุ</th>
                                                <th class="text-center">เมื่อ</th>

                                                <th class="text-center">โดยปกติคุณสูบบุหรี่กี่มวนต่อวัน</th>
                                                <th class="text-center">หลังตื่นนอนตอนเช้าคุณสูบบุหรี่มวนแรกเมื่อไหร่</th>
                                                <th class="text-center">คุณสูบบุหรี่จัดในชั่วโมงแรกหลังตื่นนอน</th>
                                                <th class="text-center">บุหรี่มวนไหนที่คุณไม่อยากเลิกมากที่สุด</th>
                                                <th class="text-center">คุณรู้สึกลำบากหรือยุ่งยากไหมที่ ต้องอยู่ในที่ปลอดบุหรี่ เช่น โรงภาพยนตร์ รถโดยสาร ร้านอาหาร</th>
                                                <th class="text-center">คุณยังต้องสูบบุหรี่ แม้จะเจ็บป่วยนอนพักตลอดในโรงพยาบาล</th>
                                                <th class="text-center">คะแนนรวม</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            <?php
                                                $query = new DBControl();

                                                $admin_user_id = intval($_COOKIE["user"] ?? 0);

                                                /*
                                                    หน้านี้คือรายงาน "บุหรี่มวนอย่างเดียว"
                                                    ดังนั้นให้ยึดข้อมูลจากตาราง test_a เป็นหลัก

                                                    เงื่อนไขสำคัญ:
                                                    1) ตรวจจาก "วันที่ล่าสุด" ของแต่ละ user
                                                    2) ข้อมูลที่แสดงต้องเป็นข้อมูลล่าสุดที่อยู่ใน test_a เท่านั้น
                                                    3) ถ้าวันล่าสุดเดียวกันพบข้อมูลในตารางอื่น เช่น test_e จะไม่แสดง user คนนั้น
                                                    4) ถ้า user มี test_e วันที่ใหม่กว่า test_a ก็ไม่แสดง เพราะแปลว่ารอบล่าสุดไม่ใช่ test_a
                                                */

                                                $other_test_tables = array('test_e');

                                                if ($admin_user_id <= 0) {
                                                    echo '<tr><td colspan="13" class="text-center text-danger">ไม่พบข้อมูลผู้ใช้งาน กรุณาเข้าสู่ระบบใหม่</td></tr>';
                                                    $data = array();
                                                } else {
                                                    $admin_university_id = intval($query->queryDB_to_string(
                                                        "SELECT university_id FROM user WHERE user_id = ".$admin_user_id
                                                    ));

                                                    $university_condition = '';

                                                    $university_condition = getApplicantAdminScopeCondition($admin_university_id, 'user.university_id');

                                                    /*
                                                        ดึง test_a ล่าสุดของแต่ละ user ก่อน
                                                        แล้วค่อยเช็กภายหลังว่า วันที่ล่าสุดนี้มีใน test_e ด้วยไหม
                                                    */
                                                    $sql = "
                                                        SELECT 
                                                            test_a.*,
                                                            user.*,
                                                            test_a.id AS test_a_id,
                                                            test_a.date AS test_a_date
                                                        FROM `test_a`
                                                        INNER JOIN (
                                                            SELECT t1.user_id, MAX(t1.id) AS latest_test_a_id
                                                            FROM `test_a` t1
                                                            INNER JOIN (
                                                                SELECT user_id, MAX(`date`) AS latest_date
                                                                FROM `test_a`
                                                                GROUP BY user_id
                                                            ) t2 
                                                                ON t2.user_id = t1.user_id 
                                                                AND t2.latest_date = t1.`date`
                                                            GROUP BY t1.user_id
                                                        ) latest_a
                                                            ON latest_a.latest_test_a_id = test_a.id
                                                        INNER JOIN `user`
                                                            ON user.user_id = test_a.user_id
                                                        WHERE user.user_type != 'admin'
                                                        ".$university_condition."
                                                        ORDER BY test_a.date DESC, test_a.id DESC
                                                    ";

                                                    $data = $query->queryDB_to_array($sql);
                                                }

                                                $i = 1;

                                                foreach ($data as $key => $val) {
                                                    $row_user_id = intval($val['user_id'] ?? 0);

                                                    if ($row_user_id <= 0) {
                                                        continue;
                                                    }

                                                    $test_a_date = $val['test_a_date'] ?? ($val['date'] ?? '');

                                                    if ($test_a_date == '') {
                                                        continue;
                                                    }

                                                    /*
                                                        ตรวจวันที่ล่าสุดจริงจากตาราง test_a และตารางอื่นที่เกี่ยวข้อง
                                                        ถ้าวันล่าสุดไม่ได้อยู่ที่ test_a แถวนี้ ให้ข้าม
                                                    */
                                                    $latest_date = reportLatestDateAcrossTables(
                                                        $query,
                                                        $row_user_id,
                                                        array_merge(array('test_a'), $other_test_tables)
                                                    );

                                                    if ($latest_date == '' || $latest_date != $test_a_date) {
                                                        continue;
                                                    }

                                                    /*
                                                        ถ้าวันเดียวกันมีข้อมูลในตารางอื่น เช่น test_e
                                                        แปลว่า user ไม่ได้เป็นกลุ่ม "บุหรี่มวนอย่างเดียว" ในวันล่าสุด
                                                        จึงไม่แสดงในหน้านี้
                                                    */
                                                    if (reportHasSameDateInOtherTables($query, $row_user_id, $test_a_date, $other_test_tables)) {
                                                        continue;
                                                    }

                                                    /*
                                                        แสดงเฉพาะ user ที่ตอบ test_a ครบจริง ๆ
                                                        หมายเหตุ: ค่า 0 ในบางช่องถือว่าเป็นคำตอบ จึงห้ามใช้ empty()
                                                    */
                                                    $a_required = array('q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'score');

                                                    if (!reportIsComplete($val, $a_required)) {
                                                        continue;
                                                    }

                                                    $applicant = getApplicantEducationLabel($val['applicant_type'] ?? 'university', $val['applicant_level'] ?? '');

                                                    echo '
                                                        <tr>
                                                            <td class="text-center">'.$i.'</td>
                                                            <td class="text-center">'.reportText($applicant).'</td>
                                                            <td class="text-left">'.reportText(($val['user_name'] ?? '').' '.($val['user_lastname'] ?? '')).'</td>
                                                            <td class="text-center">'.reportText($val['user_gender'] ?? '').'</td>
                                                            <td class="text-center">'.getAge($val['user_birthday'] ?? '').'</td>
                                                            <td class="text-center">'.getTextDateTH($test_a_date).'</td>

                                                            <td class="text-center">'.getAws(1, reportValue($val, 'q1')).'</td>
                                                            <td class="text-center">'.getAws(2, reportValue($val, 'q2')).'</td>
                                                            <td class="text-center">'.getAws(3, reportValue($val, 'q3')).'</td>
                                                            <td class="text-center">'.getAws(4, reportValue($val, 'q4')).'</td>
                                                            <td class="text-center">'.getAws(5, reportValue($val, 'q5')).'</td>
                                                            <td class="text-center">'.getAws(6, reportValue($val, 'q6')).'</td>
                                                            <td class="text-center">'.reportScore($val, 'score').'</td>
                                                        </tr>
                                                    ';

                                                    $i++;
                                                }

                                                if ($i == 1) {
                                                    echo '<tr><td colspan="13" class="text-center text-muted">ไม่พบข้อมูลผู้ใช้ที่ตอบครบทุกข้อ</td></tr>';
                                                }

                                                function reportLatestDateAcrossTables($query, $user_id, $tables)
                                                {
                                                    $latest_date = '';

                                                    foreach ($tables as $table) {
                                                        $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);

                                                        if ($table == '') {
                                                            continue;
                                                        }

                                                        if (!reportTableExists($query, $table)) {
                                                            continue;
                                                        }

                                                        $date = $query->queryDB_to_string(
                                                            'SELECT `date`
                                                             FROM `'.$table.'`
                                                             WHERE `user_id` = '.intval($user_id).'
                                                             ORDER BY `date` DESC, `id` DESC
                                                             LIMIT 1'
                                                        );

                                                        if ($date != '' && ($latest_date == '' || $date > $latest_date)) {
                                                            $latest_date = $date;
                                                        }
                                                    }

                                                    return $latest_date;
                                                }

                                                function reportHasSameDateInOtherTables($query, $user_id, $date, $tables)
                                                {
                                                    foreach ($tables as $table) {
                                                        $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);

                                                        if ($table == '') {
                                                            continue;
                                                        }

                                                        if (!reportTableExists($query, $table)) {
                                                            continue;
                                                        }

                                                        $count = intval($query->queryDB_to_string(
                                                            'SELECT COUNT(*)
                                                             FROM `'.$table.'`
                                                             WHERE `user_id` = '.intval($user_id).'
                                                             AND `date` = "'.addslashes($date).'"'
                                                        ));

                                                        if ($count > 0) {
                                                            return true;
                                                        }
                                                    }

                                                    return false;
                                                }

                                                function reportTableExists($query, $table)
                                                {
                                                    $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);

                                                    if ($table == '') {
                                                        return false;
                                                    }

                                                    $count = intval($query->queryDB_to_string(
                                                        'SELECT COUNT(*)
                                                         FROM information_schema.tables
                                                         WHERE table_schema = DATABASE()
                                                         AND table_name = "'.addslashes($table).'"'
                                                    ));

                                                    return $count > 0;
                                                }

                                                function reportIsComplete($array, $fields)
                                                {
                                                    if (!is_array($array) || empty($array)) {
                                                        return false;
                                                    }

                                                    foreach ($fields as $field) {
                                                        if (!array_key_exists($field, $array)) {
                                                            return false;
                                                        }

                                                        if ($array[$field] === null || $array[$field] === '') {
                                                            return false;
                                                        }
                                                    }

                                                    return true;
                                                }

                                                function reportValue($array, $key)
                                                {
                                                    if (is_array($array) && array_key_exists($key, $array) && $array[$key] !== null && $array[$key] !== '') {
                                                        return $array[$key];
                                                    }

                                                    return '';
                                                }

                                                function reportScore($array, $key)
                                                {
                                                    $value = reportValue($array, $key);
                                                    return ($value === '') ? '-' : htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                                                }

                                                function reportText($value)
                                                {
                                                    if ($value === null || $value === '') {
                                                        return '-';
                                                    }

                                                    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                                                }

                                                function getTest($n)
                                                {
                                                    if ($n == 1) {
                                                        return 'สูบบุหรี่มวนสลับกับบุหรี่ไฟฟ้า';
                                                    } else if ($n == 2) {
                                                        return 'สูบบุหรี่มวนอย่างเดียว';
                                                    } else if ($n == 3) {
                                                        return 'สูบบุหรี่ไฟฟ้าอย่างเดียว';
                                                    } else if ($n == 4) {
                                                        return 'ไม่สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า';
                                                    }

                                                    return '-';
                                                }

                                                function getAws($n, $q)
                                                {
                                                    if ($q === '' || $q === null) {
                                                        return '-';
                                                    }

                                                    $q = intval($q);

                                                    if ($n == 1) {
                                                        if ($q == 1) {
                                                            return '10 มวน หรือน้อยกว่า';
                                                        } else if ($q == 2) {
                                                            return '11-20 มวน';
                                                        } else if ($q == 3) {
                                                            return '21-30 มวน';
                                                        } else if ($q == 4) {
                                                            return 'มากกว่า 30 มวน';
                                                        }
                                                    } else if ($n == 2) {
                                                        if ($q == 1) {
                                                            return 'ภายใน 5 นาทีหลังตื่นนอน';
                                                        } else if ($q == 2) {
                                                            return '6-30 นาที หลังตื่นนอน';
                                                        } else if ($q == 3) {
                                                            return '31-60 นาที หลังตื่นนอน';
                                                        } else if ($q == 4) {
                                                            return 'มากกว่า 60 นาทีหลังตื่น';
                                                        }
                                                    } else if ($n == 3) {
                                                        if ($q == 1) {
                                                            return 'ใช่';
                                                        } else if ($q == 2) {
                                                            return 'ไม่ใช่';
                                                        }
                                                    } else if ($n == 4) {
                                                        if ($q == 1) {
                                                            return 'มวนแรกตอนเช้า';
                                                        } else if ($q == 2) {
                                                            return 'มวนอื่น ๆ';
                                                        }
                                                    } else if ($n == 5) {
                                                        if ($q == 1) {
                                                            return 'รู้สึกลำบาก';
                                                        } else if ($q == 2) {
                                                            return 'ไม่รู้สึกลำบาก';
                                                        }
                                                    } else if ($n == 6) {
                                                        if ($q == 1) {
                                                            return 'ใช่';
                                                        } else if ($q == 2) {
                                                            return 'ไม่ใช่';
                                                        }
                                                    }

                                                    return '-';
                                                }

                                                function getAge($birthDate)
                                                {
                                                    if (empty($birthDate) || $birthDate == '0000-00-00') {
                                                        return "";
                                                    }

                                                    try {
                                                        $dob = new DateTime($birthDate);
                                                        $now = new DateTime();

                                                        return $now->diff($dob)->y;
                                                    } catch (Exception $e) {
                                                        return "";
                                                    }
                                                }
                                            ?>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>

                            <div class="box-footer text-center"></div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <?php HtmlCore::getMenuFooter(); ?>
    </footer>

    <!-- Modal -->
    <div class="modal fade" id="report1" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="margin-top:20px"><i class="fa fa-times"></i></span>
                    </button>
                    <h4 class="modal-title"><b>ระดับการติดสารนิโคติน</b></h4>
                </div>
                <div class="modal-body">
                    <div id="table_report1"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fas fa-undo-alt"></i> ย้อนกลับ</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="report2" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="margin-top:20px"><i class="fa fa-times"></i></span>
                    </button>
                    <h4 class="modal-title"><b>โอกาสเสี่ยงที่จะสูบบุหรี่แบบมวนและไฟฟ้า</b></h4>
                </div>
                <div class="modal-body">
                    <div id="table_report2"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fas fa-undo-alt"></i> ย้อนกลับ</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="report3" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="margin-top:20px"><i class="fa fa-times"></i></span>
                    </button>
                    <h4 class="modal-title"><b>เลิกบุหรี่</b></h4>
                </div>
                <div class="modal-body">
                    <div id="table_report3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fas fa-undo-alt"></i> ย้อนกลับ</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="report4" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="margin-top:20px"><i class="fa fa-times"></i></span>
                    </button>
                    <h4 class="modal-title"><b>ประเมินคัดกรอง</b></h4>
                </div>
                <div class="modal-body">
                    <div id="table_report4"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fas fa-undo-alt"></i> ย้อนกลับ</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="dist/js/app.min.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/heatmap.js"></script>

<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/TableExport/4.0.11/css/tableexport.css" rel="stylesheet">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.11.10/xlsx.core.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/blob-polyfill/1.0.20150320/Blob.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.3/FileSaver.min.js"></script>
<script type="text/javascript" src="js/tableexport.js"></script>

<script>
    $(function () {
        getTableExport();
        dbTable();
    });

    function getReport1(user_id) {
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "php/get_report1.php",
            data: {
                user_id: user_id
            },
            beforeSend: function () {
                $('#report1').appendTo("body").modal('show');
                $("#report1").css("z-index", "9999");
            },
            success: function (result) {
                $("#table_report1").html(result);
            }
        });
    }

    function getReport2(user_id) {
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "php/get_report2.php",
            data: {
                user_id: user_id
            },
            beforeSend: function () {
                $('#report2').appendTo("body").modal('show');
                $("#report2").css("z-index", "9999");
            },
            success: function (result) {
                $("#table_report2").html(result);
            }
        });
    }

    function getReport3(user_id) {
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "php/get_report3.php",
            data: {
                user_id: user_id
            },
            beforeSend: function () {
                $('#report3').appendTo("body").modal('show');
                $("#report3").css("z-index", "9999");
            },
            success: function (result) {
                $("#table_report3").html(result);
            }
        });
    }

    function getReport4(user_id) {
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "php/get_report4.php",
            data: {
                user_id: user_id
            },
            beforeSend: function () {
                $('#report4').appendTo("body").modal('show');
                $("#report4").css("z-index", "9999");
            },
            success: function (result) {
                $("#table_report4").html(result);
            }
        });
    }

    function dbTable() {
        $("#db_table").dataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": true,
            "bInfo": false,
            "bAutoWidth": true,
            "pageLength": 50,
            "lengthMenu": [5, 10, 20, 50, 100, 200, 500],
            "language": {
                "emptyTable": "ไม่พบข้อมูล",
                "search": "ค้นหา :"
            }
        });
    }

    function deleteRecord(table, id) {
        bootbox.confirm("Are you sure?", function (result) {
            if (result) {
                deleteDB(table, id, 'user_data.php');
            }
        });
    }

    function getTableExport() {
        TableExport(document.getElementsByTagName('table'), {
            headers: true,
            footers: true,
            formats: ['xlsx'],
            filename: 'ข้อมูลสมาชิก <?php echo date('d-m-Y'); ?>',
            bootstrap: true,
            exportButtons: true,
            position: 'bottom',
            ignoreRows: null,
            ignoreCols: null,
            trimWhitespace: true
        });
    }
</script>

</body>
</html>
