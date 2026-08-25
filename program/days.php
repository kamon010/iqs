<?
include('api/app.php');

$query = new DBControl();
chkLogin();

$user_id = isset($_COOKIE["user_id"]) ? intval($_COOKIE["user_id"]) : 0;

if ($user_id <= 0) {
	echo '<script>location.href = "login.php";</script>';
	exit;
}

$date = date('Y-m-d');

/*
	เช็กว่าวันนี้ user คนนี้เคยบันทึกจำนวนบุหรี่แล้วหรือยัง
	ถ้าเคยบันทึกแล้ว:
	- ไม่ให้กรอกซ้ำ
	- แต่ยังให้กดลูกศรดูหน้าให้กำลังใจ n=1,2,3,4 ได้
*/
$today_record = $query->queryDB_to_row(
	'SELECT `day`, `date`, `n`
	 FROM `day`
	 WHERE `user_id` = '.$user_id.'
	 AND `date` = "'.$date.'"
	 ORDER BY `id` DESC
	 LIMIT 1'
);

$already_saved_today = false;

if (!empty($today_record) && isset($today_record['day'])) {
	$day = intval($today_record['day']);
	$chk_input = false;
	$chk = 1;
	$already_saved_today = true;
} else {
	$day = isset($_GET['d']) ? intval($_GET['d']) : 1;
	$chk_input = true;
	$chk = isset($_GET['chk']) ? intval($_GET['chk']) : 0;
}

if ($day <= 0) {
	$day = 1;
}

$d = $day % 7;

if ($d == 0) {
	$d = 7;
}

$maxList = array(
	1 => 4,
	2 => 3,
	3 => 4,
	4 => 3,
	5 => 4,
	6 => 4,
	7 => 4
);

$max = isset($maxList[$d]) ? intval($maxList[$d]) : 4;

$chk_chart = false;
$scr_new = false;
$test = '';
$str = '';

/*
	สำคัญ:
	ถ้าเคยบันทึกแล้ว แต่มีการกดลูกศร ?n=1, ?n=2 ให้ใช้ค่าหน้านั้น
	ถ้าเคยบันทึกแล้ว แต่ไม่มี n ให้เริ่มที่หน้าสรุปผล หรือหน้าสุดท้าย
	ถ้ายังไม่เคยบันทึก ให้เริ่มที่หน้า 1
*/
if (isset($_GET['n']) && $_GET['n'] !== '') {
	$n = intval($_GET['n']);
} else {
	$n = $already_saved_today ? $max : 1;
}

if ($n < 1) {
	$n = 1;
}

