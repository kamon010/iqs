<?
	include('api/app.php');
?>
<html>
<head>
	<? getHead(); ?>
</head>
<body class="page text-center">
    <div style="padding-top:25vh">
        <h4 class="intro_1 text-orange">ให้พันธะสัญญากับตัวเอง</h4>
        <br><br>
        <h1 class="intro_2">5 ข้อ</h1>
        <br><br>
        
        <p class="intro_3">คำแนะนำสำหรับ</p>
        <p class="intro_6">การเตรียมความพร้อม<br>ที่จะเริ่มต้นหยุดสูบบุหรี่</p>

    </div>
</body>
<script>
	$(function(){
		let timeout = setTimeout(reload, 5000);
	});

	function reload() {
		location.href = "commit_2.php";
	}

</script>
</html>