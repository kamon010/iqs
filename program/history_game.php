<?
include('api/app.php');
$query = new DBControl();
chkLogin();

$user_id = $_COOKIE["user_id"] ?? 0;
?>

<html>

<head>
	<?
	getHead();
	?>
</head>

<body class="pages">

<div class="top_menu">
	<div class="top_header">ประวัติเกมตอบคำถาม</div>

	<div class="bt_back pointer" onclick="history.back()">
		<i class="fas fa-angle-left"></i>
	</div>
</div>

<div class="main">
	<div class="container p-3">

		<?
		$list = $query->queryDB_to_array(
			'SELECT * FROM game 
			 WHERE user_id=' . intval($user_id) . '
			 ORDER BY id DESC'
		);

		if (!empty($list)) {

			foreach ($list as $row) {

				$score = intval($row['score']);

				echo '
				<div class="box_white mb-3">

					<div class="row">

						<div class="col-12 text-center">

							<p style="
								font-size:18px;
								font-weight:bold;
							">
								เกมตอบคำถาม
							</p>

							<div style="
								font-size:42px;
								font-weight:bold;
								color:#004b73;
								padding:10px 0;
							">
								' . $score . '
							</div>

							<p style="
								color:#ff9900;
								font-size:22px;
								font-weight:bold;
							">
								' . getGameText($score) . '
							</p>

							<p style="
								color:#888;
								padding-top:10px;
							">
								วันที่ทำแบบทดสอบ ' . date("d-m-Y", strtotime($row['date'])) . '
							</p>

						</div>

					</div>

				</div>
				';
			}

		} else {

			echo '
			<div class="box_white text-center">
				ยังไม่มีประวัติการทดสอบ
			</div>
			';
		}

		function getGameText($score)
		{
			if ($score < 6) {
				return 'ไม่ผ่านเกณฑ์';
			}
			else if ($score < 9) {
				return 'มีความรู้ปานกลาง';
			}
			else {
				return 'มีความรู้สูง';
			}
		}
		?>

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