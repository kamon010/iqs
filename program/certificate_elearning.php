<?
include('api/app.php');
$query = new DBControl();

$user_id = $_COOKIE["user_id"] ?? 0;

if ($user_id == 0) {
	die('กรุณาเข้าสู่ระบบ');
}

$user = $query->queryDB_to_row(
	'SELECT * FROM user WHERE user_id=' . intval($user_id)
);

$user_name = $user['user_name'] ?? 'ไม่พบชื่อ';

$date_text = 'ให้ไว้ ณ วันที่ ' . date("d") . ' ' . getThaiMonth(date("m")) . ' ' . (date("Y") + 543);

function getThaiMonth($month)
{
	$thai_months = [
		'01' => 'มกราคม',
		'02' => 'กุมภาพันธ์',
		'03' => 'มีนาคม',
		'04' => 'เมษายน',
		'05' => 'พฤษภาคม',
		'06' => 'มิถุนายน',
		'07' => 'กรกฎาคม',
		'08' => 'สิงหาคม',
		'09' => 'กันยายน',
		'10' => 'ตุลาคม',
		'11' => 'พฤศจิกายน',
		'12' => 'ธันวาคม'
	];

	return $thai_months[$month];
}
?>

<html>

<head>
	<?
	getHead();
	?>

	<style>
		.certificate_box {
			position: relative;
			width: 100%;
			border-radius: 20px;
			overflow: hidden;
			box-shadow: 0 10px 30px rgba(0, 0, 0, 0.10);
			background: #fff;
		}

		.certificate_img {
			width: 100%;
			display: block;
		}

		.certificate_name {
			position: absolute;
			top: 46%;
			left: 50%;
			transform: translate(-50%, -50%);
			font-size: 10px;
			font-weight: 400;
			color: #222;
			width: 80%;
			text-align: center;
			font-family: 'Prompt', 'Kanit', sans-serif;
		}

		.certificate_date {
			position: absolute;
			top: 65%;
			left: 50%;
			transform: translate(-50%, -50%);
			font-size: 8px;
			font-weight: 400;
			color: #222;
			width: 80%;
			text-align: center;
			font-family: 'Prompt', 'Kanit', sans-serif;
		}

		.download_bt {
			margin-top: 20px;
		}
	</style>
</head>

<body class="pages">

	<div class="top_menu">
		<div class="top_header">เกียรติบัตร</div>

		<div class="bt_back pointer" onclick="history.back()">
			<i class="fas fa-angle-left"></i>
		</div>
	</div>

	<div class="main">
		<div class="container p-3">

			<div class="box_white text-center">

				<p class="text-orange">
					<b>เกียรติบัตรของคุณ</b>
				</p>

				<div class="certificate_box">

					<img src="img/t3.png" class="certificate_img">

					<div class="certificate_name">
						<?= $user_name ?>
					</div>

					<div class="certificate_date">
						<?= $date_text ?>
					</div>

				</div>

				<div class="download_bt">
					<a href="certificate_elearning_download.php">
						<button class="bt_big bg-dark">
							<i class="fa fa-download pr-2"></i>
							ดาวน์โหลดเกียรติบัตร
						</button>
					</a>
				</div>

			</div>

		</div>
	</div>

	<div class="footer">
		<div class="container">

			<div class="menu menu-3 text-left"></div>

			<a href="home.php">
				<div class="menu menu-3m active">
					<i class="fa fa-home"></i>
				</div>
			</a>

			<div class="menu menu-3 text-left"></div>

		</div>
	</div>

</body>

</html>