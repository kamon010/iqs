<?
include('api/app.php');
?>
<html>

<head>
	<? getHead(); ?>
</head>

<body class="page text-center">

	<button class="skip-btn" onclick="skipIntro()">
		<i class="fa fa-forward"></i>
		ข้าม
	</button>

	<h1 class="intro_head text-orange">IQS</h1><br><br>
	<h2 class="intro_text">
		ถ้าคุณสูบบุหรี่มวน<br><br>และบุหรี่ไฟฟ้า<br><br>
		เราจะช่วยคุณให้<b>เลิก</b>ได้สำเร็จ
	</h2>

</body>
<script>
	$(function() {
		let timeout = setTimeout(reload, 3500);
	});

	function reload() {
		location.href = "register.php";
	}

	function skipIntro() {
		clearTimeout(timeout);
		location.href = "register.php";
	}
</script>

</html>