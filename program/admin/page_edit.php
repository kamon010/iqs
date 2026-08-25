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

	<?php
		include("php/htmlCore.php");
		include('indyEngine/engineControl.php');

		setInclude('htmlControl,dbControl,IDUControl,sessionControl');
		setPlugin('jquery,jquery-ui,alert,loaderbar,ckeditor');
		setPermission('login');

		HtmlCore::getTitle();

		$query = new DBControl();

		$type = $_GET['type'] ?? '';
		$get_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

		$page_type = ($get_id > 0) ? 'edit' : 'insert';

		$data = [
			'name' => '',
			'detail' => ''
		];

		if ($page_type == 'insert') {
			$id = intval($query->queryDB_to_string("SELECT MAX(id) FROM page")) + 1;
		} else {
			$id = $get_id;
			$sql = 'SELECT * FROM page WHERE id=' . intval($id);
			$row = $query->queryDB_to_row($sql);

			if (!empty($row)) {
				$data = array_merge($data, $row);
			}
		}

		function prepareDetailForEditor($html)
		{
			if ($html == null || trim($html) == '') {
				return '';
			}

			$html = preg_replace_callback('/src=(["\'])(.*?)\1/i', function ($m) {
				$quote = $m[1];
				$src = trim($m[2]);

				if (
					strpos($src, 'http://') === 0 ||
					strpos($src, 'https://') === 0 ||
					strpos($src, 'data:image') === 0
				) {
					return 'src=' . $quote . $src . $quote;
				}

				$src = str_replace('./uploads/', 'uploads/', $src);
				$src = str_replace('/uploads/', 'uploads/', $src);
				$src = str_replace('../../uploads/', 'uploads/', $src);
				$src = str_replace('../uploads/', 'uploads/', $src);

				if (strpos($src, 'uploads/') === 0) {
					$src = '../' . $src;
				}

				return 'src=' . $quote . $src . $quote;
			}, $html);

			return $html;
		}

		$editor_detail = prepareDetailForEditor($data['detail']);
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
			<?php HtmlCore::getMenu('book'); ?>
		</section>
	</aside>

	<div class="content-wrapper">

		<section class="content-header">
			<h1>
				<?php echo htmlspecialchars($type); ?>
				<small>TRC admin</small>
			</h1>

			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li class="active"><?php echo htmlspecialchars($type); ?></li>
			</ol>
		</section>

		<section class="content" style="font-family: 'Kanit'">
			<div class="row">
				<div class="col-md-12">

					<div class="box box-primary">
						<form id="form" class="form-horizontal form-label-left">

							<div class="box-header">
								<h3 class="box-title">
									<b><?php echo htmlspecialchars($type); ?></b>
								</h3>
							</div>

							<div class="box-body">
								<div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12">

										<input type="hidden" name="id" value="<?php echo intval($id); ?>">

										<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="form-group" style="padding-right:10px;">
												<label>หัวข้อ</label>
												<input
													type="text"
													class="form-control"
													placeholder="หัวข้อ ..."
													name="name"
													value="<?php echo htmlspecialchars($data['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
												/>
												<input type="hidden" name="type" value="<?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?>">
											</div>
										</div>

										<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="form-group">
												<label>รายละเอียด</label>
												<textarea class="form-control" id="detail" name="detail"><?php echo $editor_detail; ?></textarea>
											</div>
										</div>

									</div>
								</div>
							</div>

							<div class="box-footer text-center">
								<button type="submit" class="btn btn-primary">บันทึก</button>
							</div>

						</form>
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

