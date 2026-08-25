<?php
include('api/app.php');
$query = new DBControl();
chkLogin();

$user_id = isset($_COOKIE["user_id"]) ? intval($_COOKIE["user_id"]) : 0;

if ($user_id <= 0) {
	echo '<script>location.href="login.php";</script>';
	exit;
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
		<div class="top_header">ผลการประเมิน</div>
		<a href="home.php">
			<div class="bt_back">
				<i class="fas fa-angle-left" aria-hidden="true"></i>
			</div>
		</a>
	</div>

	<div class="main">
		<div class="container p-3">
			<?php

			/*
				ดึงข้อมูลคะแนน E-Learning ทั้งหมดของผู้ใช้
				เรียงจากครั้งแรกไปครั้งล่าสุด
			*/
			$data = $query->queryDB_to_array(
				'SELECT * FROM elearning 
				 WHERE user_id = ' . $user_id . ' 
				 ORDER BY elearning_id ASC'
			);

			if (empty($data) || !is_array($data)) {
				$data = array();
			}

			$count = count($data);

			/*
				ใช้คะแนนจากรายการสุดท้ายของ data เป็นคะแนนล่าสุด
				ไม่ใช้ query แยก เพราะบางครั้ง query แยกดึงคะแนนเก่า
			*/
			$score = 0;

			if ($count > 0) {
				$last_data = $data[$count - 1];
				$score = intval($last_data['score'] ?? 0);
			}

			if ($count > 0) {

				$text_ad = ($count == 1) ? 'PreTest' : 'PostTest';

				$chkwin = false;

				if ($score >= 12) {
					$chkwin = true;
				}

				echo '
				<div class="box_white elearning-result-card">

					<div class="elearning-title">
						ผลการทดสอบ E-Learning
					</div>

					<div class="elearning-status-row">
						<div class="elearning-badge">
							' . $text_ad . '
						</div>

						<div class="elearning-emoji">
							' . getEmuji($score) . '
						</div>
					</div>

					<div class="elearning-score">
						' . $score . ' คะแนน
					</div>

					<div class="elearning-score-label">
						คะแนนล่าสุดของคุณ
					</div>

					<div class="elearning-history-box">
						<div class="elearning-history-title">
							ประวัติคะแนน
						</div>
				';

				$i = 1;

				foreach ($data as $val) {
					$show_score = intval($val['score'] ?? 0);

					echo '
						<div class="elearning-history-item">
							<div>ทดสอบครั้งที่ ' . $i . '</div>
							<div class="elearning-history-score">' . $show_score . ' คะแนน</div>
						</div>
					';

					$i++;
				}

				echo '
					</div>

					<div class="p-2 pt-3">
						<a href="history_elearning.php">
							<button class="bt_big bg-dark">
								<i class="fa fa-history pr-2" aria-hidden="true"></i>
								ดูประวัติการทำ E-Learning
							</button>
						</a>
					</div>
				';

				/*
					ถ้ายังไม่ผ่านเกณฑ์ ให้แสดงปุ่มกลับไปเรียนเพิ่มเติม
					ถ้าผ่านแล้ว ไม่ต้องแสดงปุ่มนี้
				*/
				if (!$chkwin) {
					echo '
					<div class="p-2 pt-3">
						<a href="video.php">
							<button class="bt_big bg-dark">
								<i class="fa fa-video-camera pr-2" aria-hidden="true"></i>
								คลิปให้ความรู้ E-Learning (ศึกษาเพิ่มเติม)
							</button>
						</a>
					</div>
					';
				}

				echo '
				</div>
				';

				/*
					ถ้าคะแนนล่าสุดผ่านเกณฑ์ 12 คะแนนขึ้นไป
					ให้แสดงเกียรติบัตร
				*/
				if ($chkwin) {

					$user_data = $query->queryDB_to_row(
						'SELECT * FROM user WHERE user_id = ' . $user_id
					);

					$user_name = '';

					if (!empty($user_data) && is_array($user_data)) {
						$user_name = $user_data['user_name'] ?? '';
					}

					echo '
					<div class="box_white elearning-cert-box">

						<img src="img/logo.png" height="90px">

						<p><b>ขอมอบเกียรติบัตรเพื่อแสดงว่า</b></p>

						<div class="elearning-cert-name">
							' . $user_name . '
						</div>

						<div class="elearning-cert-text">
							ผ่านการทดสอบ E-Learning
						</div>

						<div class="p-2">
							<a href="certificate_elearning.php">
								<button class="bt_big bg-dark">
									<i class="fa fa-download pr-2"></i>
									ดาวน์โหลดเกียรติบัตร
								</button>
							</a>
						</div>

					</div>
					';
				}

			} else {

				echo '
				<script>
					Swal.fire({
						html: "ขออภัย ! <br> ท่านยังไม่ได้ทำแบบสอบถาม !",
						type: "error",
						confirmButtonText: "ตกลง",
						confirmButtonColor: "#3085d6",
						timer: 5000
					}).then((result) => {
						location.href = "e-learning.php";
					});
				</script>
				';

			}

			function getEmuji($score)
			{
				if ($score >= 12) {
					return '<img src="img/emoji/e12.png?v=2" height="200px">';
				} else if ($score < 5) {
					return '<img src="img/emoji/e5.png?v=2" height="200px">';
				} else if ($score < 7) {
					return '<img src="img/emoji/e7.png?v=2" height="200px">';
				} else {
					return '<img src="img/emoji/e10.png?v=2" height="200px">';
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
					<i class="fa fa-home" aria-hidden="true"></i>
				</div>
			</a>

			<div class="menu menu-3 text-left"></div>

		</div>
	</div>

</body>

<script>
	$(function() {});
</script>

</html>
