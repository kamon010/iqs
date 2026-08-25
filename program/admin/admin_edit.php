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
						ข้อมูลผู้ดูแลระบบ 
						<small>TRC admin</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
						<li class="active">ข้อมูลผู้ดูแลระบบ</li>
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

                                if($page_type=='insert'){
                                    $id = intval($query->queryDB_to_string("SELECT MAX(user_id) FROM user"))+1;
                                }
                                else{
                                    $id = $_GET['id'];
                                    $sql = 'SELECT * FROM user WHERE user_id='.$id;
                                    $data = $query->queryDB_to_row($sql);
                                }
                            ?>
							<div class="box box-primary">
								<form id="form" class="form-horizontal form-label-left">
									<div class="box-header">
										<h3 class="box-title"><b>ข้อมูลผู้ดูแลระบบ</b></h3>
									</div>
									<!-- /.box-header -->
									<div class="box-body">
										<input type="hidden" name="user_id" value="<? echo $id; ?>">
										<input type="hidden" name="user_type" value="admin">
										<div class="col-md-4 col-sm-6 col-xs-12">
											<div class="form-group"  style="padding-right:10px;">
												<label>มหาวิทยาลัย</label>
												<select class="form-control" name="university_id" id="university_id">
													<option>== เลือกมหาวิทยาลัย ==</option>
													<?
														$udata = $query->queryDB_to_array('SELECT * FROM university ORDER BY university_id');
														foreach($udata as $key => $val){
															echo '<option value="'.$val['university_id'].'">'.$val['university_name'].'</option>';
														}
													?>
												</select>
											</div>
										</div>
										<div class="col-md-4 col-sm-6 col-xs-12">
											<div class="form-group"  style="padding-right:10px;">
												<label>ชื่อผู้ใช้</label>
												<input type="text" class="form-control" placeholder="ชื่อผู้ใช้ ..." name="user_username" value="<? echo $data['user_username'];?>" <? echo $str; ?>/>
											</div>
										</div>
										<div class="col-md-4 col-sm-6 col-xs-12">
											<div class="form-group"  style="padding-right:10px;">
												<label>รหัสผ่าน</label>
												<input type="password" class="form-control" placeholder="รหัสผ่าน ..." name="user_password" value="<? echo $data['user_password'];?>"/>
											</div>
                                        </div>

                                        <div class="col-md-6 col-sm-6 col-xs-12">
											<div class="form-group"  style="padding-right:10px;">
												<label>ชื่อ</label>
												<input type="text" class="form-control" placeholder="ชื่อ ..." name="user_name" value="<? echo $data['user_name'];?>"/>
											</div>
										</div>
										<div class="col-md-6 col-sm-6 col-xs-12">
											<div class="form-group"  style="padding-right:10px;">
												<label>นามสกุล</label>
												<input type="text" class="form-control" placeholder="นามสกุล ..." name="user_lastname" value="<? echo $data['user_lastname'];?>"/>
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

		
		$("#form" ).submit(function(event){
			event.preventDefault();
			
			var user_id = $("input[name='user_id']").val();
			var user_username = $("input[name='user_username']").val();
			var user_password = $("input[name='user_password']").val();
			var user_name = $("input[name='user_name']").val();
			var user_lastname = $("input[name='user_lastname']").val();
			var user_type = $("input[name='user_type']").val();
			var university_id = $("#university_id").val();
            
			var values = user_id+','+user_username+','+user_password+','+user_name+','+user_lastname+','+user_type+','+university_id; 
			
			<?
				$fieldsname = "user_id,user_username,user_password,user_name,user_lastname,user_type,university_id";
				$values_add = '';
				$link = 'admin_data.php';
				
				if($page_type=='insert'){
					echo "insertDB('user','".$fieldsname."',values".$values_add.",'".$link."');";
				}
				else{
					echo "updateDB('user','".$id."','".$fieldsname."',values".$values_add.",'".$link."');";
				}
			?>
		});

        $("#upload").on("click",function(event){
			$("#uploadFile").show().click().hide();
			event.preventDefault();
		});
		
		
		$('#uploadFile').on('change', function() {
			var file_data = $('#uploadFile').prop('files')[0];   
			var form_data = new FormData();                  
			form_data.append('file', file_data);              
			$.ajax({
				url: 'php/ajax_upload_image.php?name=user_img', // point to server-side PHP script 
				dataType: 'text',  // what to expect back from the PHP script, if anything
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'post',
				beforeSend:function(result){
					$("#show_loader").html('<img src="images/loader.gif" alt="รอสักครู่">');
					$("#img_old").hide();
					
				}, 
				success: function(result){
					$("#show_loader").html(result);
					//$("#uploadFile").prop('disabled', true);
				}
			 });
		});
		
		
	});
	function deleteFile(file){
		event.preventDefault();
		$.ajax({
			type: "POST",
			url: "php/ajax_delete_file.php",
			data: {
                file:file,
                tb:'lotto',
                field:'lotto_img',
                key:'lotto_id',
                id:<? echo $id; ?>,
            },
			success: function(result){
				$('#show_error').html(result);
				$("#upload").fadeIn();
			}
		});
	}
</script>

