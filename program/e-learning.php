<?
include('api/app.php');
$query = new DBControl();
chkLogin();

$user_id = $_COOKIE["user_id"] ?? 0;

$chk = $query->queryDB_to_string('SELECT COUNT(elearning_id) FROM elearning WHERE user_id=' . $user_id);
$test = $_GET['test'] ?? '';

$data = $query->queryDB_to_array('SELECT * FROM elearning WHERE user_id=' . $user_id . ' ORDER BY elearning_id ASC');

if (empty($data) || !is_array($data)) {
	$data = array();
}

$chkwin = false;

if (!empty($data[0]) && isset($data[0]['score'])) {
	$s = $data[0]['score'];

	foreach ($data as $key => $val) {
		if (isset($val['score']) && $val['score'] > $s) {
			$chkwin = true;
		}
	}

	if ($chkwin) {
		echo '<script> location.href = "report_elearning.php"; </script>';
		exit;
	}
}
?>
<html>

<head>
	<?
	getHead();
	?>

	<style>
		.score{
			font-size:12px;
			color:#78909a;
			font-weight:400;
		}

		.elearning-test-nav{
			display:flex !important;
			align-items:center !important;
			justify-content:space-between !important;
			gap:12px !important;
			margin-bottom:14px !important;
		}

		.elearning-test-title{
			color:#005573 !important;
			font-size:15px !important;
			font-weight:800 !important;
			line-height:1.35 !important;
		}

		.elearning-test-actions{
			display:flex !important;
			align-items:center !important;
			gap:8px !important;
			flex-shrink:0 !important;
		}

		.elearning-test-btn{
			display:inline-flex !important;
			align-items:center !important;
			justify-content:center !important;
			gap:7px !important;
			min-height:40px !important;
			padding:9px 12px !important;
			border-radius:999px !important;
			font-size:14px !important;
			font-weight:800 !important;
			line-height:1 !important;
			text-decoration:none !important;
			box-shadow:0 8px 18px rgba(0,85,115,.14) !important;
			white-space:nowrap !important;
		}

		.elearning-test-btn-primary{
			background:linear-gradient(135deg,#20a6c1,#005573) !important;
			color:#ffffff !important;
		}

		.elearning-test-btn-secondary{
			background:#ffffff !important;
			border:1px solid #d9e7eb !important;
			color:#005573 !important;
		}

		@media (max-width: 360px){
			.elearning-test-nav{
				align-items:flex-start !important;
				flex-direction:column !important;
			}

			.elearning-test-actions{
				width:100% !important;
			}

			.elearning-test-btn{
				flex:1 !important;
			}
		}
	</style>
</head>

<body class="pages">

	<div class="top_menu">
		<div class="top_header">E-learning</div>
		<a href="home.php">
			<div class="bt_back"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
		</a>
	</div>

	<div class="main">

		<div class="container p-3" id="test">
			<?
			$showElearningActions = false;

			if ($test == 1) {
				$textt = 'ทดสอบหลังเรียน';
				$showElearningActions = true;
			} else {
				if ($chk == 0) {
					$textt = 'ทดสอบก่อนเรียน';
				} else {
					$textt = 'ทดสอบหลังเรียน';
					$showElearningActions = true;
				}
			}
			?>

			<div class="elearning-test-nav">
				<div class="elearning-test-title"><? echo $textt; ?></div>
				<? if ($showElearningActions) { ?>
					<div class="elearning-test-actions">
						<a href="video.php" class="elearning-test-btn elearning-test-btn-primary">
							<i class="fas fa-play-circle" aria-hidden="true"></i>
							<span>ดูคลิป</span>
						</a>
						<a href="history_elearning.php" class="elearning-test-btn elearning-test-btn-secondary">
							<i class="fas fa-history" aria-hidden="true"></i>
							<span>ดูประวัติ</span>
						</a>
					</div>
				<? } ?>
			</div>

			<form id="test_from">
				<div class="box_white">
					<p>1. ท่านรู้จักบุหรี่ไฟฟ้าไหม ? <br>ในรูปนี้ หมายเลขใดเป็นบุหรี่ไฟฟ้า <span class="score">(6 คะแนน)</span></p>
					<div class="pb-3">
						<img src="img/elearning.png" class="img">
					</div>
					<div class="row">
						<?
						for ($i = 1; $i <= 14; $i++) {
							echo '
							<div class="col-4">
								<label class="checkbox">
									' . $i . ' <input type="checkbox" name="q1_' . $i . '" value="1"><span class="checkmark"></span>
								</label>
							</div>';
						}
						?>
					</div>
				</div>

				<div class="box_white">
					<p>2. ผลิตภัณฑ์ของบุหรี่ไฟฟ้าที่ทำการตลาดล่าเหยื่อ เพื่อมุ่งเป้าล่อลวงเด็ก คือผลิตภัณฑ์ชนิดใด ? <span class="score">(1 คะแนน)</span></p>
					<label class="checkbox">ciga-like <input type="radio" name="q2" value="1"><span class="checkmark"></span></label>
					<label class="checkbox">e-cig pen <input type="radio" name="q2" value="2"><span class="checkmark"></span></label>
					<label class="checkbox">tank-mod <input type="radio" name="q2" value="3"><span class="checkmark"></span></label>
					<label class="checkbox">toy pod <input type="radio" name="q2" value="4"><span class="checkmark"></span></label>
				</div>

				<div class="box_white">
					<p>3. สารเสพติดในบุหรี่ไฟฟ้า คืออะไร ? <span class="score">(1 คะแนน)</span></p>
					<label class="checkbox">โพรพิลีน ไกลคอล <input type="radio" name="q3" value="1"><span class="checkmark"></span></label>
					<label class="checkbox">กลีเซอรีน <input type="radio" name="q3" value="2"><span class="checkmark"></span></label>
					<label class="checkbox">นิโคติน <input type="radio" name="q3" value="3"><span class="checkmark"></span></label>
					<label class="checkbox">เฮโรอีน <input type="radio" name="q3" value="4"><span class="checkmark"></span></label>
				</div>

				<div class="box_white">
					<p>4. นิโคตินในบุหรี่ไฟฟ้าเป็นชนิดใด <span class="score">(1 คะแนน)</span></p>
					<label class="checkbox">นิโคตินธรรมชาติ <input type="radio" name="q4" value="1"><span class="checkmark"></span></label>
					<label class="checkbox">นิโคตินสังเคราะห์ <input type="radio" name="q4" value="2"><span class="checkmark"></span></label>
					<label class="checkbox">นิโคตินบริสุทธิ์ <input type="radio" name="q4" value="3"><span class="checkmark"></span></label>
					<label class="checkbox">นิโคตินเจือจาง <input type="radio" name="q4" value="4"><span class="checkmark"></span></label>
				</div>

				<div class="box_white">
					<p>5. นิโคตินกระตุ้นสารสื่อประสาทสำคัญ ชื่อว่าอะไร <span class="score">(1 คะแนน)</span></p>
					<label class="checkbox">เอ็นโดรฟิน <input type="radio" name="q5" value="1"><span class="checkmark"></span></label>
					<label class="checkbox">กาบา <input type="radio" name="q5" value="2"><span class="checkmark"></span></label>
					<label class="checkbox">โดปามีน <input type="radio" name="q5" value="3"><span class="checkmark"></span></label>
					<label class="checkbox">เซโรโทนิน <input type="radio" name="q5" value="4"><span class="checkmark"></span></label>
				</div>

				<div class="box_white">
					<p>6. บุหรี่ไฟฟ้าช่วยให้เลิกบุหรี่มวน ใช่หรือไม่ <span class="score">(1 คะแนน)</span></p>
					<label class="checkbox">ช่วยได้ เพราะไม่มีควัน <input type="radio" name="q6" value="1"><span class="checkmark"></span></label>
					<label class="checkbox">ช่วยได้ เพราะไม่ระคายคอ <input type="radio" name="q6" value="2"><span class="checkmark"></span></label>
					<label class="checkbox">ไม่ช่วย เพราะไม่มีทาร์ <input type="radio" name="q6" value="3"><span class="checkmark"></span></label>
					<label class="checkbox">ไม่ช่วย เพราะยังเสพติดนิโคติน <input type="radio" name="q6" value="4"><span class="checkmark"></span></label>
				</div>

				<div class="box_white">
					<p>7. นิโคตินมีผลต่อสมองผู้ใหญ่อย่างไร <span class="score">(1 คะแนน)</span></p>
					<label class="checkbox">หลั่งเซโรโตนิน ทำให้อารมณ์ดี <input type="radio" name="q7" value="1"><span class="checkmark"></span></label>
					<label class="checkbox">หลั่งโดปามีนทำให้สมองทำงานดีขึ้น <input type="radio" name="q7" value="2"><span class="checkmark"></span></label>
					<label class="checkbox">เพิ่มรอยหยักในสมองมีความจำดีขึ้น <input type="radio" name="q7" value="3"><span class="checkmark"></span></label>
					<label class="checkbox">ทำให้หลอดเลือดในสมองตีบเลือดไปเลี้ยงสมองไม่พอ <input type="radio" name="q7" value="4"><span class="checkmark"></span></label>
				</div>

				<div class="box_white">
					<p>8. นิโคตินมีผลต่อสมองเด็กอย่างไร <span class="score">(1 คะแนน)</span></p>
					<label class="checkbox">ทำให้ควบคุมอารมณ์ได้ดี <input type="radio" name="q8" value="1"><span class="checkmark"></span></label>
					<label class="checkbox">ทำให้มีความจำดี <input type="radio" name="q8" value="2"><span class="checkmark"></span></label>
					<label class="checkbox">พูดจาโต้ตอบได้ฉะฉาน <input type="radio" name="q8" value="3"><span class="checkmark"></span></label>
					<label class="checkbox">ทำให้โง่ พัฒนาการทางสมองช้า <input type="radio" name="q8" value="4"><span class="checkmark"></span></label>
				</div>

				<div class="box_white">
					<p>9. บุหรี่ไฟฟ้าทำให้เกิดโรคเรื้อรังไม่ติดต่อคือโรคอะไร <span class="score">(1 คะแนน)</span></p>
					<label class="checkbox">เบาหวาน <input type="radio" name="q9" value="1"><span class="checkmark"></span></label>
					<label class="checkbox">ไทรอยด์เป็นพิษ<input type="radio" name="q9" value="2"><span class="checkmark"></span></label>
					<label class="checkbox">เบาจืด <input type="radio" name="q9" value="3"><span class="checkmark"></span></label>
					<label class="checkbox">เซ็กซ์เสื่อม <input type="radio" name="q9" value="4"><span class="checkmark"></span></label>
				</div>

				<div class="box_white">
					<p>10. บุหรี่ไฟฟ้าทำสมรรถภาพทางเพศเป็นอย่างไร <span class="score">(1 คะแนน)</span></p>
					<label class="checkbox">เซ็กซ์จัด <input type="radio" name="q10" value="1"><span class="checkmark"></span></label>
					<label class="checkbox">เซ็กซ์มีคุณภาพ <input type="radio" name="q10" value="2"><span class="checkmark"></span></label>
					<label class="checkbox">เซ็กซ์ปกติสุข <input type="radio" name="q10" value="3"><span class="checkmark"></span></label>
					<label class="checkbox">เซ็กซ์เสื่อม <input type="radio" name="q10" value="4"><span class="checkmark"></span></label>
				</div>

				<div class="box_white">
					<p>11. นิโคตินในบุหรี่/บุหรี่ไฟฟ้า ทำให้เป็นมะเร็งได้อย่างไร <span class="score">(1 คะแนน)</span></p>
					<label class="checkbox">หลั่งโดปามีน <input type="radio" name="q11" value="1"><span class="checkmark"></span></label>
					<label class="checkbox">ทำให้หลอดเลือดหดตัว <input type="radio" name="q11" value="2"><span class="checkmark"></span></label>
					<label class="checkbox">ทำให้เกิดการอักเสบ <input type="radio" name="q11" value="3"><span class="checkmark"></span></label>
					<label class="checkbox">สร้างอนุมูลอิสระ <input type="radio" name="q11" value="4"><span class="checkmark"></span></label>
				</div>

				<div class="box_white">
					<p>12. น้ำยาบุหรี่ไฟฟ้าทำให้เกิดโรคปอดอักเสบเฉียบพลัน เรียกว่าโรคอะไร <span class="score">(1 คะแนน)</span></p>
					<label class="checkbox">ปอดข้าวโพดคั่ว <input type="radio" name="q12" value="1"><span class="checkmark"></span></label>
					<label class="checkbox">อีวาลี่ <input type="radio" name="q12" value="2"><span class="checkmark"></span></label>
					<label class="checkbox">หืด <input type="radio" name="q12" value="3"><span class="checkmark"></span></label>
					<label class="checkbox">ถุงลมโป่งพอง <input type="radio" name="q12" value="4"><span class="checkmark"></span></label>
				</div>

				<div class="box_white">
					<p>13. ซินนามาลดีไฮด์ สิ่งปรุงแต่งรสและกลิ่นในบุหรี่ไฟฟ้า มีอันตรายอย่างไร <span class="score">(1 คะแนน)</span></p>
					<label class="checkbox">ทำให้ติดเชื้อในกระแสเลือด <input type="radio" name="q13" value="1"><span class="checkmark"></span></label>
					<label class="checkbox">ทำให้เกิดโรคมะเร็ง <input type="radio" name="q13" value="2"><span class="checkmark"></span></label>
					<label class="checkbox">ทำให้หลอดลมอักเสบ <input type="radio" name="q13" value="3"><span class="checkmark"></span></label>
					<label class="checkbox">ทำให้เกิดโรคหืด <input type="radio" name="q13" value="4"><span class="checkmark"></span></label>
				</div>

				<div class="box_white">
					<p>14. ผู้ที่ได้รับไอนิโคตินโดยที่ไม่ได้สูบเรียกว่าอะไร <span class="score">(1 คะแนน)</span></p>
					<label class="checkbox">บุหรี่มือหนึ่ง <input type="radio" name="q14" value="1"><span class="checkmark"></span></label>
					<label class="checkbox">บุหรี่มือสอง <input type="radio" name="q14" value="2"><span class="checkmark"></span></label>
					<label class="checkbox">บุหรี่มือสาม<input type="radio" name="q14" value="3"><span class="checkmark"></span></label>
					<label class="checkbox">บุหรี่มือสี่ <input type="radio" name="q14" value="4"><span class="checkmark"></span></label>
				</div>

				<div class="box_white">
					<p>15. ผู้ที่ได้รับสารนิโคตินตกค้างตามพื้น ประตู ผนัง เรียกว่าอะไร <span class="score">(1 คะแนน)</span></p>
					<label class="checkbox">บุหรี่มือหนึ่ง<input type="radio" name="q15" value="1"><span class="checkmark"></span></label>
					<label class="checkbox">บุหรี่มือสอง <input type="radio" name="q15" value="2"><span class="checkmark"></span></label>
					<label class="checkbox">บุหรี่มือสาม <input type="radio" name="q15" value="3"><span class="checkmark"></span></label>
					<label class="checkbox">บุหรี่มือสี่ <input type="radio" name="q15" value="4"><span class="checkmark"></span></label>
				</div>

				<div class="box_white">
					<p>16. บุหรี่ไฟฟ้า ผิดกฎหมายหรือไม่ <span class="score">(1 คะแนน)</span></p>
					<label class="checkbox">สูบบุหรี่ไฟฟ้าไม่ผิด <input type="radio" name="q16" value="1"><span class="checkmark"></span></label>
					<label class="checkbox">ไม่ผิด ถ้าไม่ใช่เด็กและเยาวชน <input type="radio" name="q16" value="2"><span class="checkmark"></span></label>
					<label class="checkbox">ผิดกฎหมาย ถ้าสูบบนท้องถนนผิดกฎหมายจราจร <input type="radio" name="q16" value="3"><span class="checkmark"></span></label>
					<label class="checkbox">ผิดกฎหมาย ห้ามนำเข้า ห้ามจำหน่าย ห้ามครอบครอง <input type="radio" name="q16" value="4"><span class="checkmark"></span></label>
				</div>

				<div class="box_white">
					<p>17. หากพบเจอการขายบุหรี่ผิดกฎหมาย แจ้ง สคบ. หมายเลขอะไร <span class="score">(1 คะแนน)</span></p>
					<label class="checkbox">191<input type="radio" name="q17" value="1"><span class="checkmark"></span></label>
					<label class="checkbox">1600 <input type="radio" name="q17" value="2"><span class="checkmark"></span></label>
					<label class="checkbox">1166 <input type="radio" name="q17" value="3"><span class="checkmark"></span></label>
					<label class="checkbox">1567 <input type="radio" name="q17" value="4"><span class="checkmark"></span></label>
				</div>

			</form>

			<div class="p-2">
				<button id="sent" class="bt_big bg-dark"><i class="fa fa-send pr-2" aria-hidden="true"></i>ส่งผล</button>
				<div id="show_error"></div>
			</div>
		</div>
	</div>

	<div class="footer">
		<div class="container">

			<a href="#">
				<div class="menu menu-3 text-left">
					<i class="fa fa-chevron-left" aria-hidden="true"></i>
				</div>
			</a>

			<a href="home.php">
				<div class="menu menu-3m active">
					<i class="fa fa-home" aria-hidden="true"></i>
				</div>
			</a>

			<a href="#">
				<div class="menu menu-3 text-right">
					<i class="fa fa-chevron-right" aria-hidden="true"></i>
				</div>
			</a>

		</div>
	</div>

</body>
<script>
	$(function() {
		$("#sent").click(function(event) {
			event.preventDefault();
			$.ajax({
				type: "POST",
				url: "api/e-learning.php",
				data: $("#test_from").serialize(),
				success: function(result) {
					$("#show_error").html(result);
				}
			});
		});
	});
</script>

</html>
