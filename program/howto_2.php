<?
	include('api/app.php');
?>
<html>
<head>
	<? getHead(); ?>
</head>
<body class="page text-center">
    <div style="padding-top:35vh;">
        <h1 class="intro_1 text-orange">START</h1>
        <br><br>
        <h5 class="intro_4"> เราเริ่มต้นทำพันธะสัญญาเลิกบุหรี่กันเถอะ</h5>
        <br><br>

</div>
</body>
<script>
	$(function(){
		let timeout = setTimeout(reload, 5000);
	});

	function reload() {
		location.href = "howto.php";
	}

</script>
</html>