<?php

include('api/app.php');

$query = new DBControl();

chkLogin();

$user_id = $_COOKIE["user_id"] ?? '';

if ($user_id == '') {
	echo '<script>location.href = "login.php";</script>';
	exit;
}

$user_id = intval($user_id);

$user_data = $query->queryDB_to_row(
	'SELECT * FROM user WHERE user_id=' . $user_id
);

if (empty($user_data)) {
	echo '<script>location.href = "login.php";</script>';
	exit;
}

$user_name = $user_data['user_name'] ?? '';
$user_img = $user_data['user_img'] ?? 'img/user.png';

function getImagePath($detail)
{
	if ($detail === null || trim($detail) === '') {
		return '';
	}

	$detail = html_entity_decode(
		trim($detail),
		ENT_QUOTES,
		'UTF-8'
	);

	if (stripos($detail, '<img') !== false) {
		if (preg_match('/src=["\']([^"\']+)["\']/i', $detail, $matches)) {
			$detail = $matches[1];
		}
	}

	$detail = strtok($detail, '?');

	$detail = str_replace(
		array('\\', '../', './'),
		array('/', '', ''),
		$detail
	);

	if (strpos($detail, 'uploads/images/') === 0) {
		if (file_exists(__DIR__ . '/' . $detail)) {
			return $detail;
		}

		$fileName = basename($detail);
		$newPath = 'uploads/' . $fileName;

		if (file_exists(__DIR__ . '/' . $newPath)) {
			return $newPath;
		}

		return $detail;
	}

	if (strpos($detail, 'uploads/') === 0) {
		if (file_exists(__DIR__ . '/' . $detail)) {
			return $detail;
		}

		$fileName = basename($detail);
		$newPath = 'uploads/' . $fileName;

		if (file_exists(__DIR__ . '/' . $newPath)) {
			return $newPath;
		}

		return $detail;
	}

	$fileName = basename($detail);
	$newPath = 'uploads/' . $fileName;

	if (file_exists(__DIR__ . '/' . $newPath)) {
		return $newPath;
	}

	return $detail;
}

$user_img = getImagePath($user_img);

if ($user_img == '') {
	$user_img = 'img/user.png';
}

$sql = "SELECT * FROM picture WHERE type='แบนเนอร์' ORDER BY date DESC";
$data = $query->queryDB_to_array($sql);

$bannerData = array();

foreach ($data as $val) {
	$imagePath = getImagePath($val['detail'] ?? '');

	if ($imagePath != '') {
		$val['image_path'] = $imagePath;
		$bannerData[] = $val;
	}
}

$score = $query->queryDB_to_string(
	'SELECT score FROM test_a WHERE user_id=' . $user_id . ' ORDER BY id DESC'
);

$s = $query->queryDB_to_row(
	'SELECT * FROM screening WHERE user_id=' . $user_id . ' ORDER BY id DESC'
);