if ($n > $max) {
	$n = $max;
}
?>
<html>
<head>
	<?php
		getHead();
	?>
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body class="pages days-modern">

	<div class="top_menu">
        <div class="top_header">เลิกบุหรี่วันที่ <?php echo $day; ?></div>
		<a href="home.php">
			<div class="bt_back"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
		</a>
	</div>

    <div class="main">
        <div class="container p-3">
			<?php 
			$scr_new = false;
			$test = '';
			// ============================== 1 ==============================
			if($n==1){ 
				if($d==1){ 
					echo '
					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเช้า</h2>
							</div>
							<div class="col-12 text-center py-3">
								<h1 class="pt-2 text-orange">วันนี้</h1>
								<h3>เป็นวันเลิกสูบบุหรี่วันที่ '.$day.'</h3>
							</div>
							<div class="col-12 text-center py-3">
								<h4>หยุดสูบบุหรี่ '.$day.' วันดีอย่างไร</h4>
								<h4>คุณจะรู้สึกสดชื่นขึ้น</h4>
								
								<table class="table">
									<tr>
										<td>20 นาที</td>
										<td>ความดันโลหิตและชีพจรคุณจะเริ่มกลับสู่ปกต</td>
									</tr>
									<tr>
										<td>8 ชั่วโมง</td><td>ระดับออกซิเจนในเลือดกลับสู่ปกติ <br>นิโคตินและคาร์บอนมอนอกไซด์จะลดระดับลงมากกว่าครึ่ง</td>
									</tr>
									<tr>
										<td>24 ชั่วโมง</td><td>คาร์บอนมอนอกไซด์ถูกกำจัดออกจากร่างกาย  <br>ปอดจะเริ่มกำจัดเสมหะและคราบสิ่งสกปรกจากบุหรี <br> คุณจะรู้สึกตัวเองสะอาดขึ้น หายใจเต็มปอด และสมองทำงานดีขึ้น</td>
									</tr>
								</table>   
	
							</div>
						</div>
					</div>
					';
				}
				else if($d==2){
					echo '
					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเช้า</h2>
							</div>
							<div class="col-12 text-center py-3">
								<h3><b>วันนี้</b> เป็นวันเลิกสูบบุหรี่<br>วันที่ '.$day.'</h3>
							</div>
							<div class="col-12 text-center py-3">
								<h4>เมื่อคุณหยุดสูบบุหรี่ได้ครบ '.$day.' วัน</h4>
								<h4>หรือ '.($day*24).' ชั่วโมง</h4><br><br>
								<h5>ร่างกายจะไม่มีนิโคตินหลงเหลืออยู่</h5>
								<h5>ความสามารถในการรับรสและกลิ่นจะดีขึ้นมาก</h5>

							</div>
						</div>
					</div>
					';
				}
				else if($d==3){
					echo '
					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเช้า</h2>
							</div>
							<div class="col-12 text-center py-3">
								<h3><b>วันนี้</b> เป็นวันเลิกสูบบุหรี่<br>วันที่ '.$day.'</h3>
							</div>
							<div class="col-12 text-center py-3">
								<h4>เมื่อคุณหยุดสูบบุหรี่ได้ครบ '.$day.' วัน</h4>
								<h4>หรือ '.($day*24).' ชั่วโมง</h4><br><br>
								<h5>ร่างกายจะไม่มีนิโคตินหลงเหลืออยู่</h5>
								<h5>ความสามารถในการรับรสและกลิ่นจะดีขึ้นมาก</h5>

							</div>
						</div>
					</div>
					';
				}
				else if($d==4){
					echo '
					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเช้า</h2>
							</div>
							<div class="col-12 text-center py-3">
								<h3 class="pt-2">คุณจะผ่านวันนี้ไปได้</h3>
								<p>หากคุณมีอาการอยากสูบบุหรี่และบุหรี่ไฟฟ้า</p>
								<p>IQS แนะนำให้ใช้ 5 ตัวช่วย</p>
							</div>
							<div class="col-12 text-center py-1">
								<h4 class="pt-2">คุณต้องใจแข็ง</h4>
								<p><small>แม้มีความคิดอยากสูบก็ต้องแน่วแน่กับสัญญาที่ทำไว้กับตนเองและคนที่คุณรัก</small></p>
							</div>
							<div class="col-12 text-center py-1">
								<h4 class="pt-2">ดื่มน้ำให้มาก</h4>
								<p><small>ดื่มน้ำให้มาก มากกว่า 2 ลิตร จะช่วยให้ร่างกายขับสารพิษออกจากร่างกาย</small></p>
							</div>
							<div class="col-12 text-center py-1">
								<h4 class="pt-2">หากิจกรรมอื่นทำ</h4>
								<p><small>เพื่อดึงดูความสนใจของคุณ เช่น ออกกำลังกาย เล่มเกม ดูซีรี่</small></p>
							</div>
							<div class="col-12 text-center py-1">
								<h4 class="pt-2">เคี้ยวหมากฝรั่ง หรืออมลูกอม</h4>
								<p><small>เมื่ออยากสูบบุหรี่ก็ให้รีบเคี้ยวหมากฝรั่งหรืออมลูกอมจะช่วยลดความรู้สึกอยากสูบบุหรี่ลง</small></p>
							</div>
							<div class="col-12 text-center py-1">
								<h4 class="pt-2">อมและเคี้ยวเนื้อและเปลือกมะนาว</h4>
								<p><small>วิตามินซีในมะนาวจะช่วยเปลี่ยนรสชาติ ของบุหรี่ให้เฝื่อน ทำให้ไม่อยากสูบบุหรี่และลดอาการอยากนิโคตินได้ อีกทั้งยังช่วยขจัดคราบบุหรี่ได้อีกด้วย</small></p>
							</div>
						</div>
					</div>
					';
				}
				else if($d==5){
					echo '
					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเช้า</h2>
							</div>
							<div class="col-12 text-center py-3">
								<h1>ไชโย</h1>
								<h3>คุณเลิกสูบบุหรี่<br>ติดต่อเป็นวันที่ '.$day.'</h3>
							</div>
							<div class="col-12 text-center py-3">
								<p>หากคุณสามารถเลิกบุหรี่<br>ได้ต่อไปครบ '.$day.' วัน</p>
								<p>คุณจะรู้สึก......</p><br><br>
								<p>ตัวเบาขึ้น</p>
								<p>สมองปลอดโปร่ง</p>
								<p>ความสามารถในการรับรสและกลิ่น<br>จะดีขึ้นมาก</p>
								<p>หายใจคล่องขึ้น หลอดลมเริ่มผ่อนคลาย<br>และมีกำลังเพิ่มขึ้น</p>

							</div>
						</div>
					</div>
					';
				}
				else if($d==6){
					echo '
					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเช้า</h2>
							</div>
							<div class="col-12 text-center py-3">
								<h3>เราภูมิใจในตัวคุณ </3>
							</div>
							<div class="col-12 text-center py-3">
								<h3><b>วันนี้</b> เป็นวันเลิกสูบบุหรี่ <br>วันที่ '.$day.'</h3>
							</div>
							<div class="col-12 text-center py-3">
								<p>หากคุณสามารถเลิกบุหรี่<br>ได้ต่อไปครบ '.$day.' วัน</p>
								<p>คุณจะรู้สึก......</p><br><br>
								<p>ตัวเบาขึ้น</p>
								<p>สมองปลอดโปร่ง</p>
								<p>หายใจคล่องขึ้น หลอดลมเริ่มผ่อนคลาย<br>มีกำลังเพิ่มขึ้น</p>
							</div>
						</div>
					</div>
					';
				}
				else if($d==7){
					echo '
					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเช้า</h2>
							</div>
							<div class="col-12 text-center py-3">
								<h3>ทุกคนภูมิใจในตัวคุณมาก </h3>
							</div>
							<div class="col-12 text-center py-3">
							 	<h4>ครบ 1 สัปดาห์ที่คุณเลิกสูบบุหรี่</h4>
								<p>คุณจะรู้สึก......</p><br><br>
								<p>ตัวเบาขึ้น</p>
								<p>สมองปลอดโปร่ง</p>
								<p>ความสามารถในการรับรสและกลิ่น</p>
								<p>มีกำลังเพิ่มขึ้น</p>
								<p>รู้สึกภูมิใจในตัวเองเพิ่มขึ้น<br>คุณจะรักตัวเองมากขึ้น<br>และคุณจะรู้สึกเชื่อมั่น<br>ในศักยภาพของตัวเองมากขึ้น</p>

							</div>
						</div>
					</div>
					';
				}
			}
			// ============================== 2 ==============================
			else if($n==2){ 
				if($d==1){
					echo '
					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเช้า</h2>
							</div>
							<div class="col-12 text-center py-3">
								<h2 class="pt-2 text-orange">คุณจะผ่านวันนี้ไปได้</h2>
								<p>หากคุณมีอาการอยากสูบบุหรี่และบุหรี่ไฟฟ้า</p>
							</div>
							<div class="col-12 text-center py-3">
								<h2>IQS มี 3 ตัวช่วยให้คุณ </h2>  
	
							</div>
						</div>
					</div>
					';
				}
				else if($d==2){
					echo '
					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเช้า</h2>
							</div>
							<div class="col-12 text-center py-3">
								<h3 class="pt-2">คุณต้องใจแข็ง</h3>
								<p>
									แม้มีความคิดอยากสูบก็ต้องแน่วแน่กับสัญญาที่ทำไว้กับตนเองและคนที่คุณรัก <br>
									คิดถึงข้อดีของการไม่สูบบุหรี่ครบ 1 วัน เพื่ออนาคตของคุณเอง
								</p>
							</div>
							<div class="col-12 text-center py-3">
								<h3 class="pt-2">ดื่มน้ำให้มาก</h3>
								<p>ดื่มน้ำให้มาก มากกว่า 2 ลิตร จะช่วยให้ร่างกายขับสารพิษออกจากร่างกาย</p>
							</div>
							<div class="col-12 text-center py-3">
								<h3 class="pt-2">หากิจกรรมอื่นทำ</h3>
								<p>เพื่อดึงดูความสนใจของคุณ เช่น ออกกำลังกาย เล่มเกม ดูซีรี่</p>
							</div>
							<div class="col-12 text-center py-3">
								<h3 class="pt-2">เคี้ยวหมากฝรั่ง หรืออมลูกอม</h3>
								<p>
									จะทำให้เพลิดเพลินจนลืมนึกถึงบุหรี่ <br>
									และเมื่ออยากสูบบุหรี่ก็ให้รีบเคี้ยวหมากฝรั่งหรืออมลูกอม<br>
									จะช่วยลดความรู้สึกอยากสูบบุหรี่ลง
								</p>
							</div>
						</div>
					</div>
					';
				}
				else if($d==3){
					echo '
					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเช้า</h2>
							</div>
							<div class="col-12 text-center py-3">
								<h3 class="pt-2">คุณจะผ่านวันนี้ไปได้</h3>
								<p>หากคุณมีอาการอยากสูบบุหรี่และบุหรี่ไฟฟ้า</p>
							</div>
							<div class="col-12 text-center py-3">
								<h3 class="pt-2">ดื่มน้ำให้มาก</h3>
								<p>ดื่มน้ำให้มาก มากกว่า 2 ลิตร จะช่วยให้ร่างกายขับสารพิษออกจากร่างกาย</p>
							</div>
							<div class="col-12 text-center py-3">
								<h4 class="pt-2">นอกจาก 4 ตัวช่วยเดิมแล้ว</h4>
								<h4 class="pt-2">วันนี้ IQS มีตัวช่วยพิเศษให้คุณ</h4>
							</div>
						</div>
					</div>
					';
				}
				else if($d==4){
					echo '
					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเช้า</h2>
							</div>
							<div class="col-12 text-center py-3">
								<p>
									ความเชื่อผิดๆ เกี่ยวกับ <br> “การเลิกบุหรี่” <br>
									การเลิกบุหรี่..แล้วทำให้ร่างกายอ่อนแอลง <br>
									การเลิกบุหรี่ไม่ได้ส่งผลอันตรายต่อร่างกาย <br>
									มีเพียงแค่อาการที่เกิดขึ้นหลังอดบุหรี่ <br>
									แบบเฉียบพลันเท่านั้น <br>
									เช่น รู้สึกกระสับกระส่าย หงุดหงิดง่าย <br>
									อาการเหล่านี้จะคงอยู่ประมาณ 2 สัปดาห์แรก <br>
									ก็จะค่อยๆ ทุเลาลง  <br>
									ขอให้คุณมั่นใจว่ามาถูกทางแล้ว <br>
									และจะส่งผลดีในระยะยาวต่อตัวคุณ <br>
									และคนที่คุณรัก
								</p>
							</div>
						</div>
					</div>
					';
				}
				else if($d==5){
					echo '
					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเช้า</h2>
							</div>
							<div class="col-12 text-center py-3">
								<h3 class="pt-2">คุณจะผ่านวันนี้ไปได้</h3>
								<p>หากคุณมีอาการอยากสูบบุหรี่และบุหรี่ไฟฟ้า</p>
								<p>IQS แนะนำให้ใช้ 5 ตัวช่วย</p>
							</div>
							<div class="col-12 text-center py-1">
								<h4 class="pt-2">คุณต้องใจแข็ง</h4>
								<p><small>แม้มีความคิดอยากสูบก็ต้องแน่วแน่กับสัญญาที่ทำไว้กับตนเองและคนที่คุณรัก</small></p>
							</div>
							<div class="col-12 text-center py-1">
								<h4 class="pt-2">ดื่มน้ำให้มาก</h4>
								<p><small>ดื่มน้ำให้มาก มากกว่า 2 ลิตร จะช่วยให้ร่างกายขับสารพิษออกจากร่างกาย</small></p>
							</div>
							<div class="col-12 text-center py-1">
								<h4 class="pt-2">หากิจกรรมอื่นทำ</h4>
								<p><small>เพื่อดึงดูความสนใจของคุณ เช่น ออกกำลังกาย เล่มเกม ดูซีรี่</small></p>
							</div>
							<div class="col-12 text-center py-1">
								<h4 class="pt-2">เคี้ยวหมากฝรั่ง หรืออมลูกอม</h4>
								<p><small>เมื่ออยากสูบบุหรี่ก็ให้รีบเคี้ยวหมากฝรั่งหรืออมลูกอมจะช่วยลดความรู้สึกอยากสูบบุหรี่ลง</small></p>
							</div>
							<div class="col-12 text-center py-1">
								<h4 class="pt-2">อมและเคี้ยวเนื้อและเปลือกมะนาว</h4>
								<p><small>วิตามินซีในมะนาวจะช่วยเปลี่ยนรสชาติ ของบุหรี่ให้เฝื่อน ทำให้ไม่อยากสูบบุหรี่และลดอาการอยากนิโคตินได้ อีกทั้งยังช่วยขจัดคราบบุหรี่ได้อีกด้วย</small></p>
							</div>
						</div>
					</div>
					';
				}
				else if($d==6){
					echo '
					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเช้า</h2>
							</div>
							<div class="col-12 text-center py-3">
								<h3 class="pt-2">คุณจะผ่านวันนี้ไปได้</h3>
								<p>หากคุณมีอาการอยากสูบบุหรี่และบุหรี่ไฟฟ้า</p>
								<p>IQS แนะนำให้ใช้ 5 ตัวช่วย</p>
							</div>
							<div class="col-12 text-center py-1">
								<h4 class="pt-2">คุณต้องใจแข็ง</h4>
								<p><small>แม้มีความคิดอยากสูบก็ต้องแน่วแน่กับสัญญาที่ทำไว้กับตนเองและคนที่คุณรัก</small></p>
							</div>
							<div class="col-12 text-center py-1">
								<h4 class="pt-2">ดื่มน้ำให้มาก</h4>
								<p><small>ดื่มน้ำให้มาก มากกว่า 2 ลิตร จะช่วยให้ร่างกายขับสารพิษออกจากร่างกาย</small></p>
							</div>
							<div class="col-12 text-center py-1">
								<h4 class="pt-2">หากิจกรรมอื่นทำ</h4>
								<p><small>เพื่อดึงดูความสนใจของคุณ เช่น ออกกำลังกาย เล่มเกม ดูซีรี่</small></p>
							</div>
							<div class="col-12 text-center py-1">
								<h4 class="pt-2">เคี้ยวหมากฝรั่ง หรืออมลูกอม</h4>
								<p><small>เมื่ออยากสูบบุหรี่ก็ให้รีบเคี้ยวหมากฝรั่งหรืออมลูกอมจะช่วยลดความรู้สึกอยากสูบบุหรี่ลง</small></p>
							</div>
							<div class="col-12 text-center py-1">
								<h4 class="pt-2">อมและเคี้ยวเนื้อและเปลือกมะนาว</h4>
								<p><small>วิตามินซีในมะนาวจะช่วยเปลี่ยนรสชาติ ของบุหรี่ให้เฝื่อน ทำให้ไม่อยากสูบบุหรี่และลดอาการอยากนิโคตินได้ อีกทั้งยังช่วยขจัดคราบบุหรี่ได้อีกด้วย</small></p>
							</div>
						</div>
					</div>
					';
				}
				else if($d==7){
					echo '
					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเช้า</h2>
							</div>
							<div class="col-12 text-center py-3">
								<h3 class="pt-2">คุณจะผ่านวันนี้ไปได้</h3>
								<p>หากคุณมีอาการอยากสูบบุหรี่และบุหรี่ไฟฟ้า</p>
								<p>IQS แนะนำให้ใช้ 5 ตัวช่วย</p>
							</div>
							<div class="col-12 text-center py-1">
								<h4 class="pt-2">คุณต้องใจแข็ง</h4>
								<p><small>แม้มีความคิดอยากสูบก็ต้องแน่วแน่กับสัญญาที่ทำไว้กับตนเองและคนที่คุณรัก</small></p>
							</div>
							<div class="col-12 text-center py-1">
								<h4 class="pt-2">ดื่มน้ำให้มาก</h4>
								<p><small>ดื่มน้ำให้มาก มากกว่า 2 ลิตร จะช่วยให้ร่างกายขับสารพิษออกจากร่างกาย</small></p>
							</div>
							<div class="col-12 text-center py-1">
								<h4 class="pt-2">หากิจกรรมอื่นทำ</h4>
								<p><small>เพื่อดึงดูความสนใจของคุณ เช่น ออกกำลังกาย เล่มเกม ดูซีรี่</small></p>
							</div>
							<div class="col-12 text-center py-1">
								<h4 class="pt-2">เคี้ยวหมากฝรั่ง หรืออมลูกอม</h4>
								<p><small>เมื่ออยากสูบบุหรี่ก็ให้รีบเคี้ยวหมากฝรั่งหรืออมลูกอมจะช่วยลดความรู้สึกอยากสูบบุหรี่ลง</small></p>
							</div>
							<div class="col-12 text-center py-1">
								<h4 class="pt-2">อมและเคี้ยวเนื้อและเปลือกมะนาว</h4>
								<p><small>วิตามินซีในมะนาวจะช่วยเปลี่ยนรสชาติ ของบุหรี่ให้เฝื่อน ทำให้ไม่อยากสูบบุหรี่และลดอาการอยากนิโคตินได้ อีกทั้งยังช่วยขจัดคราบบุหรี่ได้อีกด้วย</small></p>
							</div>
						</div>
					</div>
					';
				}
			}
			// ============================== 3 ==============================
			else if($n==3){ 
				if($d==1){
					echo '
					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเช้า</h2>
							</div>
							<div class="col-12 text-center py-3">
								<h3 class="pt-2 text-orange">คุณต้องใจแข็ง</h3>
								<p>
									แม้มีความคิดอยากสูบก็ต้องแน่วแน่<br>
									กับสัญญาที่ทำไว้กับตนเองและคนที่คุณรัก<br>
									คิดถึงข้อดีของการไม่สูบบุหรี่ครบ 1 วัน <br>
									เพื่ออนาคตของคุณเอง
								</p>
							</div>
							<div class="col-12 text-center py-3">
								<h3 class="pt-2 text-orange">ดื่มน้ำให้มาก</h3>
								<p>
									ดื่มน้ำให้มาก มากกว่า 2 ลิตร<br>
									จะช่วยให้ร่างกายขับสารพิษออกจากร่างกาย<br>
								</p>
							</div>
							<div class="col-12 text-center py-3">
								<h3 class="pt-2 text-orange">หากิจกรรมอื่นทำ</h3>
								<p>เพื่อดึงดูความสนใจของคุณ <br> เช่น ออกกำลังกาย เล่มเกม ดูซีร</p>
							</div>
						</div>
					</div>
					';
				}
				else if($d==2 || $d==4){
					echo '
					<div class="box_white">
						<div class="row">
					';

					if($chk){
						$new = $query->queryDB_to_string('SELECT `n` FROM `day` WHERE user_id='.$user_id.' ORDER BY id DESC LIMIT 1');
						$old = $query->queryDB_to_string('SELECT `n` FROM `day` WHERE user_id='.$user_id.' ORDER BY id DESC LIMIT 1,1');
						echo '<div class="col-12 text-center pt-3">';
						if($new==0){
							echo '<h2 class="text-green"><b>ไชโย !</b></h2>คุณทำได้คุณหยุดสูบบุหรี่ได้สำเร็จ';
							$scr_new = true;
						}
						else if($new<$old){
							echo '<h2 class="text-green"><b>ดีใจด้วยวันนี้</b></h2>วันนี้คุณทำสำเร็จ คุณสูบบุหรี่ลดลง !';
						}
						else{
							echo '<h3 class="text-red">วันนี้<br>คุณยังไม่ประสบความสำเร็จ</h3>แต่ไม่เป็นไร อย่าตำหนิตัวเอง <br> <b>พรุ่งนี้เริ่มต้นใหม่ !</b>';
						}
						echo '</div>';
					}

					if($chk_input){
						echo '
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเย็น</h2>
							</div>
							<div class="col-12 text-center py-3">
								<h3 class="pt-2">วันนี้คุณสูบบุหรี่กี่มวน</h3>
								<input id="n" name="n" type="number" class="form-control" placeholder="จำนวนมวน" >
							</div>
						';
					}
					if($day>7 && $chk){
						$test = '<div class="p-2"><a href="test_a.php"><button class="bt_big bg-dark"><small><i class="fa fa-edit pr-2" aria-hidden="true"></i>แบบประเมินระดับการติดสารนิโคติน</small></button></a></div>';
					}
					echo '
							<div class="col-12 text-center py-3">
								<div id="chart"></div>
								'.$test.'
							</div>
						</div>
					</div>
					';

					if($chk_input){
						echo '
						<div class="col-12 pl-3 pr-3 pb-3">
							<button id="sent" class="bt_big bg-dark"><i class="fa fa-send pr-2" aria-hidden="true"></i>ส่งผลวันที่ '.$day.'</button>
						</div>
						';
					}
					$chk_chart = true;
				}
				else if($d==3){
					echo '
					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเช้า</h2>
							</div>
							<div class="col-12 text-center py-3">
								<h5 class="pt-2">สมุนไพรช่วยลดอาการอยากสูบบุหรี</h5>
							</div>
							<div class="col-12 text-center py-3">
								<h1 class="pt-2">มะนาว</h1>
							</div>
							<div class="col-12 text-center py-3">
								<small>
									ผลไม้เปรี้ยวจัด มีวิตามินซีสูง เปลือกมีรสเผื่อน ขม <br>
									ซึ่งวิตามินซีในมะนาวจะช่วยเปลี่ยนรสชาติของบุหรี่ให้เฝื่อน <br>
									ทำให้ไม่อยากสูบบุหรี่และลดอาการอยากนิโคตินได้ <br>
									อีกทั้งยังช่วยขจัดคราบบุหรี่ได้อีกด้วย
								</small>
							</div>

							<div class="col-12 text-center py-3">
								<p><b>
									วิธีการใช้ หั่นมะนาวติดเปลือกเป็นชิ้นเล็ก ๆ พอดีคำ <br> 
									เมื่อมีความรู้สึกอยากบุหรี่ให้กินมะนาวแทน โดยอมแล้ว<br> 
									ค่อย ๆ ดูดความเปรี้ยว จากนั้นเคียวเปลือกอย่างช้า ๆ 3-5 นาที
								</b></p>
							</div>
						</div>
					</div>
					';
				}
				else if($d==5){
					echo '
					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเช้า</h2>
							</div>
							<div class="col-12 text-center py-3">
								<p>
								 	ให้รางวัลตัวเองบ้าง<br><br><br>
									การมอบรางวัลเล็กๆ น้อยๆ ให้กับตัวเอง<br>
									ก็ถือว่าเป็นการสร้างกำลังใจที่ดี<br>
									ดังนั้นหลังจากลดบุหรี่ได้ซักระยะแล้ว<br>
									คุณอาจจะให้รางวัลตัวเอง<br>
									ด้วยการไปทานของอร่อยที่ชอบ<br> 
									หมูกระทะก็น่าสนหรือจะซื้อเสื้อผ้า<br>
									รองเท้าออกกำลังกายให้ตัวเอง<br><br>
									จะได้มีแรงสู้กับพันธกิจเลิกบุหรี่<br>ให้สำเร็จยังไงล่ะ
								</p>
							</div>
						</div>
					</div>
					';
				}
				else if($d==6){
					echo '
					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเช้า</h2>
							</div>
							<div class="col-12 text-center py-3">
								<h4>ทุกครั้งที่ท้อ..<br>ให้กลับไปนึกถึงเป้าหมาย</h4>
							</div>
							<div class="col-12 text-center py-3">
								<p>
									ถ้าการเลิกบุหรี่มันง่าย <br> 
									ก็คงไม่มีคนที่ล้มเลิกกลางคัน<br>
									อาการข้างเคียงเมื่อร่างกายขาดนิโคติน<br>
									รวมไปถึงการเห็นคนอื่นสูบบุหรี่<br>
									อาจทำให้คุณอยากยกเลิกภารกิจ<br>
									เพราะฉะนั้น<br>
									ถ้าเริ่มรู้สึกท้อกับการเลิกบุหรี่เมื่อไหร่<br>
									แนะนำให้กลับไปนึกถึงเป้าหมาย<br>
									และพันธสัญญาที่ทำไว้กับตนเอง<br>
									และคนที่คุณรัก เพื่อกระตุ้นให้ตัวเอง<br>
									กลับมาสู้อีกครั้ง
								</p>
							</div>
						</div>
					</div>
					';
				}
				else if($d==7){
					echo '
					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเช้า</h2>
							</div>
							<div class="col-12 text-center py-3">
								<h4>คุณทำได้ <br><small> คุณสามารถเลิกบุหรี่ด้วยตนเองสำเร็จ</small></h4>
							</div>
							<div class="col-12 text-center py-2">
								<p>
								 	หากต่อจากนี้ไป<br>
									คุณมีความอยากสูบบุหรี่ขึ้นมาอีก<br> 
									ก็ให้คิดถึงความสำเร็จที่ผ่านมา<br>
									ว่ามีเทคนิคอะไรของคุณ<br>
									ที่ทำให้คุณเคยผ่านมันมาได้ <br>
									และเชื่อมั่นในตัวเองว่าต่อจากนี้ไป<br>
									คุณจะสามารถผ่านมันไปได้อีกอย่างแน่นอน <br><br>

									แต่หากคุณรู้สึกท้อแท้<br>
									และมีอาการอยากสูบบุหรี่จนรู้สึกทนไม่ได้<br>
									เราอยากให้คุณลองโทรไป 1600<br>
									จะมีพี่ ๆ คอยให้คำปรึกษา <br>
									และให้กำลังใจกับคุณตลอดเวลา
								</p>
							</div>
						</div>
					</div>
					';
				}
				
			}
			// ============================== 4 ==============================
			else if($n==4){ 
				if($d==1 || $d==3 || $d==5 || $d==6 || $d==7){
					echo '
					<div class="box_white">
						<div class="row">
					';
					if($chk){
						$new = $query->queryDB_to_string('SELECT `n` FROM `day` WHERE user_id='.$user_id.' ORDER BY id DESC LIMIT 1');
						$old = $query->queryDB_to_string('SELECT `n` FROM `day` WHERE user_id='.$user_id.' ORDER BY id DESC LIMIT 1,1');
						echo '<div class="col-12 text-center pt-3">';
						if($new==0){
							echo '<h2 class="text-green"><b>ไชโย !</b></h2>คุณทำได้คุณหยุดสูบบุหรี่ได้สำเร็จ';
							$scr_new = true;
						}
						else if($new<$old){
							echo '<h2 class="text-green"><b>ดีใจด้วยวันนี้</b></h2>วันนี้คุณทำสำเร็จ คุณสูบบุหรี่ลดลง !';
						}
						else{
							echo '<h3 class="text-red">วันนี้<br>คุณยังไม่ประสบความสำเร็จ</h3>แต่ไม่เป็นไร อย่าตำหนิตัวเอง <br> <b>พรุ่งนี้เริ่มต้นใหม่ !</b>';
						}
						echo '</div>';
					}
					if($chk_input){
						echo '
							<div class="col-12 text-center pt-3">
								<h2 class="pt-2 text-orange">ตอนเย็น</h2>
							</div>
							<div class="col-12 text-center py-3">
								<h3 class="pt-2">วันนี้คุณสูบบุหรี่กี่มวน</h3>
								<input id="n" name="n" type="number" class="form-control" placeholder="จำนวนมวน" >
							</div>
						';
					}
					
					if($day>7  && $chk){
						$test = '<div class="p-2"><a href="test_a.php"><button class="bt_big bg-dark"><small><i class="fa fa-edit pr-2" aria-hidden="true"></i>แบบประเมินระดับการติดสารนิโคติน</small></button></a></div>';
					}
					echo '
							<div class="col-12 text-center py-3">
								<div id="chart"></div>
								'.$test.'
							</div>
						</div>
					</div>
					';

					if($chk_input){
						echo '
						<div class="col-12 pl-3 pr-3 pb-3">
							<button id="sent" class="bt_big bg-dark"><i class="fa fa-send pr-2" aria-hidden="true"></i>ส่งผลวันที่ '.$day.'</button>
						</div>
						';
					}
					$chk_chart = true;
				}
			}

			if($scr_new){
				echo '
				<div class="col-12 pl-3 pr-3 pb-3">
					<a href="screening.php"><button class="bt_big bg-dark"><i class="fas fa-edit pr-2" aria-hidden="true"></i>ทำแบบคำถามคัดกรองอีกครั้ง</button></a>
				</div>
				';
			}

			
			?>
		</div>
	</div>

	<div class="container pt-2">
		<div id="show_error" style="color:#333;"></div>
	</div>

	<div class="footer">
		<div class="container">

			<?php
			if($n>1){
				echo '
				<a href="days.php?d='.$day.'&n='.($n-1).'">
					<div class="menu menu-3 text-left">
						<i class="fa fa-chevron-left" aria-hidden="true"></i>
					</div>
				</a>
				';
			}
			else{
				echo '<div class="menu menu-3 text-left"> </div>';
			}
			?>
			
			<a href="home.php">
			 	<div class="menu menu-3m active">
                    <i class="fa fa-home" aria-hidden="true"></i>
				</div>
			</a>

			<?php
			if($n<$max){
				echo '
				<a href="days.php?d='.$day.'&n='.($n+1).'">
					<div class="menu menu-3 text-right">
						<i class="fa fa-chevron-right" aria-hidden="true"></i>
					</div>
				</a>
				';
			}
			else{
				echo '<div class="menu menu-3 text-right"> </div>';
			}
			?>

		</div>
	</div>
