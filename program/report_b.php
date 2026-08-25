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
		<div class="top_header">ผลการประเมิน</div>

		<a href="home.php">
			<div class="bt_back">
				<i class="fas fa-angle-left" aria-hidden="true"></i>
			</div>
		</a>
	</div>

	<div class="main">
		<div class="container p-3">

			<?
			$data = $query->queryDB_to_row(
				'SELECT * FROM test_b WHERE user_id=' . intval($user_id) . ' ORDER BY id DESC LIMIT 1'
			);

			if (!empty($data) && is_array($data)) {

				$str = '';
				$str2 = '';

				$is_safe_cigarette =
				(
					($data['q1'] ?? '') == 4 &&
					($data['q2'] ?? '') == 4
				);

				$is_safe_ecig =
				(
					($data['q3'] ?? '') == 4 &&
					($data['q4'] ?? '') == 4 &&
					($data['q5'] ?? '') == 4
				);

				if (!$is_safe_cigarette) {

					$str = '
					<div class="p-2">
						<a href="knowledge.php">
							<button class="bt_big bg-dark">
								<i class="fa fa-exclamation-circle pr-2"></i>
								แหล่งความรู้
							</button>
						</a>
					</div>';

				}

				if (!$is_safe_ecig) {

					$str2 = '
					<div class="p-2">
						<a href="knowledge.php">
							<button class="bt_big bg-dark">
								<i class="fa fa-exclamation-circle pr-2"></i>
								แหล่งความรู้
							</button>
						</a>
					</div>';

				}

				echo '

				<p class="text-orange">
					<b>โอกาสเสี่ยงที่จะสูบบุหรี่แบบมวน</b>
				</p>

				<div class="box_white">

					<div class="row">

						<div class="col-12 text-center">

							<p><b>ผลการประเมิน</b></p>

							' . getEmuji($data) . '

							<p style="color:#ff9900">
								' . getTest($data) . '
							</p>

							' . $str . '

						</div>

					</div>

				</div>


				<p class="text-orange">
					<b>โอกาสเสี่ยงที่จะสูบบุหรี่ไฟฟ้า</b>
				</p>

				<div class="box_white">

					<div class="row">

						<div class="col-12 text-center">

							<p><b>ผลการประเมิน</b></p>

							' . getEmuji2($data) . '

							<p style="color:#ff9900">
								' . getTest2($data) . '
							</p>

							' . $str2 . '

						</div>

					</div>

				</div>

				';


				/*
				|--------------------------------------------------------------------------
				| เกียรติบัตร
				|--------------------------------------------------------------------------
				*/

				if ($is_safe_cigarette || $is_safe_ecig) {

					$user_data = $query->queryDB_to_row(
						'SELECT * FROM user WHERE user_id=' . intval($user_id)
					);

					$user_name = '';

					if (!empty($user_data) && is_array($user_data)) {
						$user_name = $user_data['user_name'] ?? '';
					}


					if ($is_safe_cigarette && $is_safe_ecig) {

						$certificate_text =
						'ไม่มีโอกาสเสี่ยงที่จะสูบบุหรี่แบบมวนและบุหรี่ไฟฟ้าในอนาคต';

						$certificate_type = 'มวนและไฟฟ้า';

					}
					else if ($is_safe_cigarette) {

						$certificate_text =
						'ไม่มีโอกาสเสี่ยงที่จะสูบบุหรี่แบบมวนในอนาคต';

						$certificate_type = 'มวน';

					}
					else {

						$certificate_text =
						'ไม่มีโอกาสเสี่ยงที่จะสูบบุหรี่ไฟฟ้าในอนาคต';

						$certificate_type = 'ไฟฟ้า';

					}


					echo '

					<div class="box_white">

						<div class="row">

							<div class="col-12 text-center">

								<img 
									src="img/logo.png" 
									height="100px"
								>

								<p>
									<b>ขอมอบเกียรติบัตรเพื่อแสดงว่า</b>
								</p>

								<h4 style="
									font-weight:bold;
									color:#004b73;
									padding-top:5px;
									padding-bottom:5px;
								">
									' . $user_name . '
								</h4>

								<p style="
									color:#ff9900;
									font-weight:bold;
								">
									' . $certificate_text . '
								</p>

								<p>
									จากการทดสอบประเมินโอกาสเสี่ยง<br>
									ที่จะสูบบุหรี่แบบมวนและบุหรี่ไฟฟ้า
								</p>

								<div class="pt-2">

									<a href="certificate.php?type=' . $certificate_type . '">

										<button class="bt_big bg-dark">
											<i class="fa fa-download pr-2"></i>
											ดาวน์โหลดเกียรติบัตร
										</button>

									</a>

								</div>

							</div>

						</div>

					</div>

					';

				}

			}
			else {

				echo '
				<script>

					Swal.fire({
						html: "ขออภัย ! <br> ท่านยังไม่ได้ทำแบบสอบถาม !",
						type: "error",
						confirmButtonText: "ตกลง",
						confirmButtonColor: "#3085d6",
						timer: 5000
					}).then((result) => {
						location.href = "screening.php";
					});

				</script>
				';

			}


			function getTest($data)
			{
				if (
					($data['q1'] ?? '') == 4 &&
					($data['q2'] ?? '') == 4
				) {

					return 'ไม่มีโอกาสเสี่ยงที่จะสูบบุหรี่แบบมวนในอนาคต';

				}
				else {

					return 'มีโอกาสเสี่ยงที่จะสูบบุหรี่แบบมวนในอนาคต';

				}
			}


			function getEmuji($data)
			{
				if (
					($data['q1'] ?? '') == 4 &&
					($data['q2'] ?? '') == 4
				) {

					return '
					<img 
						src="img/emoji/1.png?v=2" 
						height="100px"
					>';

				}
				else {

					return '
					<img 
						src="img/emoji/10.png?v=2" 
						height="100px"
					>';

				}
			}


			function getTest2($data)
			{
				if (
					($data['q3'] ?? '') == 4 &&
					($data['q4'] ?? '') == 4 &&
					($data['q5'] ?? '') == 4
				) {

					return 'ไม่มีโอกาสเสี่ยงที่จะสูบบุหรี่ไฟฟ้าในอนาคต';

				}
				else {

					return 'มีโอกาสเสี่ยงที่จะสูบบุหรี่ไฟฟ้าในอนาคต';

				}
			}


			function getEmuji2($data)
			{
				if (
					($data['q3'] ?? '') == 4 &&
					($data['q4'] ?? '') == 4 &&
					($data['q5'] ?? '') == 4
				) {

					return '
					<img 
						src="img/emoji/1.png?v=2" 
						height="100px"
					>';

				}
				else {

					return '
					<img 
						src="img/emoji/10.png?v=2" 
						height="100px"
					>';

				}
			}


			function getThaiMonth($month)
			{
				$thai_months = [
					'01' => 'มกราคม',
					'02' => 'กุมภาพันธ์',
					'03' => 'มีนาคม',
					'04' => 'เมษายน',
					'05' => 'พฤษภาคม',
					'06' => 'มิถุนายน',
					'07' => 'กรกฎาคม',
					'08' => 'สิงหาคม',
					'09' => 'กันยายน',
					'10' => 'ตุลาคม',
					'11' => 'พฤศจิกายน',
					'12' => 'ธันวาคม'
				];

				return $thai_months[$month];
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

	});
</script>

</html>