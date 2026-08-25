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
		setPlugin('jquery,jquery-ui,alert,loaderbar,ckeditor');
		setPermission('login');
		
		HtmlCore::getTitle();
        
        $query = new DBControl();
		$type = 'e-learning';
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
						HtmlCore::getMenu('e-learning');
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
						E-learning
						<small>TRC admin</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
						<li class="active">e-learning</li>
					</ol>
				</section>

				<!-- Main content -->
				<section class="content" style="font-family: 'Kanit'">
					<!-- Your Page Content Here -->
					<div class="row">
                        <div class="col-md-12">
                            
                            <div class="box box-primary">
                                <form id="form" class="form-horizontal form-label-left">
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                            <?
                                                $page_type = 'edit';  
                                                $type = 'e-learning'; 
                                                $query = new DBControl();
                                               
                                                $id = 1;
                                                $sql = 'SELECT * FROM page WHERE id='.$id;
                                                $data = $query->queryDB_to_row($sql);
                                                
                                            ?>
                                            <input type="hidden" name="id" value="e-learning">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group"  style="padding-right:10px;">
                                                    <label>Video link <small>รองรับ YouTube, TikTok URL, Google Drive หรือไฟล์ .mp4</small></label>
                                                    <input type="text" class="form-control" placeholder="เช่น YouTube URL, TikTok URL, Google Drive file link หรือ .mp4" name="name" value="<? echo htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8');?>"/>
													<input type="hidden" name="id" value="<? echo $id; ?>">
                                                    <input type="hidden" name="type" value="<? echo $type; ?>">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label>รายละเอียด</label>
													<textarea class="form-control" id="detail" name="detail" >
														<? 
															$html = $data['detail'];
															$html = str_replace('src="','src="../',$html);
															$html = str_replace("src='","src='../",$html);
														
															echo $html;
														
														?>
													</textarea>
                                                </div>
                                            </div>


                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="box-footer text-center">
                                        <button type="submit" class="btn btn-primary" >บันทึก</button>
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

		editor = CKEDITOR.replace('detail',{
			language: 'th',
			height: 520,
			allowedContent: true,
			contentsCss: ['css/ckeditor-content.css'],
			bodyClass: 'trc-admin-editor-body',
			image_previewText: ' ',
		});
        
		$("#form").submit(function(event){
			event.preventDefault();
			
			var id = 1;
			var name = $("input[name='name']").val().split(',').join('&#44;');
			
			var detail = editor.getData();
			detail = detail.split(',').join('&#44;');
			detail = detail.split('+').join('&#43;');

			var type = $("input[name='type']").val().split(',').join('&#44;');
			
			var values = id+','+name+','+detail+','+type; 
			
			<?
				$fieldsname = "id,name,detail,type";
				$values_add = '';
				$link = 'e-learning.php';
				
				if($page_type=='insert'){
					echo "insertDB('page','".$fieldsname."',values".$values_add.",'".$link."');";
				}
				else{
					echo "updateDB('page','".$id."','".$fieldsname."',values".$values_add.",'".$link."');";
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
				url: 'php/ajax_upload_image.php?name=detail', // point to server-side PHP script 
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

	function deleteRecord(table,id){
		bootbox.confirm("Are you sure?", function(result){
			if(result){
				deleteDB(table,id,'book.php');
			}
		}); 
	}
	
    function deleteFile(file){
		event.preventDefault();
		$.ajax({
			type: "POST",
			url: "php/ajax_delete_file.php",
			data: {
                file:file,
                tb:'book',
                field:'detail',
                key:'id',
                id: <? echo $id; ?>,
            },
			success: function(result){
				$('#show_error').html(result);
				$("#upload").fadeIn();
			}
		});
	}

</script>

