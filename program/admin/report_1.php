<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<link rel="shortcut icon" type="image/png" href="../img/icon.png" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.css">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link rel="stylesheet" href="dist/css/skins/skin-blue-light.css">

	<link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/TableExport/4.0.11/css/tableexport.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
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
  <!--
  BODY TAG OPTIONS:
  =================
  Apply one or more of the following classes to get the
  desired effect
  |---------------------------------------------------------|
  | SKINS         | skin-blue                               |
  |               | skin-black                              |
  |               | skin-purple                             |
  |               | skin-yellow                             |
  |               | skin-red                                |
  |               | skin-green                              |
  |---------------------------------------------------------|
  |LAYOUT OPTIONS | fixed                                   |
  |               | layout-boxed                            |
  |               | layout-top-nav                          |
  |               | sidebar-collapse                        |
  |               | sidebar-mini                            |
  |---------------------------------------------------------|
  -->
	<body class="hold-transition skin-blue-light" style="font-family: 'Kanit', sans-serif;">
		<div class="wrapper">
			<!-- Main Header -->
			<header class="main-header">
				<!-- Logo -->
				<?php
					HtmlCore::getTextHeader();
				?>

				<!-- Header Navbar -->
				<nav class="navbar navbar-static-top" role="navigation">
					<!-- Sidebar toggle button-->
					<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
						<span class="sr-only">Toggle navigation</span>
					</a>
					<!-- Navbar Right Menu -->
					<?php
						HtmlCore::getMenuHeader();
					?>
				</nav>
			</header>
			<!-- Left side column. contains the logo and sidebar -->
			<aside class="main-sidebar">

				<!-- sidebar: style can be found in sidebar.less -->
				<section class="sidebar">
				  <!-- Sidebar user panel (optional) -->

				  <!-- Sidebar Menu -->
					<?php
						HtmlCore::getMenu('report_1');
					?>
				  <!-- /.sidebar-menu -->
				</section>
				<!-- /.sidebar -->
			</aside>

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
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

				<!-- Main content -->
				<section class="content" style="font-family: 'Kanit'">
					<!-- Your Page Content Here -->
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
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped"  id="db_table">
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
                                                            echo '<tr><td colspan="19" class="text-center text-danger">ไม่พบข้อมูลผู้ใช้งาน กรุณาเข้าสู่ระบบใหม่</td></tr>';
                                                            $users = array();
                                                        } else {
                                                            $admin_university_id = intval($query->queryDB_to_string(
                                                                "SELECT university_id FROM user WHERE user_id = ".$admin_user_id
                                                            ));

                                                            /*
                                                                รายงานหน้านี้ต้องแสดงเฉพาะผู้ที่มีข้อมูลล่าสุดอยู่ครบทั้ง 2 ตาราง:
                                                                - test_a = แบบประเมินบุหรี่มวน
                                                                - test_e = แบบประเมินบุหรี่ไฟฟ้า

                                                                หลักการตรวจ:
                                                                1) หา date ล่าสุดของ test_a ของ user
                                                                2) หา date ล่าสุดของ test_e ของ user
                                                                3) ถ้าวันล่าสุดไม่ตรงกัน ให้ไม่แสดง user คนนั้น
                                                                   เช่น test_a ล่าสุด 2026-06-07 แต่ test_e ล่าสุด 2026-06-01
                                                                   แปลว่ารอบล่าสุดมีข้อมูลแค่ฝั่งบุหรี่มวน จึงไม่ควรเอา test_e เก่ามาแสดงปน
                                                                4) ถ้าวันล่าสุดตรงกัน จึงดึงแถวล่าสุดของวันนั้นมาแสดง
                                                            */

                                                            $university_condition = '';

                                                            $university_condition = getApplicantAdminScopeCondition($admin_university_id, 'university_id');

                                                            $users = $query->queryDB_to_array(
                                                                "SELECT *
                                                                 FROM `user`
                                                                 WHERE user_type != 'admin'
                                                                 ".$university_condition."
                                                                 ORDER BY user_id ASC"
                                                            );
                                                        }

                                                        $i = 1;

                                                        foreach ($users as $key => $val) {
                                                            $row_user_id = intval($val['user_id'] ?? 0);

                                                            if ($row_user_id <= 0) {
                                                                continue;
                                                            }

                                                            /*
                                                                หา date ล่าสุดของแต่ละตารางแยกกันก่อน
                                                                ใช้ ORDER BY date DESC, id DESC เพื่อให้ได้รอบล่าสุดจริง
                                                            */
                                                            $latest_a_date = $query->queryDB_to_string(
                                                                'SELECT `date`
                                                                 FROM `test_a`
                                                                 WHERE `user_id` = '.$row_user_id.'
                                                                 ORDER BY `date` DESC, `id` DESC
                                                                 LIMIT 1'
                                                            );

                                                            $latest_e_date = $query->queryDB_to_string(
                                                                'SELECT `date`
                                                                 FROM `test_e`
                                                                 WHERE `user_id` = '.$row_user_id.'
                                                                 ORDER BY `date` DESC, `id` DESC
                                                                 LIMIT 1'
                                                            );

                                                            /*
                                                                ถ้าไม่มีข้อมูลในตารางใดตารางหนึ่งเลย ไม่แสดง
                                                            */
                                                            if ($latest_a_date === '' || $latest_a_date === null || $latest_e_date === '' || $latest_e_date === null) {
                                                                continue;
                                                            }

                                                            /*
                                                                ถ้าวันล่าสุดของ test_a และ test_e ไม่ใช่วันเดียวกัน ไม่แสดง
                                                                เพราะหมายความว่ารอบล่าสุดมีข้อมูลอยู่แค่ตารางเดียว
                                                            */
                                                            if ($latest_a_date != $latest_e_date) {
                                                                continue;
                                                            }

                                                            $latest_date = addslashes($latest_a_date);

                                                            /*
                                                                ดึงข้อมูลแถวล่าสุดของวันที่ตรงกันจากทั้ง 2 ตาราง
                                                            */
                                                            $a = $query->queryDB_to_row(
                                                                'SELECT *
                                                                 FROM `test_a`
                                                                 WHERE `user_id` = '.$row_user_id.'
                                                                 AND `date` = "'.$latest_date.'"
                                                                 ORDER BY `id` DESC
                                                                 LIMIT 1'
                                                            );

                                                            $e = $query->queryDB_to_row(
                                                                'SELECT *
                                                                 FROM `test_e`
                                                                 WHERE `user_id` = '.$row_user_id.'
                                                                 AND `date` = "'.$latest_date.'"
                                                                 ORDER BY `id` DESC
                                                                 LIMIT 1'
                                                            );

                                                            if (!is_array($a)) {
                                                                $a = array();
                                                            }

                                                            if (!is_array($e)) {
                                                                $e = array();
                                                            }

                                                            /*
                                                                แสดงเฉพาะคนที่ตอบครบจริง
                                                                หมายเหตุ: ค่า 0 ใน test_e มีความหมาย จึงห้ามใช้ empty()
                                                            */
                                                            $a_required = array('q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'score');
                                                            $e_required = array('q1', 'q2', 'q3', 'q4', 'q5', 'q6');

                                                            if (!reportIsComplete($a, $a_required) || !reportIsComplete($e, $e_required)) {
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
                                                                    <td class="text-center">'.getTextDateTH($latest_a_date).'</td>

                                                                    <td class="text-center">'.getAws(1, reportValue($a, 'q1')).'</td>
                                                                    <td class="text-center">'.getAws(2, reportValue($a, 'q2')).'</td>
                                                                    <td class="text-center">'.getAws(3, reportValue($a, 'q3')).'</td>
                                                                    <td class="text-center">'.getAws(4, reportValue($a, 'q4')).'</td>
                                                                    <td class="text-center">'.getAws(5, reportValue($a, 'q5')).'</td>
                                                                    <td class="text-center">'.getAws(6, reportValue($a, 'q6')).'</td>
                                                                    <td class="text-center">'.reportScore($a, 'score').'</td>

                                                                    <td class="text-center">'.getAws2(1, reportValue($e, 'q1')).'</td>
                                                                    <td class="text-center">'.getAws2(2, reportValue($e, 'q2')).'</td>
                                                                    <td class="text-center">'.getAws2(3, reportValue($e, 'q3')).'</td>
                                                                    <td class="text-center">'.getAws2(4, reportValue($e, 'q4')).'</td>
                                                                    <td class="text-center">'.getAws2(5, reportValue($e, 'q5')).'</td>
                                                                    <td class="text-center">'.getAws2(6, reportValue($e, 'q6')).'</td>
                                                                </tr>
                                                            ';

                                                            $i++;
                                                        }

                                                        if ($i == 1) {
                                                            echo '<tr><td colspan="19" class="text-center text-muted">ไม่พบข้อมูลผู้ใช้ที่มีข้อมูลล่าสุดครบทั้ง test_a และ test_e ในวันเดียวกัน</td></tr>';
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

                                                        function getAws2($n, $q)
                                                        {
                                                            if ($q === '' || $q === null) {
                                                                return '-';
                                                            }

                                                            /*
                                                                test_e บันทึกค่าแบบคะแนนจริง เช่น 0, 1, 2, 3, 4
                                                                ดังนั้น 0 ไม่ใช่ค่าว่าง แต่เป็นคำตอบที่มีความหมาย
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

                                                        function getAge($birthDate) {
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
                                
                                <div class="box-footer text-center">

                                </div>
                                <!-- /.box-body -->
                            </div>
                        
                        </div>
					</div>
				</section><!-- /.content -->
			</div><!-- /.content-wrapper -->

			<!-- Main Footer -->
			<footer class="main-footer">
			<!-- To the right -->
			<?php
				HtmlCore::getMenuFooter();
			?>
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
                            <button type="button" class="btn btn-primary" data-dismiss="modal"> <i class="fas fa-undo-alt"></i> ย้อนกลับ</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
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
                            <button type="button" class="btn btn-primary" data-dismiss="modal"> <i class="fas fa-undo-alt"></i> ย้อนกลับ</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
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
                            <button type="button" class="btn btn-primary" data-dismiss="modal"> <i class="fas fa-undo-alt"></i> ย้อนกลับ</button>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <!-- Modal -->
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
                            <button type="button" class="btn btn-primary" data-dismiss="modal"> <i class="fas fa-undo-alt"></i> ย้อนกลับ</button>
                        </div>
                    </div>
                </div>
            </div>

		</div><!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->

    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>

	<script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/heatmap.js"></script>
    
    <!-- DATA TABES SCRIPT -->
	<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
	<script src="plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/TableExport/4.0.11/css/tableexport.css" rel="stylesheet">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.11.10/xlsx.core.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/blob-polyfill/1.0.20150320/Blob.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.3/FileSaver.min.js"></script>
	<script type="text/javascript" src="js/tableexport.js"></script>
		
    <!-- Optionally, you can add Slimscroll and FastClick plugins.
         Both of these plugins are recommended to enhance the
         user experience. Slimscroll is required when using the
         fixed layout. -->
  </body>
</html>

<script>
	
	$(function () {
		getTableExport();
		dbTable();

        
	});
    function getReport1(user_id){
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "php/get_report1.php",
            data:{ 
                user_id:user_id
            },
            beforeSend: function() {
                $('#report1').appendTo("body").modal('show');
                $("#report1").css("z-index", "9999");
            },
            success: function(result){
                $("#table_report1").html(result);
            }
        });
    }
    function getReport2(user_id){
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "php/get_report2.php",
            data:{ 
                user_id:user_id
            },
            beforeSend: function() {
                $('#report2').appendTo("body").modal('show');
                $("#report2").css("z-index", "9999");
            },
            success: function(result){
                $("#table_report2").html(result);
            }
        });
    }
    function getReport3(user_id){
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "php/get_report3.php",
            data:{ 
                user_id:user_id
            },
            beforeSend: function() {
                $('#report3').appendTo("body").modal('show');
                $("#report3").css("z-index", "9999");
            },
            success: function(result){
                $("#table_report3").html(result);
            }
        });
    }
    function getReport4(user_id){
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "php/get_report4.php",
            data:{ 
                user_id:user_id
            },
            beforeSend: function() {
                $('#report4').appendTo("body").modal('show');
                $("#report4").css("z-index", "9999");
            },
            success: function(result){
                $("#table_report4").html(result);
            }
        });
    }

    function dbTable(){
        $("#db_table").dataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": true,
            "bInfo": false,
            "bAutoWidth": true,
            "pageLength": 50,
            "lengthMenu": [0, 5, 10, 20, 50, 100, 200, 500],
            "language": {
                "emptyTable": "ไม่พบข้อมูล",
                "search": "ค้าหา :",
            }
        });
    }

	function deleteRecord(table,id){
		bootbox.confirm("Are you sure?", function(result){
			if(result){
				deleteDB(table,id,'user_data.php');
			}
		}); 
	}

    function getTableExport(){
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

