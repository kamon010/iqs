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
<body class="pages_1">

	<div class="top_menu">
        <div class="top_header">เกมตอบคำถาม</div>
		<div class="bt_back pointer" onclick="history.back()"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
	</div>

    <div class="main">
        <div class="container p-3">
			<form id="test_from">
				<div class="box_white">
					<p>1. สารใดในบุหรี่ก่อให้เกิดการเสพติด</p>
					<label class="checkbox">
						ทาร์ <input type="radio" name="q1" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
						นิโคติน <input type="radio" name="q1" value="2"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
						คาร์บอนมอนอกไซด์  <input type="radio" name="q1" value="3"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
						ไซยาไนด์ <input type="radio" name="q1" value="4"><span class="checkmark"></span>
					</label>
				</div>
				<div class="box_white">
					<p>2. โรคที่เกิดจากบุหรี่ โรคใดเมื่อเป็นแล้วไม่มีโอกาสหาย และทรมานไปตลอดชีวิต </p>
					<label class="checkbox">
						มะเร็งลำไส้ <input type="radio" name="q2" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
						มะเร็งกล่องเสียง <input type="radio" name="q2" value="2"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
						มะเร็งหลอดลม <input type="radio" name="q2" value="3"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
						ถุงลมโป่งพอง <input type="radio" name="q2" value="4"><span class="checkmark"></span>
					</label>
				</div>

				<div class="box_white">
					<p>3. พระราชบัญญัติควบคุมผลิตภัณฑ์ยาสูบ พ.ศ. 2560 กำหนดว่าห้ามสูบบุหรี่ในเขตปลอดบุหรี่ หากฝ่าฝืนมีโทษปรับเป็นจำนวนเงินกี่บาท </p>
					<label class="checkbox">
					 	2,000 บาท <input type="radio" name="q3" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
						5,000 บาท <input type="radio" name="q3" value="2"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	2,500 บาท <input type="radio" name="q3" value="3"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
						500 บาท <input type="radio" name="q3" value="4"><span class="checkmark"></span>
					</label>
				</div>

				<div class="box_white">
					<p>4. ควันบุหรี่มือสอง หมายถึงข้อใด </p>
					<label class="checkbox">
						ผู้ที่ไม่ได้สูบบุหรี่แต่ได้รับเอาควันบุหรี่จากผู้สูบที่อยู่ใกล้ชิด  <input type="radio" name="q4" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
						สารพิษจากควันบุหรี่ที่ตกค้างตามเฟอร์นิเจอร์ เสื้อผ้า   <input type="radio" name="q4" value="2"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
						ควันที่ผู้สูบบุหรี่ดูดเข้าไปในร่างกาย <input type="radio" name="q4" value="3"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
						ถูกทุกข้อ   <input type="radio" name="q4" value="4"><span class="checkmark"></span>
					</label>
				</div>
				
				<div class="box_white">
					<p>5. พระราชบัญญัติควบคุมผลิตภัณฑ์ยาสูบ พ.ศ. 2560 กำหนดว่าห้ามขายบุหรี่ให้กับผู้ที่มีอายุต่ำกว่ากี่ปีบริบูรณ์ </p>
					<label class="checkbox">
						18 ปี <input type="radio" name="q5" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
						20 ปี   <input type="radio" name="q5" value="2"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	25 ปี  <input type="radio" name="q5" value="3"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
						15 ปี   <input type="radio" name="q5" value="4"><span class="checkmark"></span>
					</label>
				</div>
				
				<div class="box_white">
					<p>6. ข้อใดคือผลกระทบของบุหรี่ไฟฟ้า</p>
					<label class="checkbox">
						สมองถูกทำลาย   <input type="radio" name="q6" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	เสพติดนิโคติน  <input type="radio" name="q6" value="2"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
						ปอดอักเสบเฉียบพลัน  <input type="radio" name="q6" value="3"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	ถูกทุกข้อ  <input type="radio" name="q6" value="4"><span class="checkmark"></span>
					</label>
				</div>
				
				<div class="box_white">
					<p>7. การสูบบุหรี่ไฟฟ้าในที่สาธารณะเป็นความผิดตามพระราชบัญญัติควบคุมผลิตภัณฑ์ยาสูบ พ.ศ. 2560 หรือไม่</p>
					<label class="checkbox">
						ไม่มีความผิดตามกฎหมาย   <input type="radio" name="q7" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
						มีความผิดเทียบเท่าการสูบบุหรี่ในที่สาธารณะ  <input type="radio" name="q7" value="2"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
						มีความผิดน้อยกว่าการสูบบุหรี่ในที่สาธารณะ  <input type="radio" name="q7" value="3"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
						ไม่รู้  <input type="radio" name="q7" value="4"><span class="checkmark"></span>
					</label>
				</div>
				
				<div class="box_white">
					<p>8.  การมีบุหรี่ไฟฟ้าไว้ในครอบครองมีความผิดตามกฎหมายหรือไม่</p>
					<label class="checkbox">
						ไม่มีความผิดเพราะไม่ได้เป็นผู้นำเข้า   <input type="radio" name="q8" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
						ไม่มีความผิดเพราะไม่ได้เป็นผู้ขาย  <input type="radio" name="q8" value="2"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	มีความผิดโทษจำคุกไม่เกิน 3 ปี หรือปรับเป็นเงิน 4 เท่าของราคาสินค้า หรือทั้งจำทั้งปรับ  <input type="radio" name="q8" value="3"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	มีความผิดโทษจำคุกไม่เกิน 5 ปี หรือปรับเป็นเงิน 4 เท่าของราคาสินค้า หรือทั้งจำทั้งปรับ  <input type="radio" name="q8" value="4"><span class="checkmark"></span>
					</label>
				</div>
				
				<div class="box_white">
					<p>9. ข้อใดกล่าวถูกต้องเกี่ยวกับบุหรี่ไฟฟ้า</p>
					<label class="checkbox">
					 	บุหรี่ไฟฟ้าช่วยเลิกบุหรี่มวนได้แน่นอน   <input type="radio" name="q9" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	บุหรี่ไฟฟ้ามีนิโคตินน้อยกว่าบุหรี่มวน  <input type="radio" name="q9" value="2"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	บุหรี่ไฟฟ้ามีผลกระทบทางร่างกายน้อยกว่าบุหรี่มวน  <input type="radio" name="q9" value="3"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	บุหรี่ไฟฟ้ามีนิโคตินมากกว่าบุหรี่มวนและมีผลกระทบทางร่างกายไม่แตกต่างจากบุหรี่มวน  <input type="radio" name="q9" value="4"><span class="checkmark"></span>
					</label>
				</div>
				
				<div class="box_white">
					<p>10. ข้อใดกล่าวถึงควันบุหรี่ไฟฟ้ามือสอง “ไม่ถูกต้อง” </p>
					<label class="checkbox">
					 	ควันบุหรี่ไฟฟ้ามือสองไม่มีอันตรายเพราะมีกลิ่นหอม   <input type="radio" name="q10" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
						ควันบุหรี่ไฟฟ้ามือสองมี PM 1.0 เสี่ยงต่อมะเร็งปอด  <input type="radio" name="q10" value="2"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
						ควันบุหรี่ไฟฟ้ามือสองมีนิโคติน  <input type="radio" name="q10" value="3"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	ควันบุหรี่ไฟฟ้ามือสองมีสารพิษที่ก่อให้เกิดผลกระทบต่อร่างกาย  <input type="radio" name="q10" value="4"><span class="checkmark"></span>
					</label>
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
			$.ajax({
				type: "POST",
				url: "api/game.php",
				data: $("#test_from").serialize() ,
				success: function(result){
					$("#show_error").html(result);
				}
			});		
		});

	});
</script>
</html>