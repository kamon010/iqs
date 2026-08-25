<?
include('api/app.php');
$query = new DBControl();
chkLogin();

$user_id = $_COOKIE["user_id"] ?? '';

if ($user_id == '') {
	echo '<script> location.href = "login.php"; </script>';
	exit;
}

$user_data = $query->queryDB_to_row('SELECT * FROM user WHERE user_id=' . $user_id);

if (empty($user_data)) {
	echo '<script> location.href = "login.php"; </script>';
	exit;
}

$user_name = $user_data['user_name'] ?? '';
$user_img = $user_data['user_img'] ?? 'img/user.png';
?>

<html>

<head>
	<?
	getHead();
	?>
</head>

<body class="pages">

	<div class="home-modern-top">
		<div>
			<div class="home-modern-title">สวัสดี, <? echo $user_name; ?>!</div>
			<div class="home-modern-date"><? echo date("d/m/Y"); ?></div>
		</div>

		<div class="home-modern-actions">
			<a href="profile.php">
				<img src="<? echo $user_img; ?>" class="home-modern-avatar">
			</a>
		</div>
	</div>

	<?
	$sql = "SELECT * FROM picture WHERE type='แบนเนอร์' ORDER BY date DESC";
	$data = $query->queryDB_to_array($sql);
	?>

	<div class="main trc-main">
		<div class="container">

			<div class="row">
				<div class="col-12 p-3">
					<div id="carouselExampleIndicators" class="carousel slide trc-banner-box" data-ride="carousel">

						<ol class="carousel-indicators">
							<?
							$n = 0;
							foreach ($data as $val) {
								echo '<li data-target="#carouselExampleIndicators" data-slide-to="' . $n . '" class="' . ($n == 0 ? 'active' : '') . '"></li>';
								$n++;
							}
							?>
						</ol>

						<div class="carousel-inner">
							<?
							$n = 0;
							foreach ($data as $val) {
								$str = ($n == 0) ? 'active' : '';
								echo '
								<div class="carousel-item ' . $str . '">
									<img src="' . ($val['detail'] ?? '') . '" class="d-block w-100 trc-banner-img">
								</div>';
								$n++;
							}
							?>
						</div>

					</div>
				</div>
			</div>

			<div class="trc-menu-layout">

				<a href="knowledge.php" class="trc-menu-item">
					<div class="trc-menu-icon"><i class="fa fa-book"></i></div>
					<div>ความรู้</div>
				</a>

				<a href="reports.php" class="trc-menu-item">
					<div class="trc-menu-icon"><i class="fa fa-bar-chart"></i></div>
					<div>รายงาน</div>
				</a>

				<a href="evaluation.php" class="trc-menu-item">
					<div class="trc-menu-icon"><i class="fa fa-check-square-o"></i></div>
					<div>แบบประเมิน</div>
				</a>

				<a href="certificates.php" class="trc-menu-item">
					<div class="trc-menu-icon"><i class="fa fa-certificate"></i></div>
					<div>เกียรติบัตร</div>
				</a>

			</div>

			<?
			$score = $query->queryDB_to_string('SELECT score FROM test_a WHERE user_id=' . $user_id . ' ORDER BY id DESC');
			$s = $query->queryDB_to_row('SELECT * FROM screening WHERE user_id=' . $user_id . ' ORDER BY id DESC');

			if ($score >= 5 && $score != '') {

				$chkhowto = $query->queryDB_to_row('SELECT * FROM howto WHERE user_id=' . $user_id . ' ORDER BY id DESC');

				if (!empty($chkhowto)) {

					$day = $query->queryDB_to_string('SELECT day FROM day WHERE user_id=' . $user_id . ' ORDER BY id DESC');

					if (!empty($day)) {
						$link = 'days.php?d=' . ($day + 1);
					} else {
						$link = 'day_1.php';
					}

				} else {
					$link = 'howto_1.php';
				}

				$old = $query->queryDB_to_row('SELECT day,date FROM day WHERE user_id=' . $user_id . ' ORDER BY id DESC LIMIT 1');
				$date = date('Y-m-d');

				if (!empty($old) && isset($old['date']) && $date <= $old['date']) {
					$link .= '&chk=1';
				}

				$title = 'เลิกบุหรี่ต่อเนื่อง';
				$desc = 'เข้าสู่แผนเลิกบุหรี่';
				$icon = 'fa-power-off';

			} else {

				$link = 'screening.php';
				$title = 'ทำแบบคัดกรอง';
				$desc = 'เริ่มประเมินก่อนเลิกบุหรี่';
				$icon = 'fa-edit';
			}
			?>

			<div class="trc-activity-box">

				<div class="trc-section-title">กิจกรรม</div>

				<a href="<? echo $link; ?>" class="trc-activity-card">
					<div class="trc-activity-left">
						<div class="trc-activity-icon blue">
							<i class="fa <? echo $icon; ?>"></i>
						</div>
						<div>
							<div class="trc-activity-title"><? echo $title; ?></div>
							<div class="trc-activity-desc"><? echo $desc; ?></div>
						</div>
					</div>
					<i class="fa fa-angle-right"></i>
				</a>

				<a href="e-learning.php" class="trc-activity-card">
					<div class="trc-activity-left">
						<div class="trc-activity-icon purple">
							<i class="fa fa-desktop"></i>
						</div>
						<div>
							<div class="trc-activity-title">เรียน E-Learning</div>
							<div class="trc-activity-desc">เข้าสู่บทเรียน</div>
						</div>
					</div>
					<i class="fa fa-angle-right"></i>
				</a>

			</div>

			<div class="container text-center pt-5">
				<? getCopyRight(); ?>
			</div>

		</div>
	</div>

	<div class="footer">
		<div class="container">

			<a href="profile.php">
				<div class="menu menu-5 text-left">
					<i class="fa fa-user-circle-o"></i>
				</div>
			</a>
			<a href="knowledge.php">
				<div class="menu menu-5 text-left">
					<i class="fa fa-book"></i>
				</div>
			</a>
			<a href="home.php">
				<div class="menu menu-5 text-center active">
					<i class="fa fa-home"></i>
				</div>
			</a>
			<a href="screening.php">
				<div class="menu menu-5 text-right">
					<i class="fa fa-edit"></i>
				</div>
			</a>
			<a href="reports.php">
				<div class="menu menu-5 text-right">
					<i class="fa fa-bar-chart"></i>
				</div>
			</a>

		</div>
	</div>

</body>
</html>