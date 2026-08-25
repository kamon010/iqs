<?
	include('api/app.php');
?>
<html>
<head>
	<? getHead(); ?>
</head>
<body class="page text-center">
    <div style="padding-top:15vh">
        <h1 class="intro_1 text-orange">5.</h1>
        <br><br>
        <br><br>
        <p class="intro_2">บอกคนรอบข้าง เช่น พ่อแม่ เพื่อน คนรัก คุณครู <br> ว่าคุณกำลังเริ่มต้นเลิกบุหรี่ </p>
        <p class="intro_4">เพื่อให้คนที่เรารักและคนที่รักเรา<br>เป็นกำลังใจให้เราเลิกบุหรี่ได้สำเร็จ</p>
        <p class="intro_6">หรือโพสต์ในสื่อสังคมออนไลน์เพื่อประกาศคำมั่นสัญญา
</p>

    </div>
</body>
<script>
	$(function(){
		let timeout = setTimeout(reload, 8000);
	});

	function reload() {
		location.href = "day_1.php";
	}

</script>
</html>