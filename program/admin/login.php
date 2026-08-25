<?
ob_start();
?>
<!DOCTYPE html>
<html class="lockscreen">
<head>
	<meta charset="UTF-8">

	<link rel="shortcut icon" type="image/jpg" href="../img/icon.png" />
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link href="css/ionicons.min.css" rel="stylesheet" type="text/css" />
	<link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Quicksand|Kanit" rel="stylesheet">

	<style>
		.lockscreen {
			background: url(images/background.jpg) repeat center center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}

		.lockscreen > body {
			background: transparent;
		}

		.lockscreen .headline {
			color: #fff;
			text-shadow: 1px 3px 5px rgba(0, 0, 0, 0.5);
			font-weight: 300;
			-webkit-font-smoothing: antialiased !important;
			opacity: 0.8;
			margin: 10px 0 30px 0;
			font-size: 90px;
		}

		@media screen and (max-width: 480px) {
			.lockscreen .headline {
				font-size: 60px;
				margin-bottom: 40px;
			}
		}

		.lockscreen .lockscreen-name {
			text-align: center;
			font-weight: 600;
			font-size: 16px;
		}

		.lockscreen-item {
			padding: 0;
			background: #fff;
			position: relative;
			-webkit-border-radius: 4px;
			-moz-border-radius: 4px;
			border-radius: 4px;
			margin: 10px auto;
			width: 290px;
		}

		.lockscreen-item:before,
		.lockscreen-item:after {
			display: table;
			content: " ";
		}

		.lockscreen-item:after {
			clear: both;
		}

		.lockscreen-item > .lockscreen-image {
			position: absolute;
			left: -10px;
			top: -30px;
			background: #fff;
			padding: 10px;
			-webkit-border-radius: 50%;
			-moz-border-radius: 50%;
			border-radius: 50%;
			z-index: 10;
		}

		.lockscreen-item > .lockscreen-image > img {
			width: 70px;
			height: 70px;
			-webkit-border-radius: 50%;
			-moz-border-radius: 50%;
			border-radius: 50%;
		}

		.lockscreen-item > .lockscreen-credentials {
			margin-left: 80px;
		}

		.lockscreen-item > .lockscreen-credentials input {
			border: 0 !important;
		}

		.lockscreen-item > .lockscreen-credentials .btn {
			background-color: #fff;
			border: 0;
		}

		.lockscreen-link {
			margin-top: 30px;
			text-align: center;
		}

		input.pw {
			-webkit-text-security: disc;
			text-security: disc;
		}
	</style>

	<?
	include("php/htmlCore.php");
	include('indyEngine/engineControl.php');

	setInclude('htmlCore,htmlControl,sessionControl');
	setPlugin('jquery,jquery-ui,alert,hrefDefault,loaderbar');

	HtmlCore::getTitle();

	$query = new DBControl();

	if (isset($_COOKIE["user"]) && $_COOKIE["user"] != '') {
		echo '
		<script language="javascript">
			window.location = "index.php";
		</script>
		';
		exit;
	}
	?>

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
</head>

<body>
	<div class="center">
		<div class="headline text-center">
			<img src="../img/logo.png" alt="TRC" width="20%" style="margin-top:15px;">
		</div>

		<div style="padding:0px;">
			<form id="form">
				<?
				if (isset($_COOKIE["recall"]) && $_COOKIE["recall"] != '') {

					$recall_id = intval($_COOKIE["recall"]);

					$sql = 'SELECT user_username FROM user WHERE user_id = "'.$recall_id.'"';
					$data = $query->queryDB_to_row($sql);

					$recall_username = '';

					if (!empty($data) && isset($data['user_username'])) {
						$recall_username = $data['user_username'];
					}

					echo '
						<div class="lockscreen-name" style="color:#696969">'.$recall_username.'</div>

						<div class="lockscreen-item">
							<input name="uumm" type="hidden" value="'.$recall_username.'" />
					';
				} else {
					echo '
						<div class="lockscreen-item">
							<div class="lockscreen-credentials">
								<div class="input-group">
									<input name="uumm" type="text" class="form-control" placeholder="username" autocomplete="username" />
								</div>
							</div>
						</div>

						<div class="lockscreen-item">
					';
				}
				?>

					<div class="lockscreen-credentials">
						<div class="input-group">
							<input name="ppww" type="text" class="form-control pw" placeholder="password" autocomplete="current-password" />
							<div class="input-group-btn">
								<button type="submit" class="btn btn-flat">
									<i class="fa fa-sign-in text-muted"></i>
								</button>
							</div>
						</div>
					</div>
				</div>
			</form>

			<div class="lockscreen-link">
				<div id="alertlogin"></div>

				<?
				if (isset($_COOKIE["recall"]) && $_COOKIE["recall"] != '') {
					echo '
					<a href="indyEngine/sessionControl.php?unrecall=true" style="color:#696969; text-shadow: 1px 1px #fff;">
						เข้าสู่ระบบด้วยบัญชีผู้ใช้อื่น
					</a>
					';
				}
				?>
			</div>
		</div>
	</div>

	<script src="js/bootstrap.min.js" type="text/javascript"></script>
	<script src="js/AdminLTE/app.js" type="text/javascript"></script>

	<script type="text/javascript">
		$(function () {
			$(".center").center();

			$(window).resize(function () {
				$(".center").center();
			});

			$("#form").submit(function (event) {
				event.preventDefault();

				$.ajax({
					type: "POST",
					url: "indyEngine/sessionControl.php",
					data: $("#form").serialize(),
					success: function (result) {
						$("#alertlogin").html(result);
					},
					error: function (xhr) {
						$("#alertlogin").html('<span style="color:red;">เกิดข้อผิดพลาดในการเข้าสู่ระบบ</span>');
						console.log(xhr.responseText);
					}
				});
			});
		});

		function startTime() {
			var today = new Date();
			var h = today.getHours();
			var m = today.getMinutes();
			var s = today.getSeconds();

			m = checkTime(m);
			s = checkTime(s);

			var day_or_night = (h > 11) ? "PM" : "AM";

			if (h > 12) {
				h -= 12;
			}

			$('#time').html(h + ":" + m + ":" + s + " " + day_or_night);

			setTimeout(function () {
				startTime();
			}, 500);
		}

		function checkTime(i) {
			if (i < 10) {
				i = "0" + i;
			}

			return i;
		}

		jQuery.fn.center = function () {
			this.css("position", "absolute");
			this.css(
				"top",
				Math.max(
					0,
					(($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop()
				) - 30 + "px"
			);

			this.css(
				"left",
				Math.max(
					0,
					(($(window).width() - $(this).outerWidth()) / 2) + $(window).scrollLeft()
				) + "px"
			);

			return this;
		};
	</script>
</body>
</html>