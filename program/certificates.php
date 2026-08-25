<?php
include('api/app.php');
$query = new DBControl();
chkLogin();

$user_id = isset($_COOKIE["user_id"]) ? (int)$_COOKIE["user_id"] : 0;

if ($user_id == 0) {
	echo '<script>location.href="login.php";</script>';
	exit;
}

$certificates = [];

/*
	ตรวจเกียรติบัตรความเสี่ยง
	หลักการ: ถ้าเคยมีแถวใดแถวหนึ่งที่ผ่านเงื่อนไข ให้แสดงเกียรติบัตร
	ไม่ยึดเฉพาะแบบสอบถามล่าสุด
*/

// เคยไม่มีความเสี่ยงทั้งบุหรี่มวนและบุหรี่ไฟฟ้า
$risk_all = $query->queryDB_to_row(
	'SELECT * FROM test_b 
	 WHERE user_id = '.$user_id.' 
	 AND q1 = 4 
	 AND q2 = 4 
	 AND q3 = 4 
	 AND q4 = 4 
	 AND q5 = 4 
	 LIMIT 1'
);

// เคยไม่มีความเสี่ยงบุหรี่มวน
$risk_cigarette = $query->queryDB_to_row(
	'SELECT * FROM test_b 
	 WHERE user_id = '.$user_id.' 
	 AND q1 = 4 
	 AND q2 = 4 
	 LIMIT 1'
);

// เคยไม่มีความเสี่ยงบุหรี่ไฟฟ้า
$risk_ecig = $query->queryDB_to_row(
	'SELECT * FROM test_b 
	 WHERE user_id = '.$user_id.' 
	 AND q3 = 4 
	 AND q4 = 4 
	 AND q5 = 4 
	 LIMIT 1'
);

if (!empty($risk_all) && is_array($risk_all)) {

	$certificates[] = [
		'title' => 'เกียรติบัตรความเสี่ยงบุหรี่มวน<br>และบุหรี่ไฟฟ้า',
		'desc' => 'ไม่มีโอกาสเสี่ยงที่จะสูบบุหรี่แบบมวนและบุหรี่ไฟฟ้าในอนาคต',
		'link' => 'certificate.php?type=มวนและไฟฟ้า',
		'icon' => 'fa-certificate'
	];

} else {

	if (!empty($risk_cigarette) && is_array($risk_cigarette)) {
		$certificates[] = [
			'title' => 'เกียรติบัตรความเสี่ยงบุหรี่มวน',
			'desc' => 'ไม่มีโอกาสเสี่ยงที่จะสูบบุหรี่แบบมวนในอนาคต',
			'link' => 'certificate.php?type=มวน',
			'icon' => 'fa-certificate'
		];
	}

	if (!empty($risk_ecig) && is_array($risk_ecig)) {
		$certificates[] = [
			'title' => 'เกียรติบัตรความเสี่ยงบุหรี่ไฟฟ้า',
			'desc' => 'ไม่มีโอกาสเสี่ยงที่จะสูบบุหรี่ไฟฟ้าในอนาคต',
			'link' => 'certificate.php?type=ไฟฟ้า',
			'icon' => 'fa-certificate'
		];
	}
}

/*
	ตรวจเกียรติบัตรเกมตอบคำถาม
	หลักการ: ถ้าเคยได้คะแนนตั้งแต่ 9 คะแนนขึ้นไป ให้แสดงตลอด
*/
$game = $query->queryDB_to_row(
	'SELECT * FROM game 
	 WHERE user_id = '.$user_id.' 
	 AND score >= 9 
	 LIMIT 1'
);

if (!empty($game) && is_array($game)) {
	$certificates[] = [
		'title' => 'เกียรติบัตรความรู้ระดับสูง',
		'desc' => 'มีความรู้เกี่ยวกับบุหรี่มวนและบุหรี่ไฟฟ้าในระดับสูง',
		'link' => 'certificate_game.php',
		'icon' => 'fa-graduation-cap'
	];
}

/*
	ตรวจเกียรติบัตร E-Learning
	หลักการ: ถ้าเคยได้คะแนนตั้งแต่ 12 คะแนนขึ้นไป ให้แสดงตลอด
*/
$elearning = $query->queryDB_to_row(
	'SELECT * FROM elearning 
	 WHERE user_id = '.$user_id.' 
	 AND score >= 12 
	 LIMIT 1'
);

if (!empty($elearning) && is_array($elearning)) {
	$certificates[] = [
		'title' => 'เกียรติบัตร E-Learning',
		'desc' => 'ผ่านการทดสอบ E-Learning',
		'link' => 'certificate_elearning.php',
		'icon' => 'fa-book'
	];
}
?>

<html>

<head>
	<?php
	getHead();
	?>
</head>

<body class="pages">

	<div class="top_menu">
		<div class="top_header">เกียรติบัตรของฉัน</div>

		<a href="home.php">
			<div class="bt_back">
				<i class="fas fa-angle-left" aria-hidden="true"></i>
			</div>
		</a>
	</div>

	<div class="main">
		<div class="container p-3">

			<?php
			if (!empty($certificates)) {

				foreach ($certificates as $cert) {
					echo '
					<div class="box_white mb-3">
						<div class="row">
							<div class="col-12 text-center">

								<div style="
									font-size:48px;
									color:#005573;
									margin-bottom:10px;
								">
									<i class="fa ' . $cert['icon'] . '"></i>
								</div>

								<h5 style="
									color:#004b73;
									font-weight:bold;
									margin-bottom:10px;
								">
									' . $cert['title'] . '
								</h5>

								<p style="
									color:#666;
									font-size:15px;
								">
									' . $cert['desc'] . '
								</p>

								<div class="p-2">
									<a href="' . $cert['link'] . '">
										<button class="bt_big bg-dark">
											<i class="fa fa-download pr-2"></i>
											ดู / ดาวน์โหลด
										</button>
									</a>
								</div>

							</div>
						</div>
					</div>
					';
				}

			} else {
				echo '
				<div class="box_white text-center">
					<div style="padding:30px 10px;">

						<i class="fa fa-certificate" style="
							font-size:55px;
							color:#ccc;
							margin-bottom:15px;
						"></i>

						<p style="
							font-size:18px;
							font-weight:bold;
							color:#777;
						">
							ยังไม่มีเกียรติบัตร
						</p>

						<p style="color:#999;">
							เมื่อผ่านเงื่อนไข ระบบจะแสดงเกียรติบัตรของคุณที่หน้านี้
						</p>

					</div>
				</div>
				';
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