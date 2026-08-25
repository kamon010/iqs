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
        <div class="top_header">คำถามคัดกรอง</div>
		<a href="home.php">
			<div class="bt_back"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
		</a>
	</div>

    <div class="main">
		<?php
			$data = $query->queryDB_to_row('SELECT * FROM screening WHERE user_id='.intval($user_id).' ORDER BY id DESC');

			if(!empty($data)){ 
				echo '
				<script>
					Swal.fire({
						html: "ขออภัย ! <br> ท่านทำคำถามคัดกรองนี้ไปแล้ว ! <br><br> ท่านต้องการทำแบบสอบถามนี้อีกหรือไม่",
						type: "error",
						confirmButtonText: " ตกลง ",
						confirmButtonColor: "#3085d6",
						showCancelButton: true,
						cancelButtonText: " ยกเลิก ",
						timer: 5000
					}).then((result) => {
						if (result.value) {
							
						}
						else{
							location.href = "home.php";
						}
					});
				</script>
				';
			}
		?>

        <div class="container p-3">
			<p class="text-orange">คำถามคัดกรอง</p>
            <form id="test_from">
				<div class="box_white">
					<p>30 วันที่ผ่านมาคุณสูบบุหรี่มวนและบุหรี่ไฟฟ้าหรือไม่ </p>

					<label class="checkbox">
					 	สูบบุหรี่มวนสลับกับบุหรี่ไฟฟ้า 
						<input type="radio" name="q1" value="1">
						<span class="checkmark"></span>
					</label>

					<label class="checkbox">
					 	สูบบุหรี่มวนอย่างเดียว 
						<input type="radio" name="q1" value="2">
						<span class="checkmark"></span>
					</label>

					<label class="checkbox">
					 	สูบบุหรี่ไฟฟ้าอย่างเดียว 
						<input type="radio" name="q1" value="3">
						<span class="checkmark"></span>
					</label>

					<label class="checkbox">
						ไม่สูบทั้งบุหรี่มวนและบุหรี่ไฟฟ้า 
						<input type="radio" name="q1" value="4">
						<span class="checkmark"></span>
					</label>
				</div>
			</form>
			
			<div class="p-2">
				<button id="sent" class="bt_big bg-dark">
					<i class="fa fa-share pr-2" aria-hidden="true"></i>ต่อไป
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

			<div class="menu menu-3 text-right"></div>

		</div>
	</div>

</body>

<script>
	$(function(){

		$("#sent").click(function(event){
			event.preventDefault();

			if($("input[type='radio'][name='q1']:checked").val() != undefined){
				$.ajax({
					type: "POST",
					url: "api/screening.php",
					data: $("#test_from").serialize(),
					success: function(result){
						$("#show_error").html(result);
					}
				});
			}
			else{
				Swal.fire({
					html: "ขออภัย ! <br> กรุณาเลือกคำตอบ !",
					type: "error",
					confirmButtonText: " ตกลง ",
					confirmButtonColor: "#3085d6",
					timer: 5000
				});
			}	
		});

		<?php
            if(!isset($_COOKIE['user_id'])){
                echo '
                    Swal.fire({
                        html: "ขออภัย ! <br> กรุณาเข้าสู่ระบบก่อน !",
                        type: "error",
                        confirmButtonText: " ตกลง ",
                        confirmButtonColor: "#3085d6",
                        timer: 5000
                    }).then((result) => {
                        location.href = "login.php";
                    });
                ';
            }	
        ?>

	});
</script>
</html>