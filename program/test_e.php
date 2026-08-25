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
        <div class="top_header2">แบบประเมิน<br>การติดนิโคตินจากบุหรี่ไฟฟ้า</div>
		<div class="bt_back pointer" onclick="history.back()"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
	</div>

    <div class="main">
        <div class="container p-3">
			<p class="text-orange"><small>โปรดเลือกคำตอบที่ตรงกับพฤติกรรมของท่านมากที่สุดในช่วง 7 วันที่ผ่านมา</small></p>
			<form id="test_from">
				<div class="box_white">
					<p>1. ใช้บุหรี่ไฟฟ้าหลังตื่นนอนภายในกี่นาที? </p>
					<label class="checkbox">
						ภายใน 5 นาที  <input type="radio" name="q1" value="3"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	6–30 นาที <input type="radio" name="q1" value="2"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	31–60 นาที <input type="radio" name="q1" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	มากกว่า 60 นาที <input type="radio" name="q1" value="0"><span class="checkmark"></span>
					</label>
				</div>
				
				<div class="box_white">
					<p>2. โดยปกติใช้บุหรี่ไฟฟ้ากี่ครั้งต่อวัน ? </p>
					<label class="checkbox">
						มากกว่า 20 <input type="radio" name="q2" value="4"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	11–20 <input type="radio" name="q2" value="3"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	6–10 <input type="radio" name="q2" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	1–5 <input type="radio" name="q2" value="0"><span class="checkmark"></span>
					</label>
				</div>

				<div class="box_white">
					<p>3. การใช้บุหรี่ไฟฟ้าในช่วงเช้าสำคัญกว่าช่วงอื่นหรือไม่ ? </p>
					<label class="checkbox">
						ใช่ <input type="radio" name="q3" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	ไม่ใช่ <input type="radio" name="q3" value="0"><span class="checkmark"></span>
					</label>
				</div>

				<div class="box_white">
					<p>4. เคยพยายามหยุดใช้แล้วหงุดหงิดหรือวิตกกังวลหรือไม่ ? </p>
					<label class="checkbox">
						ใช่ <input type="radio" name="q4" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	ไม่ใช่ <input type="radio" name="q4" value="0"><span class="checkmark"></span>
					</label>
				</div>

				<div class="box_white">
					<p>5. ใช้บุหรี่ไฟฟ้าขณะเจ็บป่วยหรือต้องพักรักษาตัวหรือไม่ ? </p>
					<label class="checkbox">
						ใช่ <input type="radio" name="q5" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	ไม่ใช่ <input type="radio" name="q5" value="2"><span class="checkmark"></span>
					</label>
				</div>

				<div class="box_white">
					<p>6. ปริมาณนิโคตินในน้ำยาที่ใช้คือเท่าใด ? </p>
					<label class="checkbox">
						≥ 35 mg/ml <input type="radio" name="q6" value="2"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	20–34 mg/ml <input type="radio" name="q6" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	< 20 mg/ml <input type="radio" name="q6" value="0"><span class="checkmark"></span>
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
				url: "api/test_e.php",
				data: $("#test_from").serialize() ,
				success: function(result){
					$("#show_error").html(result);
				}
			});		
		});

	});
</script>
</html>