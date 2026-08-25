<?
	include('api/app.php');
?>
<html>
<head>
	<? getHead(); ?>
</head>
<body class="page text-center">
    <div style="padding-top:25vh">
        <h1 class="intro_1 text-orange">3.</h1>
        <br><br>
        <h3 class="intro_2">เปลี่ยนทรงผมใหม่<br>เปลี่ยนเสื้อผ้าใหม่ </h3>
        <br><br>
        
        <p class="intro_3">จะได้ดูว่าเรากำลังจะเริ่มต้นใหม</p>
        <p class="intro_6">เป็นคนใหม่ที่ไม่พึ่งบุหรี่และบุหรี่ฟ้า</p>

    </div>
</body>
<script>
	$(function(){
		let timeout = setTimeout(reload, 5000);
	});

	function reload() {
		location.href = "commit_4.php";
	}

</script>
</html>