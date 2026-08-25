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
        <div class="top_header2">แบบประเมิน<br>โอกาสเสี่ยงที่จะสูบ</div>
		<div class="bt_back pointer" onclick="history.back()"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
	</div>

    <div class="main">
        <div class="container p-3">
			<p class="text-orange"><small>คำถามสำหรับประเมินโอกาสเสี่ยงที่จะสูบบุหรี่มวนและบุหรี่ไฟฟ้าในอนาคต</small></p>
			<form id="test_from">
				<div class="box_white">
					<p>1. ถ้าเพื่อนสนิทของคุณเสนอบุหรี่ให้คุณ คุณจะสูบบุหรี่ไหม ? </p>
					<label class="checkbox">
						ใช่แน่นอน <input type="radio" name="q1" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	อาจจะใช่ <input type="radio" name="q1" value="2"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	อาจจะไม่ใช่ <input type="radio" name="q1" value="3"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	ไม่ใช่แน่นอน <input type="radio" name="q1" value="4"><span class="checkmark"></span>
					</label>
				</div>
				
				<div class="box_white">
					<p>2. ใน 12 เดือนข้างหน้า คุณคิดว่าคุณจะสูบบุหรี่หรือไม่ ? </p>
					<label class="checkbox">
						ใช่แน่นอน <input type="radio" name="q2" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	อาจจะใช่ <input type="radio" name="q2" value="2"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	อาจจะไม่ใช่ <input type="radio" name="q2" value="3"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	ไม่ใช่แน่นอน <input type="radio" name="q2" value="4"><span class="checkmark"></span>
					</label>
				</div>

				<div class="box_white">
					<p>3. ถ้าเพื่อนสนิทของคุณเสนอบุหรี่ไฟฟ้าให้คุณ คุณจะสูบบุหรี่ไฟฟ้าไหม ? </p>
					<label class="checkbox">
						ใช่แน่นอน <input type="radio" name="q3" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	อาจจะใช่ <input type="radio" name="q3" value="2"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	อาจจะไม่ใช่ <input type="radio" name="q3" value="3"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	ไม่ใช่แน่นอน <input type="radio" name="q3" value="4"><span class="checkmark"></span>
					</label>
				</div>

				<div class="box_white">
					<p>4. ใน 12 เดือนข้างหน้า คุณคิดว่าคุณจะสูบบุหรี่ไฟฟ้าหรือไม่ ? </p>
					<label class="checkbox">
						ใช่แน่นอน <input type="radio" name="q4" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	อาจจะใช่ <input type="radio" name="q4" value="2"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	อาจจะไม่ใช <input type="radio" name="q4" value="3"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	ไม่ใช่แน่นอน <input type="radio" name="q4" value="4"><span class="checkmark"></span>
					</label>
				</div>

				<div class="box_white">
					<p>5. คุณมีความสนใจหรืออยากรู้อยากเห็นเกี่ยวกับบุหรี่ไฟฟ้าหรือไม่ ? </p>
					<label class="checkbox">
						ใช่แน่นอน <input type="radio" name="q5" value="1"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	อาจจะใช่ <input type="radio" name="q5" value="2"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	อาจจะไม่ใช่ <input type="radio" name="q5" value="3"><span class="checkmark"></span>
					</label>
					<label class="checkbox">
					 	ไม่ใช่แน่นอน <input type="radio" name="q5" value="4"><span class="checkmark"></span>
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
				url: "api/test_b.php",
				data: $("#test_from").serialize() ,
				success: function(result){
					$("#show_error").html(result);
				}
			});		
		});

	});
</script>
</html>