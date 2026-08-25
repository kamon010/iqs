<?
	include('api/app.php');
	$query = new DBControl();
	chkLogin();

	$user_id = $_COOKIE["user_id"];

	if($_GET['n']!=''){
		$n = $_GET['n']; 
	}
	else{
		$n = 1; 
	}
?>
<html>
<head>
	<?
		getHead();
	?>
</head>
<body class="pages">

	<div class="top_menu">
        <div class="top_header">เลิกบุหรี่วันที่ 1</div>
		<a href="home.php">
			<div class="bt_back"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
		</a>
	</div>

    <div class="main">
        <div class="container p-3">
			<? 
			if($n==1){ 
				echo '
				<div class="box_white">
					<div class="row">
						<div class="col-12 text-center pt-3">
							<h2 class="pt-2 text-orange">ตอนเช้า</h2>
						</div>
						<div class="col-12 text-center py-3">
							<h1 class="pt-2 text-orange">วันนี้</h1>
							<h3>เป็นวันเลิกสูบบุหรี่วันแรก</h3>
						</div>
						<div class="col-12 text-center py-3">
							<h4>หยุดสูบบุหรี่ 1 วันดีอย่างไร</h4>
							<h4>คุณจะรู้สึกสดชื่นขึ้น</h4>
							
							<table class="table">
								<tr>
									<td>20 นาที</td>
									<td>ความดันโลหิตและชีพจรคุณจะเริ่มกลับสู่ปกติ</td>
								</tr>
								<tr>
									<td>8 ชั่วโมง</td><td>ระดับออกซิเจนในเลือดกลับสู่ปกติ <br>นิโคตินและคาร์บอนมอนอกไซด์จะลดระดับลงมากกว่าครึ่ง</td>
								</tr>
								<tr>
									<td>24 ชั่วโมง</td><td>คาร์บอนมอนอกไซด์ถูกกำจัดออกจากร่างกาย  <br>ปอดจะเริ่มกำจัดเสมหะและคราบสิ่งสกปรกจากบุหรี่ <br> คุณจะรู้สึกตัวเองสะอาดขึ้น หายใจเต็มปอด และสมองทำงานดีขึ้น</td>
								</tr>
							</table>   

						</div>
					</div>
				</div>
				';
			}
			else if($n==2){ 
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
			else if($n==3){ 
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
			else if($n==4){ 
				$test = $query->queryDB_to_row('SELECT * FROM test WHERE user_id='.$user_id.' ORDER BY id DESC');
				echo '
				<div class="box_white">
					<div class="row">
						<div class="col-12 text-center pt-3">
							<h2 class="pt-2 text-orange">ตอนเย็น</h2>
						</div>
						<div class="col-12 text-center py-3">
							<h3 class="pt-2">วันนี้คุณสูบบุหรี่กี่มวน</h3>
							<input type="hidden" id="q1" name="q1" value="'.$test["q1"].'">
							<input id="n" name="n" type="number" class="form-control" placeholder="จำนวนมวน" >
							<p class="text-red"><small>** จากเดิมท่านสูบวันละ '.$test["q1"].' มวน</small></p>
					</div>
				</div>

				<div class="col-12 pl-3 pr-3 pb-3">
					<button id="sent" class="bt_big bg-dark"><i class="fa fa-send pr-2" aria-hidden="true"></i>ส่งผลวันที่ 1</button>
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

			<?
			if($n>1){
				echo '
				<a href="day_1.php?n='.($n-1).'">
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

			<?
			if($n<4){
				echo '
				<a href="day_1.php?n='.($n+1).'">
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
<script>
	$(function(){

		$("#sent").click(function(event){
			event.preventDefault();
			$.ajax({
				type: "POST",
				url: "api/day_1.php",
				data: {
					day : 1,
					n : $('#n').val(),
					q1 : $('#q1').val()
				},
				success: function(result){
					$("#show_error").html(result);
				}
			});		
		});

	});
</script>
</html>