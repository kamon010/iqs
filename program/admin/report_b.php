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
	<?
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
				<?
					HtmlCore::getTextHeader();
				?>

				<!-- Header Navbar -->
				<nav class="navbar navbar-static-top" role="navigation">
					<!-- Sidebar toggle button-->
					<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
						<span class="sr-only">Toggle navigation</span>
					</a>
					<!-- Navbar Right Menu -->
					<?
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
					<?
						HtmlCore::getMenu('report_b');
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
                                                    <tr">
                                                        <th class="text-center">ลำดับ</th>
                                                        <th class="text-center">ประเภทผู้สมัคร</th>
                                                        <th class="text-center">ชื่อ-นามสกุล</th>
                                                        <th class="text-center">ครั้งที่</th>
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
                                                    <?
                                                        $query = new DBControl();

                                                        
                                                        $user_id = $_COOKIE["user"];
		                                                $university_id = $query->queryDB_to_string("SELECT university_id FROM user WHERE user_id = ".$user_id);
                                                        $user_type = $query->queryDB_to_string("SELECT university_id FROM user WHERE user_id = ".$user_id);

                                                        $scope_condition = getApplicantAdminScopeCondition($university_id, 'university_id');
                                                        $sql = "SELECT * FROM user WHERE user_type!='admin' ".$scope_condition." ORDER BY user_id";
                                                        $data = $query->queryDB_to_array($sql);
                                                        


                                                        $i = 1;
                                                        foreach($data as $key => $val){
                                                            $applicant = getApplicantEducationLabel($val['applicant_type'] ?? 'university', $val['applicant_level'] ?? '');
                                                            
                                                            $texts = '';
                                                            $tests = $query->queryDB_to_array("SELECT * FROM test_a WHERE user_id=".$val['user_id']);		
                                                            $n = 1;
                                                            if(sizeof($tests)>0){
                                                                foreach($tests as $index => $v){	
                                                                    if($n>1){
                                                                        $texts .= '<tr class="text-center"><td></td><td></td><td></td></td>';
                                                                    }
                                                                    $texts .='
                                                                        <td class="text-center">'.$n.'</td>
                                                                        <td class="text-center">'.getTextDateTH($v['date']).'</td>
                                                                        <td class="text-center">'.getAws(1,$v['q1']).'</td>
                                                                        <td class="text-center">'.getAws(2,$v['q2']).'</td>
                                                                        <td class="text-center">'.getAws(3,$v['q3']).'</td>
                                                                        <td class="text-center">'.getAws(4,$v['q4']).'</td>
                                                                        <td class="text-center">'.getAws(5,$v['q5']).'</td>
                                                                        <td class="text-center">'.getAws(6,$v['q6']).'</td>
                                                                        <td class="text-center">'.$v['score'].'</td>
                                                                    ';
                                                                    $n++;
                                                                    if($n!=sizeof($tests)){
                                                                        $texts .='</tr>';
                                                                    }
                                                                }
                                                            }
                                                            else{
                                                                $texts .='<td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>';
                                                            }

                                                            echo '
                                                                <tr class="text-center">
                                                                    <td class="text-center">'.$i.'</td>
                                                                    <td>'.$applicant.'</td>
                                                                    <td class="text-left">'.$val['user_name'].' '.$val['user_lastname'].'</td>
                                                                    '.$texts.'
                                                                </tr>
                                                            ';
                                                            $i++;
                                                        }

                                                        function getAws($n,$q){
                                                            if($n==1){
                                                                if($q==1){
                                                                    return '10 มวน หรือน้อยกว่า';
                                                                }
                                                                else if($q==2){
                                                                    return '11-20 มวน';
                                                                }
                                                                else if($q==3){
                                                                    return '21-30 มวน';
                                                                }
                                                                else if($q==4){
                                                                    return 'มากกว่า 30 มวน';
                                                                }
                                                            }
                                                            else if($n==2){
                                                                if($q==1){
                                                                    return 'ภายใน 5 นาทีหลังตื่นนอน';
                                                                }
                                                                else if($q==2){
                                                                    return '6-30 นาที หลังตื่นนอน';
                                                                }
                                                                else if($q==3){
                                                                    return '31-60 นาที หลังตื่นนอน';
                                                                }
                                                                else if($q==4){
                                                                    return 'มากกว่า 60 นาทีหลังตื่น';
                                                                }
                                                            }
                                                            else if($n==3){
                                                                if($q==1){
                                                                    return 'ใช่';
                                                                }
                                                                else if($q==2){
                                                                    return 'ไม่ใช่';
                                                                }
                                                            }
                                                            else if($n==2){
                                                                if($q==1){
                                                                    return 'มวนแรกตอนเช้า';
                                                                }
                                                                else if($q==2){
                                                                    return 'มวนอื่นๆ';
                                                                }
                                                            }
                                                            else if($n==4){
                                                                if($q==1){
                                                                    return 'รู้สึกลำบาก';
                                                                }
                                                                else if($q==2){
                                                                    return 'ไม่รู้สึกลำบาก';
                                                                }
                                                            }
                                                            else if($n==5){
                                                                if($q==1){
                                                                    return 'ใช่';
                                                                }
                                                                else if($q==2){
                                                                    return 'ไม่ใช่';
                                                                }
                                                            }
                                                        }

                                                    ?>
                                                 <tbody>
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
			<?
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
            "bSort": false,
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
            filename: 'ข้อมูลสมาชิก <? echo date('d-m-Y'); ?>',
            bootstrap: true,
            exportButtons: true,
            position: 'bottom',
            ignoreRows: null,
            ignoreCols: null,
            trimWhitespace: true
        });
    }
	

</script>

