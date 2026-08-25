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

        function h($text) {
            return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
        }

        function getSafeTextDateTH($date) {
            if (empty($date) || $date == '0000-00-00') {
                return '-';
            }
            if (function_exists('getTextDateTH')) {
                return getTextDateTH($date);
            }
            return $date;
        }
    ?>

    <style>
        body {
            font-family: 'Kanit', sans-serif;
            font-size: 15px;
            color: #263238;
        }

        .content-wrapper {
            background: #f4f8fb;
        }

        .content-header h1 {
            font-size: 25px;
            font-weight: 700;
            color: #263238;
        }

        .box.box-primary {
            border-top: 4px solid #2f8dbd;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(38, 50, 56, 0.08);
            overflow: hidden;
        }

        .box-header {
            padding: 18px 20px;
            border-bottom: 1px solid #e8eef3;
            background: #ffffff;
        }

        .box-title {
            font-size: 20px !important;
            font-weight: 700;
            color: #17475f;
        }

        .box-body {
            padding: 18px 20px 22px 20px;
            background: #ffffff;
        }

        #db_table {
            font-size: 15.5px;
            border-collapse: separate;
            border-spacing: 0 6px;
        }

        #db_table thead th {
            font-size: 15.5px;
            font-weight: 700;
            color: #17475f;
            background: #eef7fb;
            border-bottom: 0 !important;
            padding: 13px 12px;
            vertical-align: middle !important;
            white-space: nowrap;
        }

        #db_table tbody tr {
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(38, 50, 56, 0.04);
        }

        #db_table tbody td {
            font-size: 15.5px;
            padding: 13px 12px;
            vertical-align: middle !important;
            border-top: 1px solid #edf2f6;
        }

        #db_table tbody tr:hover td {
            background: #f7fbfd;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_paginate {
            font-size: 15px;
        }

        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            border-radius: 8px;
            border: 1px solid #d6e2ea;
            padding: 6px 10px;
        }

        .btn-chart-toggle {
            min-width: 88px;
            height: 34px;
            border-radius: 18px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 3px 8px rgba(47, 141, 189, 0.25);
        }

        .btn-chart-toggle i {
            margin-right: 5px;
        }

        .chart-detail-wrapper {
            padding: 22px;
            background: linear-gradient(180deg, #ffffff 0%, #f7fbfd 100%);
            border: 1px solid #dceaf1;
            border-radius: 16px;
            box-shadow: 0 10px 28px rgba(38, 50, 56, 0.10);
            margin: 8px 0 14px 0;
        }

        .chart-title-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e6eff4;
        }

        .chart-title-card h4 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: #17475f;
        }

        .chart-title-card small {
            display: block;
            margin-top: 4px;
            font-size: 14px;
            color: #78909c;
        }

        .chart-summary-pill {
            background: #e8f5fb;
            color: #12627f;
            border-radius: 999px;
            padding: 8px 14px;
            font-size: 14px;
            font-weight: 700;
            white-space: nowrap;
        }

        .chart-box {
            width: 100%;
            height: 430px;
            margin-bottom: 18px;
            background: #ffffff;
            border: 1px solid #edf3f6;
            border-radius: 14px;
            padding: 10px;
        }

        .detail-mini-table {
            margin-bottom: 0;
            font-size: 15px;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
        }

        .detail-mini-table th {
            text-align: center;
            vertical-align: middle !important;
            background: #eef7fb;
            color: #17475f;
            font-size: 15px;
            font-weight: 700;
            padding: 12px !important;
        }

        .detail-mini-table td {
            text-align: center;
            vertical-align: middle !important;
            font-size: 15px;
            padding: 11px !important;
        }

        .te-export-button {
            font-size: 15px !important;
            border-radius: 8px !important;
            padding: 8px 12px !important;
        }
    </style>

    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="hold-transition skin-blue-light">
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
            <?php HtmlCore::getMenu('report_e'); ?>
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

        <section class="content">
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
                                                    <th class="text-center">เมื่อ</th>
                                                    <th class="text-center">วันที่ล่าสุด</th>
                                                    <th class="text-center">จำนวนมวนล่าสุด</th>
                                                    <th class="text-center">กราฟ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $chartDataMap = array();

                                                    $admin_user_id = isset($_COOKIE["user"]) ? intval($_COOKIE["user"]) : 0;
                                                    $university_id = intval($query->queryDB_to_string("SELECT university_id FROM user WHERE user_id = ".$admin_user_id));

                                                    $scope_condition = getApplicantAdminScopeCondition($university_id, 'university_id');
                                                    $sql = "SELECT * FROM user WHERE user_type!='admin' ".$scope_condition." ORDER BY user_id";

                                                    $data = $query->queryDB_to_array($sql);
                                                    $i = 1;

                                                    foreach ($data as $key => $val) {
                                                        $member_user_id = intval($val['user_id']);

                                                        /*
                                                         * ดึงข้อมูลการเลิกบุหรี่ของ user นี้ทั้งหมด
                                                         * ถ้าวันที่เลิกบุหรี่ซ้ำกัน ให้เก็บเฉพาะรายการล่าสุดของวันนั้น
                                                         * ใช้ date DESC และ id DESC เพื่อเลือกข้อมูลล่าสุด
                                                         */
                                                        $tests = $query->queryDB_to_array(
                                                            "SELECT * FROM `day` 
                                                             WHERE user_id = ".$member_user_id." 
                                                             ORDER BY CAST(`day` AS UNSIGNED) ASC, `date` DESC, `id` DESC"
                                                        );

                                                        if (empty($tests) || !is_array($tests)) {
                                                            continue;
                                                        }

                                                        $dayMap = array();

                                                        foreach ($tests as $v) {
                                                            if (!isset($v['day']) || $v['day'] === '') {
                                                                continue;
                                                            }

                                                            $dayNo = intval($v['day']);

                                                            if ($dayNo <= 0) {
                                                                continue;
                                                            }

                                                            /* เพราะ query เรียง date DESC, id DESC แล้ว ถ้ามี day เดิม ให้ใช้ตัวแรกเท่านั้น */
                                                            if (!isset($dayMap[$dayNo])) {
                                                                $dayMap[$dayNo] = $v;
                                                            }
                                                        }

                                                        if (empty($dayMap)) {
                                                            continue;
                                                        }

                                                        ksort($dayMap, SORT_NUMERIC);

                                                        $chartRows = array();
                                                        $latestRecord = null;

                                                        foreach ($dayMap as $dayNo => $v) {
                                                            $chartRows[] = array(
                                                                'day' => 'วันที่ '.$dayNo,
                                                                'dayNo' => $dayNo,
                                                                'n' => intval($v['n']),
                                                                'date' => getSafeTextDateTH($v['date'])
                                                            );

                                                            if ($latestRecord === null) {
                                                                $latestRecord = $v;
                                                            } else {
                                                                $latestDate = isset($latestRecord['date']) ? $latestRecord['date'] : '';
                                                                $currentDate = isset($v['date']) ? $v['date'] : '';
                                                                $latestId = isset($latestRecord['id']) ? intval($latestRecord['id']) : 0;
                                                                $currentId = isset($v['id']) ? intval($v['id']) : 0;

                                                                if ($currentDate > $latestDate || ($currentDate == $latestDate && $currentId > $latestId)) {
                                                                    $latestRecord = $v;
                                                                }
                                                            }
                                                        }

                                                        if ($latestRecord === null) {
                                                            continue;
                                                        }

                                                        $chartDataMap[$member_user_id] = $chartRows;

                                                        $applicant = getApplicantEducationLabel($val['applicant_type'] ?? 'university', $val['applicant_level'] ?? '');
                                                        $latestDateText = getSafeTextDateTH($latestRecord['date']);
                                                        $latestDayText = 'วันที่ '.intval($latestRecord['day']);
                                                        $latestNText = intval($latestRecord['n']).' มวน';

                                                        echo '
                                                            <tr class="text-center">
                                                                <td class="text-center">'.$i.'</td>
                                                                <td>'.h($applicant).'</td>
                                                                <td class="text-left">'.h($val['user_name'].' '.$val['user_lastname']).'</td>
                                                                <td class="text-center">'.$latestDateText.'</td>
                                                                <td class="text-center">'.$latestDayText.'</td>
                                                                <td class="text-center">'.$latestNText.'</td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-primary btn-chart-toggle" data-user-id="'.$member_user_id.'">
                                                                        <i class="fa fa-chevron-right"></i><span>ดูกราฟ</span>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        ';

                                                        $i++;
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer text-center"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <?php HtmlCore::getMenuFooter(); ?>
    </footer>
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
    var chartDataMap = <?php echo json_encode($chartDataMap, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK); ?>;
    var dbTableObj = null;

    $(function () {
        dbTable();
        getTableExport();
    });

    function dbTable() {
        dbTableObj = $("#db_table").DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": false,
            "bInfo": false,
            "bAutoWidth": true,
            "pageLength": 50,
            "lengthMenu": [5, 10, 20, 50, 100, 200, 500],
            "language": {
                "emptyTable": "ไม่พบข้อมูล",
                "search": "ค้นหา :",
                "lengthMenu": "Show _MENU_ entries"
            }
        });
    }

    $(document).on("click", ".btn-chart-toggle", function (event) {
        event.preventDefault();

        var button = $(this);
        var userId = button.data("user-id").toString();
        var tr = button.closest("tr");
        var row = dbTableObj.row(tr);
        var icon = button.find("i");

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass("shown");
            icon.removeClass("fa-chevron-down").addClass("fa-chevron-right");
            button.find("span").text("ดูกราฟ");
            return;
        }

        row.child(buildChartDetail(userId)).show();
        tr.addClass("shown");
        icon.removeClass("fa-chevron-right").addClass("fa-chevron-down");
        button.find("span").text("ซ่อน");

        renderUserChart(userId);
    });

    function buildChartDetail(userId) {
        var rows = chartDataMap[userId] || [];
        var chartId = "chart_user_" + userId;
        var html = '';

        html += '<div class="chart-detail-wrapper">';
        html += '<div class="chart-title-card">';
        html += '<div><h4><i class="fa fa-line-chart"></i> กราฟจำนวนบุหรี่รายวัน</h4><small>แสดงข้อมูลล่าสุดของแต่ละวัน หากมีข้อมูลซ้ำในวันเดียวกัน</small></div>';
        html += '<div class="chart-summary-pill">ทั้งหมด ' + rows.length + ' วัน</div>';
        html += '</div>';
        html += '<div id="' + chartId + '" class="chart-box"></div>';
        html += '<table class="table table-bordered detail-mini-table">';
        html += '<thead><tr><th>วันที่เลิกบุหรี่</th><th>วันที่บันทึก</th><th>จำนวนมวน</th></tr></thead><tbody>';

        if (rows.length === 0) {
            html += '<tr><td colspan="3">ไม่พบข้อมูล</td></tr>';
        } else {
            rows.forEach(function (item) {
                html += '<tr>';
                html += '<td>' + escapeHtml(item.day) + '</td>';
                html += '<td>' + escapeHtml(item.date) + '</td>';
                html += '<td>' + Number(item.n) + ' มวน</td>';
                html += '</tr>';
            });
        }

        html += '</tbody></table>';
        html += '</div>';

        return html;
    }

    function renderUserChart(userId) {
        var rows = chartDataMap[userId] || [];
        var chartId = "chart_user_" + userId;
        var categories = rows.map(function (item) { return item.day; });
        var values = rows.map(function (item) { return Number(item.n); });

        Highcharts.chart(chartId, {
            chart: {
                type: 'line',
                backgroundColor: 'transparent',
                spacingTop: 18,
                spacingRight: 22,
                spacingBottom: 18,
                spacingLeft: 16,
                style: {
                    fontFamily: 'Kanit, sans-serif'
                }
            },
            title: {
                text: 'จำนวนบุหรี่ที่สูบในแต่ละวัน',
                style: {
                    color: '#17475f',
                    fontSize: '22px',
                    fontWeight: '700'
                }
            },
            subtitle: {
                text: 'หากวันเดียวกันมีข้อมูลซ้ำ ระบบจะแสดงเฉพาะข้อมูลล่าสุด',
                style: {
                    color: '#78909c',
                    fontSize: '14px'
                }
            },
            xAxis: {
                categories: categories,
                lineColor: '#cfdde6',
                tickColor: '#cfdde6',
                labels: {
                    style: {
                        color: '#546e7a',
                        fontSize: '14px',
                        fontWeight: '600'
                    }
                },
                title: {
                    text: 'วันที่เลิกบุหรี่',
                    style: {
                        color: '#17475f',
                        fontSize: '15px',
                        fontWeight: '700'
                    }
                }
            },
            yAxis: {
                title: {
                    text: 'จำนวนมวน',
                    style: {
                        color: '#17475f',
                        fontSize: '15px',
                        fontWeight: '700'
                    }
                },
                labels: {
                    style: {
                        color: '#546e7a',
                        fontSize: '14px',
                        fontWeight: '600'
                    }
                },
                gridLineColor: '#edf3f6',
                min: 0,
                allowDecimals: false
            },
            tooltip: {
                useHTML: true,
                borderRadius: 10,
                padding: 12,
                style: {
                    fontSize: '14px',
                    fontFamily: 'Kanit, sans-serif'
                },
                formatter: function () {
                    var item = rows[this.point.index] || {};
                    return '<div style="font-size:14px; line-height:1.7; min-width:150px;">' +
                        '<b style="font-size:15px; color:#17475f;">' + this.x + '</b><br>' +
                        'วันที่บันทึก: ' + escapeHtml(item.date || '-') + '<br>' +
                        'จำนวน: <b style="color:#2f8dbd;">' + this.y + ' มวน</b>' +
                    '</div>';
                }
            },
            plotOptions: {
                series: {
                    lineWidth: 4,
                    color: '#2f8dbd',
                    marker: {
                        enabled: true,
                        radius: 5,
                        lineWidth: 2,
                        lineColor: '#ffffff',
                        fillColor: '#2f8dbd'
                    },
                    states: {
                        hover: {
                            lineWidthPlus: 1
                        }
                    }
                }
            },
            legend: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'จำนวนมวน',
                data: values
            }]
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
            ignoreCols: [6],
            trimWhitespace: true
        });
    }

    function escapeHtml(text) {
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
</script>
</body>
</html>
