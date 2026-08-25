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
		<div class="top_header">ผลลัพธ์รวม</div>
		<div class="bt_back pointer" onclick="history.back()">
			<i class="fas fa-angle-left" aria-hidden="true"></i>
		</div>
	</div>

	<div class="main">
		<div class="container p-3">

			<?
			$count_chk = 0;

			$a_count = intval($query->queryDB_to_string(
				'SELECT COUNT(*) FROM test_a WHERE user_id=' . intval($user_id)
			));

			$a = $query->queryDB_to_row(
				'SELECT * FROM test_a WHERE user_id=' . intval($user_id) . ' ORDER BY id DESC LIMIT 1'
			);

			if (!empty($a) && is_array($a)) {

				$score_a = intval($a['score']);

				if (checkTestA($score_a)) {
					$action_a = '
					<div class="p-2">
						<a href="howto_1.php">
							<button class="bt_big bg-dark">
								<i class="fa fa-exclamation-circle pr-2"></i>
								วิธีเลิกบุหรี่
							</button>
						</a>
					</div>';
				} else {
					$action_a = '
					<div class="p-2">
						<a href="https://www.thailandquitline.or.th/site/" target="_blank">
							<button class="bt_big bg-dark">
								<i class="fa fa-exclamation-circle pr-2"></i>
								Quitline 1600
							</button>
						</a>
					</div>';
				}

				$history_a = '';

				if ($a_count > 1) {
					$history_a = '
					<div class="col-12">
						<div class="p-2 bt_history_wrap">
							<a href="history_test_a.php">
								<button class="bt_big bt_history_soft">
									<i class="fa fa-history"></i>
									ดูประวัติผลประเมินบุหรี่มวน
								</button>
							</a>
						</div>
					</div>';
				}

				echo '
				<div class="result-section">
					<p class="text-orange"><b>ระดับการติดสารนิโคติน</b></p>

					<div class="box_white">
						<div class="row">

							<div class="col-12 text-center">
								<p><b>ผลการประเมิน</b></p>

								<div class="pb-2">
									' . getEmujiA($score_a) . '
								</div>

								<h4 class="pt-2">' . $score_a . ' คะแนน</h4>

								<p style="color:#ff9900">
									' . getTestA($score_a) . '
								</p>
							</div>

							<div class="col-12 text-left">
								<b>คำแนะนำ</b><br>
								<small>' . getTextTestA($score_a) . '</small>
							</div>

							<div class="col-12">
								' . $action_a . '
							</div>

							' . $history_a . '

						</div>
					</div>
				</div>
				';

				$count_chk++;
			}


			$e_count = intval($query->queryDB_to_string(
				'SELECT COUNT(*) FROM test_e WHERE user_id=' . intval($user_id)
			));

			$e = $query->queryDB_to_row(
				'SELECT * FROM test_e WHERE user_id=' . intval($user_id) . ' ORDER BY id DESC LIMIT 1'
			);

			if (!empty($e) && is_array($e)) {

				$score_e = intval($e['score']);

				$history_e = '';

				if ($e_count > 1) {
					$history_e = '
					<div class="p-2 bt_history_wrap">
						<a href="history_test_e.php">
							<button class="bt_big bt_history_soft">
								<i class="fa fa-history"></i>
								ดูประวัติผลประเมินบุหรี่ไฟฟ้า
							</button>
						</a>
					</div>';
				}

				echo '
				<div class="result-section">
					<p class="text-orange"><b>การติดนิโคตินจากบุหรี่ไฟฟ้า</b></p>

					<div class="box_white">
						<div class="row">

							<div class="col-12 text-center">
								<p><b>ผลการประเมิน</b></p>

								<div class="pb-2">
									' . getEmujiE($score_e) . '
								</div>

								<div style="color:#ff9900; font-size:22px; font-weight:bold; padding-bottom:10px;">
									' . getTestE($score_e) . '
								</div>

								<p>
									การแปลผลคะแนนรวมของท่านคือ<br>
									<b>' . $score_e . ' คะแนน</b>
								</p>

								' . $history_e . '

							</div>

						</div>
					</div>
				</div>
				';

				$count_chk++;
			}


			$b_count = intval($query->queryDB_to_string(
				'SELECT COUNT(*) FROM test_b WHERE user_id=' . intval($user_id)
			));

			$b = $query->queryDB_to_row(
				'SELECT * FROM test_b WHERE user_id=' . intval($user_id) . ' ORDER BY id DESC LIMIT 1'
			);

			if (!empty($b) && is_array($b)) {

				$risk_button_a = '';
				$risk_button_e = '';
				$history_b = '';

				if (checkRisk($b)) {
					$risk_button_a = '
					<div class="p-2">
						<a href="knowledge.php">
							<button class="bt_big bg-dark">
								<i class="fa fa-exclamation-circle pr-2"></i>
								แหล่งความรู้
							</button>
						</a>
					</div>';
				}

				if (checkRisk2($b)) {
					$risk_button_e = '
					<div class="p-2">
						<a href="knowledge.php">
							<button class="bt_big bg-dark">
								<i class="fa fa-exclamation-circle pr-2"></i>
								แหล่งความรู้
							</button>
						</a>
					</div>';
				}

				if ($b_count > 1) {
					$history_b = '
					<div class="p-2 bt_history_wrap">
						<a href="history_test_b.php">
							<button class="bt_big bt_history_soft">
								<i class="fa fa-history"></i>
								ดูประวัติผลประเมินความเสี่ยง
							</button>
						</a>
					</div>';
				}

				echo '
				<div class="result-section">
					<p class="text-orange"><b>โอกาสเสี่ยงที่จะสูบบุหรี่แบบมวน</b></p>

					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center">
								<p><b>ผลการประเมิน</b></p>
								' . getEmujiB($b) . '
								<p style="color:#ff9900">' . getTestB($b) . '</p>
								' . $risk_button_a . '
							</div>
						</div>
					</div>
				</div>

				<div class="result-section">
					<p class="text-orange"><b>โอกาสเสี่ยงที่จะสูบไฟฟ้า</b></p>

					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center">
								<p><b>ผลการประเมิน</b></p>
								' . getEmujiB2($b) . '
								<p style="color:#ff9900">' . getTestB2($b) . '</p>
								' . $risk_button_e . '
								' . $history_b . '
							</div>
						</div>
					</div>
				</div>
				';

				$count_chk++;
			}


		$game_count = intval($query->queryDB_to_string(
	'SELECT COUNT(*) FROM game WHERE user_id=' . intval($user_id)
));

			$test = $query->queryDB_to_row(
				'SELECT * FROM game WHERE user_id=' . intval($user_id) . ' ORDER BY id DESC LIMIT 1'
			);

			if (!empty($test) && is_array($test)) {

				$game_button = '';
				$history_game = '';

				if (checkTestGame($test['score'])) {

					$game_button = '
					<div class="p-2">
						<a href="knowledge.php">
							<button class="bt_big bg-dark">
								<i class="fa fa-exclamation-circle pr-2"></i>
								แหล่งความรู้
							</button>
						</a>
					</div>';

				}

				if ($game_count > 1) {

					$history_game = '
					<div class="p-2 bt_history_wrap">
						<a href="history_game.php">
							<button class="bt_big bt_history_soft">
								<i class="fa fa-history"></i>
								ดูประวัติการทดสอบเกม
							</button>
						</a>
					</div>';

				}

				echo '
				<div class="result-section">
					<p class="text-orange"><b>ผลการทดสอบเกมตอบคำถาม</b></p>

					<div class="box_white">
						<div class="row">

							<div class="col-12 text-center">
								<p><b>คะแนนที่คุณทำได้</b></p>

								<h4 class="pt-2">
									' . intval($test['score']) . ' คะแนน
								</h4>

								<p style="color:#ff9900">
									' . getTestGame($test['score']) . '
								</p>
							</div>

							<div class="col-12 text-center">
								<small>
									' . getTextTestGame($test['score']) . '
								</small>
							</div>

							<div class="col-12">
								' . $game_button . '
								' . $history_game . '
							</div>

						</div>
					</div>
				</div>
				';

				$count_chk++;
			}

			function checkTestA($score)
			{
				return $score <= 5;
			}

			function getTestA($score)
			{
				if ($score <= 3) {
					return 'ไม่นับว่าคุณติดสารนิโคติน';
				} else if ($score <= 5) {
					return 'ระดับปานกลาง';
				} else if ($score <= 7) {
					return 'ระดับปานกลางและมีแนวโน้มอย่างมากในการพัฒนาไปเป็นระดับสูง';
				} else if ($score <= 9) {
					return 'ระดับสูง';
				} else {
					return 'ระดับสูงมาก';
				}
			}

			function getTextTestA($score)
			{
				if ($score <= 3) {
					return 'คุณสามารถเลิกสูบบุหรี่ได้ด้วยตนเอง หากเคยเลิกไม่สำเร็จ อาจใช้พฤติกรรมบำบัดช่วยเสริม';
				} else if ($score <= 5) {
					return 'ติดสารนิโคตินระดับปานกลาง ควรวางแผนลดหรือเลิกบุหรี่';
				} else if ($score <= 7) {
					return 'มีแนวโน้มติดสารนิโคตินสูง ควรเริ่มแผนเลิกบุหรี่อย่างจริงจัง';
				} else if ($score <= 9) {
					return 'ติดสารนิโคตินระดับสูง ควรพบแพทย์หรือผู้เชี่ยวชาญเพื่อรับคำปรึกษา';
				} else {
					return 'ติดสารนิโคตินระดับสูงมาก ควรเข้ารับคำปรึกษาและวางแผนเลิกบุหรี่อย่างเป็นระบบ';
				}
			}

			function getEmujiA($score)
			{
				if ($score <= 3) {
					return '<img src="img/emoji/1.png?v=2" height="120px">';
				} else if ($score <= 5) {
					return '<img src="img/emoji/3.png?v=2" height="120px">';
				} else if ($score <= 7) {
					return '<img src="img/emoji/5.png?v=2" height="120px">';
				} else if ($score <= 9) {
					return '<img src="img/emoji/7.png?v=2" height="120px">';
				} else {
					return '<img src="img/emoji/10.png?v=2" height="120px">';
				}
			}

			function getTestE($score)
			{
				if ($score < 3) {
					return 'ต่ำมาก';
				} else if ($score < 5) {
					return 'ต่ำ';
				} else if ($score < 7) {
					return 'ปานกลาง';
				} else if ($score < 9) {
					return 'สูง';
				} else {
					return 'สูงมาก';
				}
			}

			function getEmujiE($score)
			{
				if ($score < 3) {
					return '<img src="img/emoji/1.png?v=2" height="120px">';
				} else if ($score < 5) {
					return '<img src="img/emoji/3.png?v=2" height="120px">';
				} else if ($score < 7) {
					return '<img src="img/emoji/5.png?v=2" height="120px">';
				} else if ($score < 9) {
					return '<img src="img/emoji/7.png?v=2" height="120px">';
				} else {
					return '<img src="img/emoji/10.png?v=2" height="120px">';
				}
			}

			function checkRisk($data)
			{
				return !(($data['q1'] ?? '') == 4 && ($data['q2'] ?? '') == 4);
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
					return '<img src="img/emoji/1.png?v=2" height="120px">';
				} else {
					return '<img src="img/emoji/10.png?v=2" height="120px">';
				}
			}

			function checkRisk2($data)
			{
				return !(($data['q3'] ?? '') == 4 && ($data['q4'] ?? '') == 4 && ($data['q5'] ?? '') == 4);
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
					return '<img src="img/emoji/1.png?v=2" height="120px">';
				} else {
					return '<img src="img/emoji/10.png?v=2" height="120px">';
				}
			}

			function checkTestGame($score)
			{
				return $score <= 6;
			}

			function getTestGame($score)
			{
				if ($score < 6) {
					return 'ไม่ผ่านเกณฑ์';
				} else if ($score < 9) {
					return 'มีความรู้ปานกลาง';
				} else {
					return 'มีความรู้สูง';
				}
			}

			function getTextTestGame($score)
			{
				if ($score < 6) {
					return 'ควรศึกษาเนื้อหาเพิ่มเติมเกี่ยวกับบุหรี่และบุหรี่ไฟฟ้า';
				} else if ($score < 9) {
					return 'มีความรู้ระดับปานกลาง สามารถศึกษาเพิ่มเติมเพื่อพัฒนาคะแนนให้สูงขึ้น';
				} else {
					return 'มีความรู้เกี่ยวกับบุหรี่และบุหรี่ไฟฟ้าระดับสูง';
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

<script>
	$(function() {
		<?
		if ($count_chk == 0) {
			echo '
			Swal.fire({
				html: "ขออภัย ! <br> ท่านยังไม่ได้ทดสอบแบบสอบถาม !",
				type: "error",
				confirmButtonText: " ตกลง ",
				confirmButtonColor: "#3085d6",
				timer: 5000
			}).then((result) => {
				location.href = "home.php";
			});
			';
		}
		?>
	});
</script>

</html>