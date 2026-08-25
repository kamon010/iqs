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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<?
		include("php/htmlCore.php");
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
						HtmlCore::getMenu('admin_data');
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
                        ผู้ดูแลระบบ
						<small>TRC admin</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
						<li class="active">ผู้ดูแลระบบ</li>
					</ol>
				</section>

				<!-- Main content -->
				<section class="content" style="font-family: 'Kanit'">
					<!-- Your Page Content Here -->
					<div class="row">
                        <div class="col-md-12">
                            
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title"><b>ผู้ดูแลระบบ</b></h3>

                                    <div class="box-tools pull-right">
                                        <a href="admin_edit.php">
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
                                                        <th class="text-center">ข้อมูลมหาวิทยาลัย</th>
                                                        <th class="text-center">ชื่อ</th>
                                                        <th class="text-center">แก้ไข</th>
                                                        <th class="text-center">ลบ</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?
                                                        $query = new DBControl();
                                                        $sql = "SELECT * FROM user WHERE user_type='admin' AND user_id!=1 ORDER BY user_id";		
                                                        $data = $query->queryDB_to_array($sql);

                                                        $i = 1;
                                                        foreach($data as $key => $val){
                                                            $university = $query->queryDB_to_string('SELECT university_name FROM university WHERE university_id='.$val['university_id']);
                                                            echo '
                                                                <tr>
                                                                    <td class="text-center">'.$i.'</td>
                                                                    <td>'.$university.'</td>
                                                                    <td>'.$val['user_name'].' '.$val['user_lastname'].'</td>
                                                                    <td class="text-center"><a href="admin_edit.php?id='.$val['user_id'].'"><button type="button" title="แก้ไข" class="btn btn-success"><i class="fa fa-edit"></i>&nbsp;&nbsp;แก้ไข</button></a></td>
                                                                    <td class="text-center"><a href="#" onClick="deleteRecord(\'user\',\''.$val['user_id'].'\')"><button type="button" title="ลบ" class="btn btn-danger"><i class="fa fa-trash"></i>&nbsp;&nbsp;ลบ</button></a></td>
                                                                </tr>
                                                            ';
                                                            $i++;
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
		
    <!-- Optionally, you can add Slimscroll and FastClick plugins.
         Both of these plugins are recommended to enhance the
         user experience. Slimscroll is required when using the
         fixed layout. -->
  </body>
</html>

<script>
	
	$(function () {
		
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
            "bLengthChange": false,
            "bFilter": true,
            "bSort": true,
            "bInfo": false,
            "bAutoWidth": false,
            "pageLength": 50,
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
	

</script>

