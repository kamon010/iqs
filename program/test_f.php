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
        <div class="top_header2">แบบประเมิน<br>การติดนิโคตินจากบุหรี่มวน (FTND)</div>
		<div class="bt_back pointer" onclick="history.back()"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
	</div>

    <div class="main">
        <div class="container p-3">
			<p class="text-orange"><small>โปรดเลือกคำตอบที่ตรงกับพฤติกรรมของท่านมากที่สุดในช่วง 7 วันที่ผ่านมา </small></p>
			<form id="test_from">
				<div class="box_white">
					<p>1. สูบบุหรี่ตัวแรกหลังตื่นนอนในเวลากี่นาที ? </p>
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
					<p>2. เลิกสูบในสถานที่ห้ามสูบเป็นเรื่องยากหรือไม่ ? </p>
					<label class="checkbox">
						ใช่ <input type="radio" name="q2" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	ไม่ใช่ <input type="radio" name="q2" value="0"><span class="checkmark"></span>
					</label>
				</div>

				<div class="box_white">
					<p>3. บุหรี่ตัวไหนที่สำคัญที่สุด ? </p>
					<label class="checkbox">
						ตัวแรกหลังตื่น <input type="radio" name="q3" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	ตัวอื่นๆ <input type="radio" name="q3" value="0"><span class="checkmark"></span>
					</label>
				</div>

				<div class="box_white">
					<p>4. สูบบุหรี่วันละกี่มวน ? </p>
					<label class="checkbox">
						31+  <input type="radio" name="q4" value="3"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	21–30 <input type="radio" name="q4" value="2"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	11–20 <input type="radio" name="q4" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	≤ 10 <input type="radio" name="q4" value="0"><span class="checkmark"></span>
					</label>
				</div>

				<div class="box_white">
					<p>5. สูบบ่อยขึ้นในช่วงเครียดหรือไม่ ? </p>
					<label class="checkbox">
						ใช่ <input type="radio" name="q5" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	ไม่ใช่ <input type="radio" name="q5" value="0"><span class="checkmark"></span>
					</label>
				</div>

				<div class="box_white">
					<p>6. ยังสูบบุหรี่แม้ป่วยหรือนอนทั้งวันหรือไม่ ? </p>
					<label class="checkbox">
						ใช่ <input type="radio" name="q6" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	ไม่ใช่ <input type="radio" name="q6" value="0"><span class="checkmark"></span>
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
				url: "api/test_f.php",
				data: $("#test_from").serialize() ,
				success: function(result){
					$("#show_error").html(result);
				}
			});		
		});

	});
</script>
</html>