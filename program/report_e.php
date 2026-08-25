<?
include('api/app.php');
$query = new DBControl();
chkLogin();

$user_id = $_COOKIE["user_id"];

?>
<html>

<head>
	<?
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

		<?

		$sql = 'SELECT aws FROM screening 
				WHERE user_id=' . $user_id . ' 
				ORDER BY id DESC';

		$aws = $query->queryDB_to_string($sql);

		$str = '';

		if ($aws == '1') {

			echo '<div class="container p-3">';
			echo '<div class="text-center" style="color:#ff9900">
					ผลการประเมินการสูบบุหรี่แบบมวน
				  </div>';

			$a = $query->queryDB_to_row(
				'SELECT * FROM test_a 
				 WHERE user_id=' . $user_id . '
				 ORDER BY id DESC'
			);

			if (!empty($a)) {

				if (checkTest($a['score'])) {

					$str = '
					<div class="p-2">
						<a href="howto_1.php">
							<button id="sent" class="bt_big bg-dark">
								<i class="fa fa-exclamation-circle pr-2"></i>
								วิธีเลิกบุหรี่
							</button>
						</a>
					</div>';
				} else {

					$str = '
					<div class="p-2">
						<a href="https://www.thailandquitline.or.th/site/" target="_blank">
							<button id="sent" class="bt_big bg-dark">
								<i class="fa fa-exclamation-circle pr-2"></i>
								Quitline 1600
							</button>
						</a>
					</div>';
				}

				echo '
				<div class="box_white">
					<div class="row">

						<div class="col-12 text-center">

							<p>
								<b>คุณติดสารนิโคติน</b>
							</p>

							<div class="pb-2">
								' . getEmuji($a['score']) . '
							</div>

							<div style="
								color:#ff9900;
								font-size:22px;
								font-weight:bold;
								padding-bottom:10px;
							">
								' . getTest($a['score']) . '
							</div>

							<h4 class="pt-2">
								' . $a['score'] . ' คะแนน
							</h4>

						</div>

						<div class="col-12 text-left">
							<b>คำแนะนำ</b><br>

							<small>
								' . getTextTest($a['score']) . '
							</small>
						</div>

						<div class="col-12">
							' . $str . '
						</div>

					</div>
				</div>
				';
			} else {

				echo '
				<script>

					Swal.fire({
						html: "ขออภัย ! <br> ท่านยังไม่ได้ทำแบบสอบถาม !",
						type: "error",
						confirmButtonText: "ตกลง",
						confirmButtonColor: "#3085d6"

					}).then((result) => {

						location.href = "screening.php";

					});

				</script>
				';
			}

			echo '</div>';
		}

		?>

		<div class="container p-3">

			<?

			$data = $query->queryDB_to_row(
				'SELECT * FROM test_e 
				 WHERE user_id=' . $user_id . '
				 ORDER BY id DESC'
			);

			if (!empty($data)) {

				echo '
				<div class="box_white">

					<div class="row">

						<div class="col-12 text-center">

							<p>
								<b>
									ผลการประเมิน
								</b>
							</p>
							<p>
								<b>
									การติดนิโคตินจากบุหรี่ไฟฟ้า
								</b>
							</p>

							<div class="pb-2">
								' . getEmujia($data) . '
							</div>

							<div style="
								color:#ff9900;
								font-size:22px;
								font-weight:bold;
								padding-bottom:10px;
							">
								' . getTesta($data) . '
							</div>

							<p>
								' . checkRisk($data) . '
							</p>

							' . $str . '

						</div>

					</div>

				</div>
				';
			} else {

				echo '
				<script>

					Swal.fire({
						html: "ขออภัย ! <br> ท่านยังไม่ได้ทำแบบประเมินบุหรี่ไฟฟ้า !",
						type: "error",
						confirmButtonText: "ตกลง",
						confirmButtonColor: "#3085d6"
					});

				</script>
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
					<i class="fa fa-home" aria-hidden="true"></i>
				</div>

			</a>

			<div class="menu menu-3 text-left"></div>

		</div>

	</div>

</body>

<script>
	$(function() {

	});
</script>

</html>

<?

function checkTest($score)
{
	return ($score <= 5);
}

function getTest($score)
{
	if ($score <= 3) {
		return 'ไม่นับว่าคุณติดสารนิโคติน';
	} else if ($score <= 5) {
		return 'ระดับปานกลาง';
	} else if ($score <= 7) {
		return 'ระดับปานกลางและมีแนวโน้มสูง';
	} else if ($score <= 9) {
		return 'ระดับสูง';
	} else {
		return 'ระดับสูงมาก';
	}
}

function getTextTest($score)
{
	if ($score <= 3) {

		return 'คุณสามารถเลิกสูบบุหรี่ได้ด้วยตนเอง';
	} else if ($score <= 5) {

		return 'ติดสารนิโคตินระดับปานกลาง';
	} else if ($score <= 7) {

		return 'ควรเริ่มวางแผนเลิกบุหรี่โดยเร็ว';
	} else if ($score <= 9) {

		return 'ควรพบแพทย์เพื่อรับคำปรึกษา';
	} else {

		return 'ควรเข้ารับการรักษาและพบแพทย์';
	}
}

function getEmuji($score)
{
	if ($score <= 3) {

		return '<img src="img/emoji/3.png" height="60px">';
	} else if ($score <= 5) {

		return '<img src="img/emoji/5.png" height="60px">';
	} else if ($score <= 7) {

		return '<img src="img/emoji/7.png" height="60px">';
	} else if ($score <= 9) {

		return '<img src="img/emoji/10.png" height="60px">';
	} else {

		return '<img src="img/emoji/10.png" height="60px">';
	}
}

function checkRisk($data)
{
	$total =
		$data['q1'] +
		$data['q2'] +
		$data['q3'] +
		$data['q4'] +
		$data['q5'] +
		$data['q6'];

	return '
	<div style="line-height:1.8;">
		การแปลผลคะแนนรวมของท่านคือ
		<br>
		<span style="font-size:18px;font-weight:bold;color:#005573;">
			'.$total.' คะแนน
		</span>
	</div>
';
}

function getTesta($data)
{
	$total =
		$data['q1'] +
		$data['q2'] +
		$data['q3'] +
		$data['q4'] +
		$data['q5'] +
		$data['q6'];

	if ($total < 3) {

		return 'ต่ำมาก';
	} else if ($total < 5) {

		return 'ต่ำ';
	} else if ($total < 7) {

		return 'ปานกลาง';
	} else if ($total < 9) {

		return 'สูง';
	} else {

		return 'สูงมาก';
	}
}

function getEmujia($data)
{
	$total =
		$data['q1'] +
		$data['q2'] +
		$data['q3'] +
		$data['q4'] +
		$data['q5'] +
		$data['q6'];

	if ($total < 3) {

		return '<img src="img/emoji/3.png" height="60px">';
	} else if ($total < 5) {

		return '<img src="img/emoji/5.png" height="60px">';
	} else if ($total < 7) {

		return '<img src="img/emoji/7.png" height="60px">';
	} else if ($total < 9) {

		return '<img src="img/emoji/10.png" height="60px">';
	} else {

		return '<img src="img/emoji/10.png" height="60px">';
	}
}

?>