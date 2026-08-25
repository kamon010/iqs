<?php
include('api/app.php');
$query = new DBControl();
chkLogin();

$user_id = $_COOKIE["user_id"] ?? 0;
?>

<html>
<head>
	<?php getHead(); ?>
</head>

<body class="pages">

	<div class="top_menu">
		<div class="top_header">ประวัติผลความเสี่ยง</div>

		<div class="bt_back pointer" onclick="history.back()">
			<i class="fas fa-angle-left"></i>
		</div>
	</div>

	<div class="main">
		<div class="container p-3">

			<?php
			$list = $query->queryDB_to_array(
				'SELECT * FROM test_b 
				 WHERE user_id=' . intval($user_id) . ' 
				 ORDER BY id DESC'
			);

			if (!empty($list)) {

				foreach ($list as $row) {

					echo '
					<div class="box_white mb-3">
						<div class="row">
							<div class="col-12 text-center">

								<p><b>ผลประเมินความเสี่ยง</b></p>

								<p style="font-size:15px; color:#666;">
									วันที่ทำแบบประเมิน ' . date("d-m-Y", strtotime($row['date'])) . '
								</p>

								<hr style="margin:15px 0;">

								<p><b>โอกาสเสี่ยงที่จะสูบบุหรี่แบบมวน</b></p>
								' . getEmujiB($row) . '
								<p style="color:#ff9900">' . getTestB($row) . '</p>

								<hr style="margin:20px 0;">

								<p><b>โอกาสเสี่ยงที่จะสูบบุหรี่ไฟฟ้า</b></p>
								' . getEmujiB2($row) . '
								<p style="color:#ff9900">' . getTestB2($row) . '</p>

							</div>
						</div>
					</div>
					';
				}

			} else {
				echo '
				<div class="box_white text-center">
					ยังไม่มีประวัติผลประเมิน
				</div>
				';
			}


			function getTestB($data)
			{
				if (($data['q1'] ?? '') == 4 && ($data['q2'] ?? '') == 4) {
					return 'ไม่มีโอกาสเสี่ยงที่จะสูบบุหรี่แบบมวนในอนาคต';
				} else {
					return 'มีโอกาสเสี่ยงที่จะสูบบุหรี่แบบมวนในอนาคต';
				}
			}

			function getEmujiB($data)
			{
				if (($data['q1'] ?? '') == 4 && ($data['q2'] ?? '') == 4) {
					return '<img src="img/emoji/1.png?v=2" height="100px">';
				} else {
					return '<img src="img/emoji/10.png?v=2" height="100px">';
				}
			}

			function getTestB2($data)
			{
				if (($data['q3'] ?? '') == 4 && ($data['q4'] ?? '') == 4 && ($data['q5'] ?? '') == 4) {
					return 'ไม่มีโอกาสเสี่ยงที่จะสูบบุหรี่ไฟฟ้าในอนาคต';
				} else {
					return 'มีโอกาสเสี่ยงที่จะสูบบุหรี่ไฟฟ้าในอนาคต';
				}
			}

			function getEmujiB2($data)
			{
				if (($data['q3'] ?? '') == 4 && ($data['q4'] ?? '') == 4 && ($data['q5'] ?? '') == 4) {
					return '<img src="img/emoji/1.png?v=2" height="100px">';
				} else {
					return '<img src="img/emoji/10.png?v=2" height="100px">';
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