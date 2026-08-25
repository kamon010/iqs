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
        <div class="top_header2">แบบประเมิน<br>ระดับการติดสารนิโคติน</div>
		<div class="bt_back pointer" onclick="history.back()"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
	</div>

    <div class="main">
        <div class="container p-3">
			<p class="text-orange"><small>แบบทดสอบระดับการติดสารนิโคติน (Fagerstrom test for nicotine dependence) </small></p>
			<form id="test_from">
				<div class="box_white">
					<p>1. คุณสูบบุหรี่วันละกี่มวน </p>
					<div class="input-group mb-3">
						<input type="text" class="form-control" aria-describedby="basic-addon2" name="q1">
						<div class="input-group-append">
							<span class="input-group-text" id="basic-addon2">มวน</span>
						</div>
					</div>

					<p>2. ราคาบุหรี่ที่คุณซื้อซองละกี่บาท </p>
					<div class="input-group mb-3">
						<input type="text" class="form-control" aria-describedby="basic-addon2" name="q2">
						<div class="input-group-append">
							<span class="input-group-text" id="basic-addon2">บาท</span>
						</div>
					</div>
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
				url: "api/test.php",
				data: $("#test_from").serialize() ,
				success: function(result){
					$("#show_error").html(result);
				}
			});		
		});

	});
</script>
</html>