if ($score >= 5 && $score != '') {
	$chkhowto = $query->queryDB_to_row(
		'SELECT * FROM howto WHERE user_id=' . $user_id . ' ORDER BY id DESC'
	);

	if (!empty($chkhowto)) {
		$day = $query->queryDB_to_string(
			'SELECT day FROM day WHERE user_id=' . $user_id . ' ORDER BY id DESC'
		);

		if (!empty($day)) {
			$link = 'days.php?d=' . ($day + 1);
		} else {
			$link = 'day_1.php';
		}
	} else {
		$link = 'howto_1.php';
	}

	$old = $query->queryDB_to_row(
		'SELECT day,date FROM day WHERE user_id=' . $user_id . ' ORDER BY id DESC LIMIT 1'
	);

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

<!DOCTYPE html>
<html>

<head>
    <?php getHead(); ?>
</head>

<body class="pages">

    <div class="home-modern-top">
        <div>
            <div class="home-modern-title">
                สวัสดี, <?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?>!
            </div>

            <div class="home-modern-date">
                <?php echo date("d/m/Y"); ?>
            </div>
        </div>

        <div class="home-modern-actions">
            <a href="profile.php">
                <img src="<?php echo htmlspecialchars($user_img, ENT_QUOTES, 'UTF-8'); ?>" class="home-modern-avatar"
                    alt="Profile">
            </a>
        </div>
    </div>

    <div class="main trc-main">
        <div class="container">

            <?php if (count($bannerData) > 0) { ?>
            <div class="row">
                <div class="col-12 p-3">
                    <div id="carouselExampleIndicators" class="carousel slide trc-banner-box" data-ride="carousel">

                        <?php if (count($bannerData) > 1) { ?>
                        <ol class="carousel-indicators">
                            <?php
									foreach ($bannerData as $index => $val) {
										?>
                            <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $index; ?>"
                                class="<?php echo ($index == 0) ? 'active' : ''; ?>"></li>
                            <?php
									}
									?>
                        </ol>
                        <?php } ?>

                        <div class="carousel-inner">
                            <?php
								foreach ($bannerData as $index => $val) {
									$imagePath = $val['image_path'];
									?>
                            <div class="carousel-item <?php echo ($index == 0) ? 'active' : ''; ?>">
                                <img src="<?php echo htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8'); ?>"
                                    class="d-block w-100 trc-banner-img" alt="">
                            </div>
                            <?php
								}
								?>
                        </div>

                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="trc-menu-layout">

                <a href="knowledge.php" class="trc-menu-item">
                    <div class="trc-menu-icon">
                        <i class="fa fa-book"></i>
                    </div>
                    <div>ความรู้</div>
                </a>

                <a href="reports.php" class="trc-menu-item">
                    <div class="trc-menu-icon">
                        <i class="fa fa-bar-chart"></i>
                    </div>
                    <div>รายงาน</div>
                </a>

                <a href="evaluation.php" class="trc-menu-item">
                    <div class="trc-menu-icon">
                        <i class="fa fa-check-square-o"></i>
                    </div>
                    <div>แบบประเมิน</div>
                </a>

                <a href="certificates.php" class="trc-menu-item">
                    <div class="trc-menu-icon">
                        <i class="fa fa-certificate"></i>
                    </div>
                    <div>เกียรติบัตร</div>
                </a>

            </div>

            <div class="trc-activity-box">

                <div class="trc-section-title">
                    กิจกรรม
                </div>

                <a href="<?php echo htmlspecialchars($link, ENT_QUOTES, 'UTF-8'); ?>" class="trc-activity-card">
                    <div class="trc-activity-left">
                        <div class="trc-activity-icon blue">
                            <i class="fa <?php echo htmlspecialchars($icon, ENT_QUOTES, 'UTF-8'); ?>"></i>
                        </div>

                        <div>
                            <div class="trc-activity-title">
                                <?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
                            </div>

                            <div class="trc-activity-desc">
                                <?php echo htmlspecialchars($desc, ENT_QUOTES, 'UTF-8'); ?>
                            </div>
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
                            <div class="trc-activity-title">
                                เรียน E-Learning
                            </div>

                            <div class="trc-activity-desc">
                                เข้าสู่บทเรียน
                            </div>
                        </div>
                    </div>

                    <i class="fa fa-angle-right"></i>
                </a>

                <a href="youth_survey.php" class="trc-activity-card">
                    <div class="trc-activity-left">
                        <div class="trc-activity-icon blue"><i class="fa fa-list-alt"></i></div>
                        <div><div class="trc-activity-title">แบบสอบถามเด็กและเยาวชน</div>
                        <div class="trc-activity-desc">อายุ 10–18 ปี · 30 ข้อ · ตอบโดยสมัครใจ</div></div>
                    </div><i class="fa fa-angle-right"></i>
                </a>

            </div>

            <div class="container text-center pt-5">
                <?php getCopyRight(); ?>
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