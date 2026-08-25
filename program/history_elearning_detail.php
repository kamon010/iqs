<?php
include('api/app.php');
include('php/elearning_questions.php');

$query = new DBControl();
chkLogin();

$user_id = isset($_COOKIE["user_id"]) ? intval($_COOKIE["user_id"]) : 0;
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$attempt_no = isset($_GET['attempt']) ? intval($_GET['attempt']) : 0;

if ($user_id <= 0) {
	echo '<script>location.href="login.php";</script>';
	exit;
}

if ($id > 0) {
	$row = $query->queryDB_to_row(
		'SELECT * FROM elearning 
		 WHERE user_id = ' . $user_id . ' 
		   AND elearning_id = ' . $id . ' 
		 LIMIT 1'
	);
} else {
	$row = array();
}

if ((empty($row) || !is_array($row)) && $attempt_no > 0) {
	$offset = $attempt_no - 1;
	$row = $query->queryDB_to_row(
		'SELECT * FROM elearning 
		 WHERE user_id = ' . $user_id . ' 
		 ORDER BY elearning_id ASC
		 LIMIT ' . $offset . ', 1'
	);
}

if (!empty($row) && is_array($row)) {
	$id = intval($row['elearning_id'] ?? $id);

	if ($attempt_no <= 0) {
		$attempt_no = intval($query->queryDB_to_string(
			'SELECT COUNT(*) FROM elearning 
			 WHERE user_id = ' . $user_id . ' 
			   AND elearning_id <= ' . $id
		));
	}
}

if ($attempt_no <= 0) {
	$attempt_no = 1;
}

$questions = getElearningQuestions();
$correct_count = 0;

foreach ($questions as $question) {
	if (isElearningAnswerCorrect($row, $question)) {
		$correct_count++;
	}
}
?>

<html>

<head>
	<?php getHead(); ?>

	<style>
		.elearning-detail-summary {
			text-align: center;
		}

		.elearning-detail-badge {
			display: inline-block;
			background: #eef7fa;
			color: #2f7887;
			border-radius: 30px;
			padding: 5px 16px;
			font-size: 14px;
			font-weight: bold;
			margin-bottom: 10px;
		}

		.elearning-detail-score {
			font-size: 42px;
			font-weight: bold;
			color: #004b73;
			line-height: 1;
			padding: 8px 0;
		}

		.elearning-question-title {
			color: #004b73;
			font-size: 16px;
			font-weight: bold;
			line-height: 1.5;
			margin-bottom: 12px;
		}

		.elearning-result-pill {
			display: inline-block;
			border-radius: 30px;
			padding: 4px 14px;
			font-size: 13px;
			font-weight: bold;
			margin-bottom: 12px;
		}

		.elearning-result-pill.correct {
			background: #e8f7ee;
			color: #1f7a43;
		}

		.elearning-result-pill.wrong {
			background: #fdecec;
			color: #c0392b;
		}

		.elearning-choice {
			border: 1px solid #e4ebef;
			border-radius: 10px;
			padding: 10px 12px;
			margin-bottom: 8px;
			color: #34495e;
			background: #fff;
			font-size: 14px;
		}

		.elearning-choice.selected-correct {
			border-color: #2ca75f;
			background: #eefaf3;
			color: #1f7a43;
			font-weight: bold;
		}

		.elearning-choice.selected-wrong {
			border-color: #d9534f;
			background: #fff0ef;
			color: #c0392b;
			font-weight: bold;
		}

		.elearning-choice-mark {
			float: right;
			font-size: 12px;
			font-weight: bold;
		}

		.elearning-note {
			color: #888;
			font-size: 13px;
			line-height: 1.5;
			margin: 8px 0 0;
		}
	</style>
</head>

