<?php
include('api/app.php');
$query = new DBControl();
chkLogin();

$user_id = isset($_COOKIE["user_id"]) ? intval($_COOKIE["user_id"]) : 0;

if ($user_id <= 0) {
	echo '<script>location.href="login.php";</script>';
	exit;
}

/*
	รับค่า aws จาก URL เช่น test.php?aws=1
	aws = 1 คือ สูบบุหรี่มวนสลับกับบุหรี่ไฟฟ้า / สูบทั้งสองอย่าง
*/
$aws = isset($_GET["aws"]) ? intval($_GET["aws"]) : 0;

/*
	กรณีเปิด test.php ตรง ๆ แล้วไม่มี aws ติดมา
	ให้ดึงค่าคัดกรองล่าสุดจากตาราง screening
*/
if ($aws <= 0) {
	$aws = intval($query->queryDB_to_string('
		SELECT aws 
		FROM screening 
		WHERE user_id = '.$user_id.' 
		ORDER BY `date` DESC 
		LIMIT 1
	'));
}
?>
<html>
<head>
	<?php
		getHead();
	?>
</head>
<body class="pages_1">

	<div class="top_menu">
        <div class="top_header2">แบบประเมิน<br>ระดับการติดสารนิโคติน</div>
		<div class="bt_back pointer" onclick="history.back()">
			<i class="fas fa-angle-left" aria-hidden="true"></i>
		</div>
	</div>

    <div class="main">
        <div class="container p-3">

			<p class="text-orange">
				<small>แบบทดสอบระดับการติดสารนิโคติน (Fagerstrom test for nicotine dependence)</small>
			</p>

			<form id="test_from">

				<!-- ส่งค่า aws ไปให้ api/test_a.php -->
				<input type="hidden" name="aws" value="<?php echo $aws; ?>">

				<div class="box_white">
					<p>1. โดยปกติคุณสูบบุหรี่กี่มวนต่อวัน</p>

					<label class="checkbox">
						10 มวน หรือน้อยกว่า
						<input type="radio" name="q1" value="1">
						<span class="checkmark"></span>
					</label>

					<label class="checkbox">
						11-20 มวน
						<input type="radio" name="q1" value="2">
						<span class="checkmark"></span>
					</label>

					<label class="checkbox">
						21-30 มวน
						<input type="radio" name="q1" value="3">
						<span class="checkmark"></span>
					</label>

					<label class="checkbox">
						มากกว่า 30 มวน
						<input type="radio" name="q1" value="4">
						<span class="checkmark"></span>
					</label>
				</div>

				<div class="box_white">
					<p>2. หลังตื่นนอนตอนเช้าคุณสูบบุหรี่มวนแรกเมื่อไหร่</p>

					<label class="checkbox">
						ภายใน 5 นาทีหลังตื่นนอน
						<input type="radio" name="q2" value="1">
						<span class="checkmark"></span>
					</label>

					<label class="checkbox">
						6-30 นาที หลังตื่นนอน
						<input type="radio" name="q2" value="2">
						<span class="checkmark"></span>
					</label>

					<label class="checkbox">
						31-60 นาที หลังตื่นนอน
						<input type="radio" name="q2" value="3">
						<span class="checkmark"></span>
					</label>

					<label class="checkbox">
						มากกว่า 60 นาทีหลังตื่น
						<input type="radio" name="q2" value="4">
						<span class="checkmark"></span>
					</label>
				</div>

				<div class="box_white">
					<p>3. คุณสูบบุหรี่จัดในชั่วโมงแรกหลังตื่นนอน</p>

					<label class="checkbox">
						ใช่
						<input type="radio" name="q3" value="1">
						<span class="checkmark"></span>
					</label>

					<label class="checkbox">
						ไม่ใช่
						<input type="radio" name="q3" value="2">
						<span class="checkmark"></span>
					</label>
				</div>

				<div class="box_white">
					<p>4. บุหรี่มวนไหนที่คุณไม่อยากเลิกมากที่สุด</p>

					<label class="checkbox">
						มวนแรกตอนเช้า
						<input type="radio" name="q4" value="1">
						<span class="checkmark"></span>
					</label>

					<label class="checkbox">
						มวนอื่น ๆ
						<input type="radio" name="q4" value="2">
						<span class="checkmark"></span>
					</label>
				</div>
				
				<div class="box_white">
					<p>5. คุณรู้สึกลำบากหรือยุ่งยากไหมที่ต้องอยู่ในที่ปลอดบุหรี่ เช่น โรงภาพยนตร์ รถโดยสาร ร้านอาหาร</p>

					<label class="checkbox">
						รู้สึกลำบาก
						<input type="radio" name="q5" value="1">
						<span class="checkmark"></span>
					</label>

					<label class="checkbox">
						ไม่รู้สึกลำบาก
						<input type="radio" name="q5" value="2">
						<span class="checkmark"></span>
					</label>
				</div>
				
				<div class="box_white">
					<p>6. คุณยังต้องสูบบุหรี่ แม้จะเจ็บป่วยนอนพักตลอดในโรงพยาบาล</p>

					<label class="checkbox">
						ใช่
						<input type="radio" name="q6" value="1">
						<span class="checkmark"></span>
					</label>

					<label class="checkbox">
						ไม่ใช่
						<input type="radio" name="q6" value="2">
						<span class="checkmark"></span>
					</label>
				</div>

			</form>
		
			<div class="p-2">
				<button id="sent" class="bt_big bg-dark">
					<i class="fa fa-send pr-2" aria-hidden="true"></i>ส่งผล
				</button>
				<div id="show_error"></div>
			</div>

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
$(function(){

	$("#sent").click(function(event){
		event.preventDefault();

		let complete = true;

		for (let i = 1; i <= 6; i++) {
			if ($("input[name='q" + i + "']:checked").length === 0) {
				complete = false;
				break;
			}
		}

		if (!complete) {
			Swal.fire({
				html: "กรุณาตอบแบบสอบถามให้ครบทุกข้อ",
				type: "error",
				confirmButtonText: "ตกลง",
				confirmButtonColor: "#3085d6"
			});
			return;
		}

		$("#sent").prop("disabled", true).html('<i class="fa fa-spinner fa-spin pr-2"></i>กำลังบันทึก');

		$.ajax({
			type: "POST",
			url: "api/test_a.php",
			data: $("#test_from").serialize(),
			success: function(result){
				$("#show_error").html(result);
			},
			error: function(){
				$("#sent").prop("disabled", false).html('<i class="fa fa-send pr-2"></i>ส่งผล');

				Swal.fire({
					html: "เกิดข้อผิดพลาดในการบันทึกข้อมูล",
					type: "error",
					confirmButtonText: "ตกลง",
					confirmButtonColor: "#3085d6"
				});
			}
		});		
	});

});
</script>
</html>