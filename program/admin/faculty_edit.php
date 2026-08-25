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
	<body class="hold-transition skin-blue-light"  style="font-family: 'Kanit', sans-serif;">
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
						HtmlCore::getMenu('university');
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
						แก้ไขข้อมูลคณะ
						<small>TRC admin</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
						<li class="active">แก้ไขข้อมูลคณะ</li>
					</ol>
				</section>

				<!-- Main content -->
				<section class="content" style="font-family: 'Kanit'">
                    <!-- Your Page Content Here -->
					<div class="row">
						
						<div class="col-md-12">
                            <?
                                if($_GET['id']==''){
                                    $page_type = 'insert';
                                }
                                else{
                                    $page_type = 'edit';
                                    $str = 'disabled';
                                }

                                $university_name = $query->queryDB_to_string('SELECT university_name FROM university WHERE university_id='.$_GET['uid']);

                                if($page_type=='insert'){
                                    $id = intval($query->queryDB_to_string("SELECT MAX(faculty_id) FROM faculty"))+1;
                                }
                                else{
                                    $id = $_GET['id'];
                                    $sql = 'SELECT * FROM faculty WHERE faculty_id='.$id;
                                    $data = $query->queryDB_to_row($sql);
                                }
                            ?>
							<div class="box box-primary">
								<form id="form" class="form-horizontal form-label-left">
									<div class="box-header">
										<h3 class="box-title"><b>แก้ไขข้อมูลคณะ</b></h3>
									</div>
									<!-- /.box-header -->
									<div class="box-body">
										<input type="hidden" name="university_id" value="<? echo $_GET['uid']; ?>">
                                        <input type="hidden" name="faculty_id" value="<? echo $id; ?>">
										<div class="col-md-6 col-sm-6 col-xs-12">
											<div class="form-group"  style="padding-right:10px;">
												<label>มหาวิทยาลัย</label>
												<input type="text" class="form-control" placeholder="มหาวิทยาลัย ..." name="university_name" value="<? echo $university_name; ?>" disabled/>
											</div>
										</div>

										<div class="col-md-6 col-sm-6 col-xs-12">
											<div class="form-group"  style="padding-right:10px;">
												<label>ชื่อคณะ</label>
												<input type="text" class="form-control" placeholder="ชื่อคณะ ..." name="faculty_name" value="<? echo $data['faculty_name'];?>"/>
											</div>
                                        </div>
                                        
										
									</div>
									
									<div class="box-footer" align="center">
										<div class="form-group">
											<a href="#">
												<button type="submit" class="btn btn-primary">บันทึก</button>
											</a>
										</div>								
									</div>
									<!-- /.box-body -->
								</form>
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
		
    <!-- Optionally, you can add Slimscroll and FastClick plugins.
         Both of these plugins are recommended to enhance the
         user experience. Slimscroll is required when using the
         fixed layout. -->
  </body>
</html>

<script>
	$( function() {

		
		$("#form").submit(function(event){
			event.preventDefault();
			var faculty_id = $("input[name='faculty_id']").val();
			var faculty_name = $("input[name='faculty_name']").val();
			var university_id = $("input[name='university_id']").val();
            
			var values = faculty_id+','+faculty_name+','+university_id; 
			
			<?
				$fieldsname = "faculty_id,faculty_name,university_id";
				$values_add = '';
				$link = 'faculty_data.php?id='.$_GET['uid'];
				if($page_type=='insert'){
					echo "insertDB('faculty','".$fieldsname."',values".$values_add.",'".$link."');";
				}
				else{
					echo "updateDB('faculty','".$id."','".$fieldsname."',values".$values_add.",'".$link."');";
				}
			?>
		});
		
		
	});
</script>