</body>

<?php 
$str = '';
$data = $query->queryDB_to_array('SELECT * FROM `day` WHERE user_id='.$user_id.' ORDER BY id ASC');
foreach($data as $key => $val){
	$str .= intval($val['n']).',';
}

$str = rtrim($str, ',');
if ($str === '') {
	$str = '0';
}


if ($chk_chart) {
echo '
<script>
	var options = {
		series: [
			{
				name: "จำนวนบุหรี่ที่สูบ",
				data: ['.$str.']
			}
		],
		chart: {
			height: 280,
			type: "area",
			fontFamily: "Kanit, sans-serif",
			toolbar: {
				show: false
			},
			zoom: {
				enabled: false
			},
			animations: {
				enabled: true,
				easing: "easeinout",
				speed: 700
			}
		},
		colors: ["#0a6c8a"],
		fill: {
			type: "gradient",
			gradient: {
				shadeIntensity: 1,
				opacityFrom: 0.35,
				opacityTo: 0.05,
				stops: [0, 90, 100]
			}
		},
		dataLabels: {
			enabled: true,
			style: {
				fontSize: "12px",
				fontWeight: "700",
				colors: ["#0a6c8a"]
			},
			background: {
				enabled: true,
				foreColor: "#ffffff",
				borderRadius: 8,
				padding: 5,
				opacity: 0.95,
				borderWidth: 0
			},
			formatter: function (val) {
				return val;
			}
		},
		stroke: {
			curve: "smooth",
			width: 4
		},
		markers: {
			size: 5,
			colors: ["#ffffff"],
			strokeColors: "#0a6c8a",
			strokeWidth: 3,
			hover: {
				size: 7
			}
		},
		grid: {
			borderColor: "#d9e7eb",
			strokeDashArray: 5,
			padding: {
				top: 10,
				right: 15,
				bottom: 5,
				left: 10
			}
		},
		xaxis: {
			labels: {
				style: {
					colors: "#78909a",
					fontSize: "12px",
					fontWeight: 600
				}
			},
			title: {
				text: "วันที่",
				style: {
					color: "#005573",
					fontSize: "13px",
					fontWeight: 700
				}
			},
			axisBorder: {
				show: false
			},
			axisTicks: {
				show: false
			}
		},
		yaxis: {
			min: 0,
			forceNiceScale: true,
			labels: {
				style: {
					colors: "#78909a",
					fontSize: "12px",
					fontWeight: 600
				}
			},
			title: {
				text: "จำนวนบุหรี่ที่สูบ (มวน)",
				style: {
					color: "#005573",
					fontSize: "13px",
					fontWeight: 700
				}
			}
		},
		tooltip: {
			theme: "light",
			y: {
				formatter: function (val) {
					return val + " มวน";
				}
			}
		},
		legend: {
			show: true,
			position: "top",
			horizontalAlign: "center",
			fontSize: "13px",
			fontWeight: 700,
			labels: {
				colors: "#005573"
			}
		}
	};

	var chart = new ApexCharts(document.querySelector("#chart"), options);
	chart.render();
</script>
';
}
?>

<script>
	$(function(){

		$("#sent").click(function(event){
			event.preventDefault();
			$.ajax({
				type: "POST",
				url: "api/days.php",
				data: {
					day : <?php echo intval($day); ?>,
					n : $('#n').val()
				},
				success: function(result){
					$("#show_error").html(result);

					$("#sent").prop("disabled", true);
					$("#sent").html('<i class="fa fa-check pr-2" aria-hidden="true"></i>ส่งผลแล้ว');
					$("#n").prop("disabled", true);

					setTimeout(function(){
						location.href = "days.php?d=<?php echo intval($day); ?>&chk=1";
					}, 800);
				}
			});		
		});


		

	});
</script>
</html>