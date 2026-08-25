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
            <?php HtmlCore::getMenu('report_3'); ?>
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
                                    <button class="btn btn-warning">
                                        <i class="fa fa-plus"></i> เพิ่มข้อมูล
                                    </button>
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

                                                <th class="text-center">ใช้บุหรี่ไฟฟ้าหลังตื่นนอนภายในกี่นาที</th>
                                                <th class="text-center">โดยปกติใช้บุหรี่ไฟฟ้ากี่ครั้งต่อวัน</th>
                                                <th class="text-center">การใช้บุหรี่ไฟฟ้าในช่วงเช้าสำคัญกว่าช่วงอื่นหรือไม่</th>
                                                <th class="text-center">เคยพยายามหยุดใช้แล้วหงุดหงิดหรือวิตกกังวลหรือไม่</th>
                                                <th class="text-center">ใช้บุหรี่ไฟฟ้าขณะเจ็บป่วยหรือต้องพักรักษาตัวหรือไม่</th>
                                                <th class="text-center">ปริมาณนิโคตินในน้ำยาที่ใช้คือเท่าใด</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            <?php
                                                $query = new DBControl();

                                                $admin_user_id = intval($_COOKIE["user"] ?? 0);

                                                if ($admin_user_id <= 0) {
                                                    echo '<tr><td colspan="12" class="text-center text-danger">ไม่พบข้อมูลผู้ใช้งาน กรุณาเข้าสู่ระบบใหม่</td></tr>';
                                                    $data = array();
                                                } else {
                                                    $admin_university_id = intval($query->queryDB_to_string(
                                                        "SELECT university_id FROM user WHERE user_id = ".$admin_user_id
                                                    ));

                                                    $university_condition = '';

                                                    $university_condition = getApplicantAdminScopeCondition($admin_university_id, 'user.university_id');

                                                    /*
                                                        หน้า report_3 = สูบบุหรี่ไฟฟ้าอย่างเดียว

                                                        เงื่อนไขที่แก้ไขแล้ว:
                                                        1) รวมข้อมูล test_a และ test_e ก่อน
                                                        2) หา "วันที่ล่าสุด" ของแต่ละ user จากทั้ง 2 ตาราง
                                                        3) แสดงเฉพาะ user ที่วันที่ล่าสุดมีข้อมูลใน test_e
                                                        4) ถ้าวันล่าสุดมีข้อมูลใน test_a ด้วย จะไม่แสดงในหน้านี้
                                                           เพราะถือว่าเป็นกลุ่ม "สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า"
                                                        5) ถ้า user มี test_e เก่า แต่ล่าสุดมี test_a จะไม่แสดง
                                                        6) ถ้าวันเดียวกันมี test_e หลายแถว จะเลือก id ล่าสุด
                                                    */
                                                    $sql = "
                                                        SELECT e.*, user.*
                                                        FROM (
                                                            SELECT
                                                                all_test.user_id,
                                                                MAX(all_test.real_date) AS latest_date
                                                            FROM (
                                                                SELECT
                                                                    user_id,
                                                                    COALESCE(
                                                                        DATE(`date`),
                                                                        DATE(STR_TO_DATE(`date`, '%Y-%c-%e'))
                                                                    ) AS real_date
                                                                FROM `test_a`

                                                                UNION ALL

                                                                SELECT
                                                                    user_id,
                                                                    COALESCE(
                                                                        DATE(`date`),
                                                                        DATE(STR_TO_DATE(`date`, '%Y-%c-%e'))
                                                                    ) AS real_date
                                                                FROM `test_e`
                                                            ) AS all_test
                                                            WHERE all_test.real_date IS NOT NULL
                                                            GROUP BY all_test.user_id
                                                        ) AS latest_user

                                                        INNER JOIN `test_e` AS e
                                                            ON e.id = (
                                                                SELECT MAX(e2.id)
                                                                FROM `test_e` AS e2
                                                                WHERE e2.user_id = latest_user.user_id
                                                                AND COALESCE(
                                                                    DATE(e2.`date`),
                                                                    DATE(STR_TO_DATE(e2.`date`, '%Y-%c-%e'))
                                                                ) = latest_user.latest_date
                                                            )

                                                        INNER JOIN `user`
                                                            ON user.user_id = latest_user.user_id

                                                        WHERE user.user_type != 'admin'
                                                        ".$university_condition."

                                                        AND NOT EXISTS (
                                                            SELECT 1
                                                            FROM `test_a` AS a
                                                            WHERE a.user_id = latest_user.user_id
                                                            AND COALESCE(
                                                                DATE(a.`date`),
                                                                DATE(STR_TO_DATE(a.`date`, '%Y-%c-%e'))
                                                            ) = latest_user.latest_date
                                                        )

                                                        ORDER BY latest_user.latest_date DESC, e.id DESC
                                                    ";

                                                    $data = $query->queryDB_to_array($sql);
                                                }

                                                $i = 1;

                                                foreach ($data as $key => $row) {
                                                    $row_user_id = intval($row['user_id'] ?? 0);

                                                    if ($row_user_id <= 0) {
                                                        continue;
                                                    }

                                                    $e_required = array('q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'score');

                                                    if (!reportIsComplete($row, $e_required)) {
                                                        continue;
                                                    }

                                                    $applicant = getApplicantEducationLabel($row['applicant_type'] ?? 'university', $row['applicant_level'] ?? '');

                                                    echo '
                                                        <tr>
                                                            <td class="text-center">'.$i.'</td>
                                                            <td class="text-center">'.reportText($applicant).'</td>
                                                            <td class="text-left">'.reportText(($row['user_name'] ?? '').' '.($row['user_lastname'] ?? '')).'</td>
                                                            <td class="text-center">'.reportText($row['user_gender'] ?? '').'</td>
                                                            <td class="text-center">'.getAge($row['user_birthday'] ?? '').'</td>
                                                            <td class="text-center">'.getTextDateTH($row['date'] ?? '').'</td>

                                                            <td class="text-center">'.getAws2(1, reportValue($row, 'q1')).'</td>
                                                            <td class="text-center">'.getAws2(2, reportValue($row, 'q2')).'</td>
                                                            <td class="text-center">'.getAws2(3, reportValue($row, 'q3')).'</td>
                                                            <td class="text-center">'.getAws2(4, reportValue($row, 'q4')).'</td>
                                                            <td class="text-center">'.getAws2(5, reportValue($row, 'q5')).'</td>
                                                            <td class="text-center">'.getAws2(6, reportValue($row, 'q6')).'</td>
                                                        </tr>
                                                    ';

                                                    $i++;
                                                }

                                                if ($i == 1) {
                                                    echo '<tr><td colspan="12" class="text-center text-muted">ไม่พบข้อมูลผู้ใช้ที่ตอบครบทุกข้อ</td></tr>';
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

                                                function getAws2($n, $q)
                                                {
                                                    if ($q === '' || $q === null) {
                                                        return '-';
                                                    }

                                                    /*
                                                        test_e ใช้ค่าจริงตามแบบสอบถาม:
                                                        q1: 3,2,1,0
                                                        q2: 4,3,1,0
                                                        q3: 1,0
                                                        q4: 1,0
                                                        q5: 1,2
                                                        q6: 2,1,0

                                                        หมายเหตุ:
                                                        ค่า 0 เป็นคำตอบจริง ไม่ใช่ค่าว่าง
                                                    */
                                                    $q = intval($q);

                                                    if ($n == 1) {
                                                        if ($q == 3) {
                                                            return 'ภายใน 5 นาที';
                                                        } else if ($q == 2) {
                                                            return '6–30 นาที';
                                                        } else if ($q == 1) {
                                                            return '31–60 นาที';
                                                        } else if ($q == 0) {
                                                            return 'มากกว่า 60 นาที';
                                                        }
                                                    } else if ($n == 2) {
                                                        if ($q == 4) {
                                                            return 'มากกว่า 20';
                                                        } else if ($q == 3) {
                                                            return '11–20';
                                                        } else if ($q == 1) {
                                                            return '6–10';
                                                        } else if ($q == 0) {
                                                            return '1–5';
                                                        }
                                                    } else if ($n == 3) {
                                                        if ($q == 1) {
                                                            return 'ใช่';
                                                        } else if ($q == 0) {
                                                            return 'ไม่ใช่';
                                                        }
                                                    } else if ($n == 4) {
                                                        if ($q == 1) {
                                                            return 'ใช่';
                                                        } else if ($q == 0) {
                                                            return 'ไม่ใช่';
                                                        }
                                                    } else if ($n == 5) {
                                                        if ($q == 1) {
                                                            return 'ใช่';
                                                        } else if ($q == 2) {
                                                            return 'ไม่ใช่';
                                                        }
                                                    } else if ($n == 6) {
                                                        if ($q == 2) {
                                                            return '≥ 35 mg/ml';
                                                        } else if ($q == 1) {
                                                            return '20–34 mg/ml';
                                                        } else if ($q == 0) {
                                                            return '< 20 mg/ml';
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

    <div class="modal fade" id="report1" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
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
                    <button type="button" class="btn btn-primary" data-dismiss="modal">
                        <i class="fas fa-undo-alt"></i> ย้อนกลับ
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="report2" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
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
                    <button type="button" class="btn btn-primary" data-dismiss="modal">
                        <i class="fas fa-undo-alt"></i> ย้อนกลับ
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="report3" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
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
                    <button type="button" class="btn btn-primary" data-dismiss="modal">
                        <i class="fas fa-undo-alt"></i> ย้อนกลับ
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="report4" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
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
                    <button type="button" class="btn btn-primary" data-dismiss="modal">
                        <i class="fas fa-undo-alt"></i> ย้อนกลับ
                    </button>
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
            data: { user_id: user_id },
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
            data: { user_id: user_id },
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
            data: { user_id: user_id },
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
            data: { user_id: user_id },
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
