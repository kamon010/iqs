<?
	include('api/app.php');
?>
<html>
<head>
	<? getHead(); ?>
</head>
<body class="page text-center">
    <div style="padding-top:15vh">
        <h1 class="intro_1 text-orange">4.</h1>
        <br><br>
        <h3 class="intro_2">ทำความสะอาดห้องพัก</h3>
        <br><br>
        
        <p class="intro_3">รวมทั้งเสื้อผ้าก็นำมาซักให้สะอาด <br> ให้กลิ่นบุหรี่หมดไป </p>
        <p class="intro_5">จริงอยู่คนสูบบุหรี่จะไม่ได้กลิ่นเหล่านี้หรอก<br>เพราะความเคยชิน </p>
        <p class="intro_6"><b>แต่เมื่อเลิกแล้ว คุณจะได้กลิ่นของมัน <b> </p>

    </div>
</body>
<script>
	$(function(){
		let timeout = setTimeout(reload, 8000);
	});

	function reload() {
		location.href = "commit_5.php";
	}

</script>
</html>