<script>
	$(function () {

		var editor = CKEDITOR.replace('detail', {
			language: 'th',
			height: 520,
			allowedContent: true,
			contentsCss: ['css/ckeditor-content.css'],
			bodyClass: 'trc-admin-editor-body',

			// เอาข้อความ Lorem ipsum ในหน้าต่างคุณสมบัติรูปภาพออก
			image_previewText: ' '
		});

		function cleanDetailBeforeSave(html) {
			if (!html) {
				return '';
			}

			/*
				ลบช่องว่างจาก CKEditor ที่ทำให้หน้าแสดงผลมีพื้นที่ว่าง เช่น
				<p>&nbsp;</p>
				<p><br></p>
				<p></p>
			*/
			html = html.replace(/<p\b[^>]*>(?:\s|&nbsp;|&#160;|\u00A0|<br\s*\/?>)*<\/p>/gi, '');

			/*
				แปลง path รูปก่อนบันทึกกลับฐานข้อมูล
				ให้ฐานข้อมูลเก็บเป็น uploads/xxx.jpg
				ไม่เก็บ ../uploads/xxx.jpg
			*/
			html = html.replace(/src=(["'])\.\.\/uploads\//gi, 'src=$1uploads/');
			html = html.replace(/src=(["'])\.\.\/\.\.\/uploads\//gi, 'src=$1uploads/');
			html = html.replace(/src=(["'])\.\/uploads\//gi, 'src=$1uploads/');
			html = html.replace(/src=(["'])\/uploads\//gi, 'src=$1uploads/');

			/*
				ถ้า CKEditor หรือข้อมูลเก่าใส่ input type="image"
				ให้แปลงเป็น img ปกติ
			*/
			html = html.replace(/<input([^>]*?)\s+src=(["'])(.*?)\2([^>]*)>/gi, function (match, before, quote, src, after) {
				before = before.replace(/\s*type=(["']).*?\1/gi, '');
				before = before.replace(/\s*name=(["']).*?\1/gi, '');
				before = before.replace(/\s*value=(["']).*?\1/gi, '');

				after = after.replace(/\s*type=(["']).*?\1/gi, '');
				after = after.replace(/\s*name=(["']).*?\1/gi, '');
				after = after.replace(/\s*value=(["']).*?\1/gi, '');

				return '<img' + before + ' src=' + quote + src + quote + after + '>';
			});

			/*
				ลบ style / width / height เฉพาะแท็ก img
				เพื่อไม่ให้ขนาดภาพไปบังคับหน้าแสดงผล
				และไม่กระทบ style ของข้อความส่วนอื่น
			*/
			html = html.replace(/<img\b[^>]*>/gi, function (tag) {
				tag = tag.replace(/\sstyle=(["']).*?\1/gi, '');
				tag = tag.replace(/\swidth=(["']).*?\1/gi, '');
				tag = tag.replace(/\sheight=(["']).*?\1/gi, '');
				return tag;
			});

			return html;
		}

		$("#form").submit(function(event) {
			event.preventDefault();

			var id = $("input[name='id']").val();
			var name = $("input[name='name']").val().split(',').join('&#44;');

			var detail = editor.getData();
			detail = cleanDetailBeforeSave(detail);

			detail = detail.split(',').join('&#44;');
			detail = detail.split('+').join('&#43;');

			var type = $("input[name='type']").val().split(',').join('&#44;');

			var values = id + ',' + name + ',' + detail + ',' + type;

			<?php
				$fieldsname = "id,name,detail,type";
				$values_add = '';
				$link = 'page.php?type=' . $type;

				if ($page_type == 'insert') {
					echo "insertDB('page','" . $fieldsname . "',values" . $values_add . "," . json_encode($link, JSON_UNESCAPED_UNICODE) . ");";
				} else {
					echo "updateDB('page','" . intval($id) . "','" . $fieldsname . "',values" . $values_add . "," . json_encode($link, JSON_UNESCAPED_UNICODE) . ");";
				}
			?>
		});

		$("#upload").on("click", function(event) {
			$("#uploadFile").show().click().hide();
			event.preventDefault();
		});

		$('#uploadFile').on('change', function() {
			var file_data = $('#uploadFile').prop('files')[0];
			var form_data = new FormData();

			form_data.append('file', file_data);

			$.ajax({
				url: 'php/ajax_upload_image.php?name=detail',
				dataType: 'text',
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,
				type: 'post',
				beforeSend: function(result) {
					$("#show_loader").html('<img src="images/loader.gif" alt="รอสักครู่">');
					$("#img_old").hide();
				},
				success: function(result) {
					$("#show_loader").html(result);
				}
			});
		});

	});

	function deleteRecord(table, id) {
		bootbox.confirm("Are you sure?", function(result) {
			if (result) {
				deleteDB(table, id, 'book.php');
			}
		});
	}

	function deleteFile(file) {
		event.preventDefault();

		$.ajax({
			type: "POST",
			url: "php/ajax_delete_file.php",
			data: {
				file: file,
				tb: 'book',
				field: 'detail',
				key: 'id',
				id: <?php echo intval($id); ?>
			},
			success: function(result) {
				$('#show_error').html(result);
				$("#upload").fadeIn();
			}
		});
	}
</script>

</body>
</html>
