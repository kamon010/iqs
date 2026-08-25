<?php
	include('api/app.php');
	$query = new DBControl();
	chkLogin();

	$user_id = $_COOKIE["user_id"] ?? 0;
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
		<div class="bt_back pointer" onclick="history.back()">
			<i class="fas fa-angle-left" aria-hidden="true"></i>
		</div>
	</div>

	<div class="main">
		<div class="container p-3">
			<?php
				$test = $query->queryDB_to_row('SELECT * FROM test WHERE user_id=' . intval($user_id) . ' ORDER BY id DESC');

				if (!empty($test)) {

					$q1 = isset($test['q1']) ? floatval($test['q1']) : 0;
					$q2 = isset($test['q2']) ? floatval($test['q2']) : 0;

					$n = ($q2 / 20) * $q1;

					echo '
						<div class="box_white">
							<div class="row">
								<div class="col-12 text-center pt-3">
									<p>จำนวนเงินที่คุณเสียไป<b> ต่อเดือน</b><br>หากไม่เลิกสูบบุหรี่ตั้งแต่วันนี้</p>
									<h4 class="pt-2 text-orange">' . number_format($n * 30) . ' บาท</h4>
								</div>

								<div class="col-12 text-center py-3">
									<p>จำนวนเงินที่คุณเสียไป<b> ต่อปี</b><br>หากไม่เลิกสูบบุหรี่ตั้งแต่วันนี้</p>
									<h4 class="pt-2 text-orange">' . number_format($n * 365) . ' บาท</h4>
								</div>

								<div class="col-12 text-center py-3">
									<p>จำนวนเงินที่คุณเสียไปใน<b> 10 ปี</b><br>หากไม่เลิกสูบบุหรี่ตั้งแต่วันนี้</p>
									<h4 class="pt-2 text-orange">' . number_format($n * 3650) . ' บาท</h4>
								</div>
							</div>
						</div>
					';

					$chk = true;

					if ($chk) {
						echo '<div class="p-2"><a href="test_a.php"><button id="sent" class="bt_big bg-dark"><i class="fa fa-paper-plane pr-2" aria-hidden="true"></i>ประเมินต่อ</button></a></div>';
					}

				} else {
					echo '
						<script>
							Swal.fire({
								html: "ขออภัย ! <br> ท่านยังไม่ได้ทำแบบสอบถาม !",
								type: "error",
								confirmButtonText: " ตกลง ",
								confirmButtonColor: "#3085d6",
								timer: 5000
							}).then((result) => {
								location.href = "screening.php";
							});
						</script>
					';
				}

				function getTest($score){
					if($score <= 3){
						return 'ไม่นับว่าคุณติดสารนิโคติน';
					}
					else if($score <= 5){
						return 'ระดับปานกลาง';
					}
					else if($score <= 7){
						return 'ระดับปานกลางและมีแนวโน้มอย่างมากในการพัฒนาไปเป็นระดับสูง';
					}
					else if($score <= 9){
						return 'ระดับสูง';
					}
					else{
						return 'ระดับสูงมาก';
					}
				}

				function getTextTest($score){
					if($score <= 3){
						return 'ไม่นับว่าติดสารนิโคติน คุณสามารถเลิกสูบบุหรี่ได้ด้วยตนเอง หากคุณเคยล้มเหลว
						จากการพยายามเลิกด้วยตนเอง คุณอาจลองใช้พฤติกรรมบำบัดบางอย่างช่วยเสริม';
					}
					else if($score <= 5){
						return 'ติดสารนิโคตินในระดับปานกลาง คุณสามารถเลิกสูบบุหรี่ได้ด้วยตนเอง หากคุณเคยล้มเหลว
						จากการพยายามเลิกด้วยตนเอง คุณอาจลองใช้พฤติกรรมบำบัดบางอย่างช่วยเสริม';
					}
					else if($score <= 7){
						return 'ติดสารนิโคตินระดับปานกลาง และมีแนวโน้มอย่างมากในการพัฒนาไปเป็นการติด สาร
						นิโคตินในระดับสูง การเลิกสูบบุหรี่เสียตั้งแต่วันนี้ จะทำได้ง่ายกว่าการที่คุณจะยัง สูบต่อไป
						และจะมีแผนเลิกในอนาคต หากคุณเคยล้มเหลวจากการพยายามเลิกบุหรี่ ด้วยตนเอง หรือ
						ร่วมกับการใช้พฤติกรรมบำบัดเสริม คุณควรอย่างยิ่งที่จะไปพบแพทย์ 6-7 เพื่อรับคำปรึกษา
						เพื่อการเลิกสูบบุหรี่';
					}
					else if($score <= 9){
						return 'ติดสารนิโคตินในระดับสูง คุณควรใส่ใจและให้ความสำคัญกับสุขภาพของคุณ และเอาจริงเอา
						จังในการเลิกบุหรี่ เพื่อชีวิตที่ดีขึ้น สำหรับคุณการเลิกบุหรี่ด้วยตนเอง หรือการใช้แค่เพียง
						กำลังใจอย่าง เดียวอาจไม่เพียงพอในการรักษา หากคุณเคยล้มเหลวจากการพยายามเลิก
						บุหรี่ด้วย ตนเองหรือร่วมกับการใช้ พฤติกรรมบำบัดเสริม หรือวิธีอื่นๆ รวมทั้งการใช้แผ่น
						แปะ นิโคตินหรือหมากฝรั่งนิโคตินมาแล้ว กรณีนี้ท่านควรพบแพทย์เพื่อรับคำปรึกษาเพื่อ
						การเลิกบุหรี่';
					}
					else{
						return 'ติดสารนิโคตินในระดับสูงมาก คุณควรใส่ใจและให้ความสำคัญกับสุขภาพของคุณ และเอา
						จริงเอาจังในการเลิกบุหรี่ เพื่อชีวิตที่ดีขึ้น สำหรับคุณการเลิกบุหรี่ด้วยตนเอง หรือการใช้แค่
						เพียงกำลังใจอย่าง เดียวอาจไม่เพียงพอ หากคุณเคยล้มเหลวจากการพยายามเลิกบุหรี่ด้วย
						ตนเองหรือ ร่วมกับการใช้พฤติกรรมบำบัดเสริม หรือวิธีอื่นๆ รวมทั้งการใช้แผ่นแปะนิโคติน
						มาแล้ว กรณีนี้ท่านควรพบแพทย์เพื่อรับคำปรึกษา เพื่อการเลิกบุหรี่';
					}
				}

				function getEmuji($score){
					if($score <= 3){
						return '<img src="img/emoji/1.png" height="60px">';
					}
					else if($score <= 5){
						return '<img src="img/emoji/3.png" height="60px">';
					}
					else if($score <= 7){
						return '<img src="img/emoji/5.png" height="60px">';
					}
					else if($score <= 9){
						return '<img src="img/emoji/7.png" height="60px">';
					}
					else{
						return '<img src="img/emoji/10.png" height="60px">';
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
	$(function(){

	});
</script>
</html>