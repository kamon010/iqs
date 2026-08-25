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
            <?php HtmlCore::getMenu('report_4'); ?>
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

                                                <th class="text-center">ถ้าเพื่อนสนิทของคุณเสนอบุหรี่ให้คุณ คุณจะสูบบุหรี่ไหม</th>
                                                <th class="text-center">ใน 12 เดือนข้างหน้า คุณคิดว่าคุณจะสูบบุหรี่หรือไม่</th>
                                                <th class="text-center">ถ้าเพื่อนสนิทของคุณเสนอบุหรี่ไฟฟ้าให้คุณ คุณจะสูบบุหรี่ไฟฟ้าไหม</th>
                                                <th class="text-center">ใน 12 เดือนข้างหน้า คุณคิดว่าคุณจะสูบบุหรี่ไฟฟ้าหรือไม่</th>
                                                <th class="text-center">คุณมีความสนใจหรืออยากรู้อยากเห็นเกี่ยวกับบุหรี่ไฟฟ้าหรือไม่</th>
                                                <th class="text-center">โอกาสเสี่ยง<br>ที่จะสูบแบบมวน</th>
                                                <th class="text-center">โอกาสเสี่ยง<br>ที่จะสูบบุหรี่ไฟฟ้า</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            <?php
                                                $query = new DBControl();

                                                $admin_user_id = intval($_COOKIE["user"] ?? 0);

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
                                                        หน้านี้เป็นรายงานของ test_b เท่านั้น
                                                        - ดึง test_b ล่าสุดของแต่ละ user
                                                        - ถ้าวันเดียวกันมี test_a หรือ test_e จะไม่แสดง
                                                        - ถ้ามี test_a หรือ test_e วันที่ใหม่กว่า test_b จะไม่แสดง
                                                        - แสดงเฉพาะ record ที่ตอบครบ q1-q5
                                                    */
                                                    $sql = "
                                                        SELECT tb.*, user.*
                                                        FROM `test_b` tb
                                                        INNER JOIN (
                                                            SELECT user_id, MAX(id) AS latest_test_b_id
                                                            FROM `test_b`
                                                            GROUP BY user_id
                                                        ) latest_tb
                                                            ON latest_tb.latest_test_b_id = tb.id
                                                        INNER JOIN `user`
                                                            ON user.user_id = tb.user_id
                                                        WHERE user.user_type != 'admin'
                                                        ".$university_condition."
                                                        AND NOT EXISTS (
                                                            SELECT 1
                                                            FROM `test_a` ta
                                                            WHERE ta.user_id = tb.user_id
                                                            AND ta.date >= tb.date
                                                            LIMIT 1
                                                        )
                                                        AND NOT EXISTS (
                                                            SELECT 1
                                                            FROM `test_e` te
                                                            WHERE te.user_id = tb.user_id
                                                            AND te.date >= tb.date
                                                            LIMIT 1
                                                        )
                                                        ORDER BY tb.date DESC, tb.id DESC
                                                    ";

                                                    $data = $query->queryDB_to_array($sql);
                                                }

                                                $i = 1;

                                                foreach ($data as $key => $val) {
                                                    $row_user_id = intval($val['user_id'] ?? 0);

                                                    if ($row_user_id <= 0) {
                                                        continue;
                                                    }

                                                    $required = array('q1', 'q2', 'q3', 'q4', 'q5');

                                                    if (!reportIsComplete($val, $required)) {
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
                                                            <td class="text-center">'.getTextDateTH($val['date'] ?? '').'</td>

                                                            <td class="text-center">'.getAws(1, reportValue($val, 'q1')).'</td>
                                                            <td class="text-center">'.getAws(2, reportValue($val, 'q2')).'</td>
                                                            <td class="text-center">'.getAws(3, reportValue($val, 'q3')).'</td>
                                                            <td class="text-center">'.getAws(4, reportValue($val, 'q4')).'</td>
                                                            <td class="text-center">'.getAws(5, reportValue($val, 'q5')).'</td>
                                                            <td class="text-center">'.getTest($val).'</td>
                                                            <td class="text-center">'.getTest2($val).'</td>
                                                        </tr>
                                                    ';

                                                    $i++;
                                                }

                                                if ($i == 1) {
                                                    echo '<tr><td colspan="13" class="text-center text-muted">ไม่พบข้อมูลผู้ใช้ที่ตอบครบทุกข้อ</td></tr>';
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

                                                function reportText($value)
                                                {
                                                    if ($value === null || $value === '') {
                                                        return '-';
                                                    }

                                                    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
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

                                                function getAws($n, $q)
                                                {
                                                    if ($q === '' || $q === null) {
                                                        return '-';
                                                    }

                                                    $q = intval($q);

                                                    if ($q == 1) {
                                                        return 'ใช่แน่นอน';
                                                    } else if ($q == 2) {
                                                        return 'อาจจะใช่';
                                                    } else if ($q == 3) {
                                                        return 'อาจจะไม่ใช่';
                                                    } else if ($q == 4) {
                                                        return 'ไม่ใช่แน่นอน';
                                                    }

                                                    return '-';
                                                }

                                                function getTest($data)
                                                {
                                                    $q1 = intval($data['q1'] ?? 0);
                                                    $q2 = intval($data['q2'] ?? 0);

                                                    if ($q1 == 4 && $q2 == 4) {
                                                        return 'ไม่มีโอกาสเสี่ยง<br>ที่จะสูบบุหรี่แบบมวนในอนาคต';
                                                    }

                                                    return 'มีโอกาสเสี่ยงที่<br>จะสูบบุหรี่แบบมวนในอนาคต';
                                                }

                                                function getTest2($data)
                                                {
                                                    $q3 = intval($data['q3'] ?? 0);
                                                    $q4 = intval($data['q4'] ?? 0);
                                                    $q5 = intval($data['q5'] ?? 0);

                                                    if ($q3 == 4 && $q4 == 4 && $q5 == 4) {
                                                        return 'ไม่มีโอกาสเสี่ยง<br>ที่จะสูบบุหรี่ไฟฟ้าในอนาคต';
                                                    }

                                                    return 'มีโอกาสเสี่ยงที่<br>จะสูบบุหรี่ไฟฟ้าในอนาคต';
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