<body class="pages">

	<div class="top_menu">
		<div class="top_header">รายละเอียด E-Learning</div>

		<div class="bt_back pointer" onclick="history.back()">
			<i class="fas fa-angle-left"></i>
		</div>
	</div>

	<div class="main">
		<div class="container p-3">

			<?php if (empty($row) || !is_array($row)) { ?>
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
							margin:0 0 10px 0;
						">
							ไม่พบรายละเอียดการทำ E-Learning
						</p>

						<p style="color:#999;">
							กรุณากลับไปเลือกประวัติอีกครั้ง
						</p>

						<div class="p-2">
							<a href="history_elearning.php">
								<button class="bt_big bg-dark">
									<i class="fa fa-history pr-2" aria-hidden="true"></i>
									กลับไปหน้าประวัติ
								</button>
							</a>
						</div>
					</div>
				</div>
			<?php } else { ?>

			<div class="box_white mb-3 elearning-detail-summary">
				<div class="elearning-detail-badge">
					<?php echo htmlspecialchars(getElearningAttemptLabel($attempt_no), ENT_QUOTES, 'UTF-8'); ?>
				</div>

				<p style="font-size:18px; font-weight:bold; color:#004b73; margin-bottom:5px;">
					ทดสอบครั้งที่ <?php echo $attempt_no; ?>
				</p>

				<div class="elearning-detail-score">
					<?php echo intval($row['score'] ?? 0); ?>
				</div>

				<p style="color:#ff9900; font-size:18px; font-weight:bold;">
					<?php echo getElearningScoreText(intval($row['score'] ?? 0)); ?>
				</p>

				<p style="color:#777; margin-bottom:5px;">
					ตอบถูก <?php echo $correct_count; ?> จาก <?php echo count($questions); ?> ข้อ
				</p>

				<p class="elearning-note">
					ข้อที่ตอบผิดจะแสดงเฉพาะคำตอบที่เลือกไว้ โดยไม่เฉลยคำตอบที่ถูกต้อง
				</p>
			</div>

			<?php
			foreach ($questions as $question_no => $question) {
				$is_correct = isElearningAnswerCorrect($row, $question);
				$answer = getElearningAnswer($row, $question);

				echo '
				<div class="box_white mb-3">
					<div class="elearning-question-title">
						ข้อ ' . intval($question_no) . '. ' . htmlspecialchars($question['title'], ENT_QUOTES, 'UTF-8') . '
					</div>

					<div class="elearning-result-pill ' . ($is_correct ? 'correct' : 'wrong') . '">
						' . ($is_correct ? 'ตอบถูก' : 'ตอบผิด') . '
					</div>
				';

				if ($question_no == 1) {
					echo '
					<div class="pb-3">
						<img src="img/elearning.png" class="img">
					</div>
					';
				}

				$has_answer = false;

				foreach ($question['options'] as $choice_value => $choice_text) {
					if ($question['type'] == 'checkbox') {
						$is_selected = in_array(intval($choice_value), $answer);
					} else {
						$is_selected = intval($answer) == intval($choice_value);
					}

					if ($is_selected) {
						$has_answer = true;
					}

					$class_name = '';
					$mark_text = '';

					if ($is_selected && $is_correct) {
						$class_name = 'selected-correct';
						$mark_text = 'คำตอบที่เลือก';
					} else if ($is_selected && !$is_correct) {
						$class_name = 'selected-wrong';
						$mark_text = 'คำตอบที่เลือก';
					}

					echo '
					<div class="elearning-choice ' . $class_name . '">
						' . htmlspecialchars($choice_text, ENT_QUOTES, 'UTF-8') . '
						' . ($mark_text != '' ? '<span class="elearning-choice-mark">' . $mark_text . '</span>' : '') . '
						<div style="clear:both;"></div>
					</div>
					';
				}

				if (!$has_answer) {
					echo '
					<p class="elearning-note">
						ผู้ใช้งานไม่ได้เลือกคำตอบในข้อนี้
					</p>
					';
				}

				echo '
				</div>
				';
			}
			?>

			<?php } ?>

		</div>
	</div>

	<div class="footer">
		<div class="container">
			<a href="history_elearning.php">
				<div class="menu menu-3 text-left">
					<i class="fa fa-chevron-left"></i>
				</div>
			</a>

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
