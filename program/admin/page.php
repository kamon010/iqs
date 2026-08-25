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
        $type = $_GET['type'];
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
						HtmlCore::getMenu($type);
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
                        จัดการข้อมูล
						<small><? echo $type; ?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
						<li class="active"><? echo $type; ?></li>
					</ol>
				</section>

				<!-- Main content -->
				<section class="content" style="font-family: 'Kanit'">
					<!-- Your Page Content Here -->
					<div class="row">
                        <div class="col-md-12">
                            
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title"><b><? echo $type; ?></b></h3>

                                    <div class="box-tools pull-right">
                                        <a href="page_edit.php?type=<? echo $type; ?>">
                                            <button class="btn btn-warning"><i class="fa fa-plus"></i> เพิ่มข้อมูล</button>
                                        </a>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            
                                        <div class="table-responsive">
                                            <?
                                                $query = new DBControl();
                                                $sql = "SELECT * FROM page WHERE type='".$type."' ORDER BY id";		
                                                $raw_data = $query->queryDB_to_array($sql);
                                                
                                                $tableHead = array('ลำดับ','หัวข้อ','แก้ไข','ลบ');
                                                
                                                $tableAttribute = array(
                                                    'id'=>'db_table',
                                                    'class'=>'table table-bordered table-hover',
                                                    'style'=>''
                                                );
                                                $tdValues = array(
                                                );
                                                
                                                $new_array = array();
                                                $i = 1;
                                                foreach($raw_data as $key => $val){
                                                    $new_array[$key][0] = $val['id'];
                                                    $new_array[$key][1] = $i;
                                                    $new_array[$key][2] = $val['name'];
                                                    $new_array[$key][3] = '<a href="page_edit.php?type='.$type.'&id='.$val['id'].'"><button class="btn btn-success" title="แก้ไข"><i class="fa fa-edit"></i>&nbsp;&nbsp;แก้ไข</button></a>';
                                                    $new_array[$key][4] = '<a href="#" onClick="deleteRecord(\'page\',\''.$val['id'].'\')"><button type="button" title="ลบ" class="btn btn-danger"><i class="fa fa-trash"></i>&nbsp;&nbsp;ลบ</button></a>';
                                                    $i++;
                                                }
                                                
                                                $htmlTable = HtmlControl::getDBTable($new_array,$tableAttribute,$tdValues);
                                                $htmlTable = HtmlControl::addHeadTable($htmlTable,array($tableHead),true,'th');
                                                
                                                echo $htmlTable;
                                            ?>
                                            </div>

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
				deleteDB(table,id,'page.php?type=<? echo $type; ?>');
			}
		}); 
	}
	

</script>

