<?php
include('api/app.php');
include('php/elearning_questions.php');

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
	<?php getHead(); ?>
</head>

<body class="pages">

	<div class="top_menu">
		<div class="top_header">ประวัติ E-Learning</div>

		<div class="bt_back pointer" onclick="history.back()">
			<i class="fas fa-angle-left"></i>
		</div>
	</div>

	<div class="main">
		<div class="container p-3">

			<?php
			$list = $query->queryDB_to_array(
				'SELECT * FROM elearning 
				 WHERE user_id = ' . $user_id . ' 
				 ORDER BY elearning_id ASC'
			);

			if (!empty($list) && is_array($list)) {
				$items = array();
				$attempt_no = 1;

				foreach ($list as $row) {
					$row['attempt_no'] = $attempt_no;
					$items[] = $row;
					$attempt_no++;
				}

				$items = array_reverse($items);

				foreach ($items as $row) {
					$score = intval($row['score'] ?? 0);
					$attempt = intval($row['attempt_no'] ?? 1);
					$type_text = getElearningAttemptLabel($attempt);
					$date_text = '';

					if (!empty($row['date'])) {
						$date_text = date("d-m-Y", strtotime($row['date']));
					}

					echo '
					<div class="box_white mb-3">
						<div class="row">
							<div class="col-12 text-center">
								<p style="
									font-size:18px;
									font-weight:bold;
									color:#004b73;
									margin-bottom:6px;
								">
									ทดสอบครั้งที่ ' . $attempt . '
								</p>

								<div style="
									display:inline-block;
									background:#eef7fa;
									color:#2f7887;
									border-radius:30px;
									padding:5px 16px;
									font-size:14px;
									font-weight:bold;
									margin-bottom:12px;
								">
									' . htmlspecialchars($type_text, ENT_QUOTES, 'UTF-8') . '
								</div>

								<div style="
									font-size:42px;
									font-weight:bold;
									color:#004b73;
									line-height:1;
									padding:8px 0;
								">
									' . $score . '
								</div>

								<p style="
									color:#ff9900;
									font-size:20px;
									font-weight:bold;
									margin-bottom:8px;
								">
									' . getElearningScoreText($score) . '
								</p>

								<p style="
									color:#888;
									margin-bottom:12px;
								">
									วันที่ทำแบบทดสอบ ' . htmlspecialchars($date_text, ENT_QUOTES, 'UTF-8') . '
								</p>

								<div class="p-2">
									<button class="bt_big bg-dark" onclick="location.href=\'history_elearning_detail.php?id=' . intval($row['elearning_id']) . '&attempt=' . $attempt . '\'">
										<i class="fa fa-list-alt pr-2" aria-hidden="true"></i>
										ดูรายละเอียดคำตอบ
									</button>
								</div>
							</div>
						</div>
					</div>
					';
				}
			} else {
				echo '
				<div class="box_white text-center">
					<div style="padding:30px;">
						<i class="fa fa-folder-open" style="
							font-size:50px;
							color:#ccc;
							margin-bottom:15px;
						"></i>

						<p style="
							font-size:18px;
							color:#777;
							margin:0;
						">
							ยังไม่มีประวัติ E-Learning
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
