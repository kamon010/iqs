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

	<h1 class="intro_head text-orange">IQS</h1>

	<h2 class="intro_text">
		ช่วยคุณให้รอบรู้พิษภัย <br><br>
		ของบุหรี่มวนและบุหรี่ไฟฟ้า
	</h2>

</body>

<script>
	let timeout;

	$(function() {
		timeout = setTimeout(reload, 3500);
	});

	function reload() {
		location.href = "intro_2.php";
	}

	function skipIntro() {
		clearTimeout(timeout);
		location.href = "register.php";
	}
</script>

</html>