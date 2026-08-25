<?
	include('api/app.php');
?>
<html>
<head>
	<? getHead(); ?>
</head>
<body class="page text-center">
    <div style="padding-top:15vh">
        <h1 class="intro_1 text-orange">1.</h1>
        <br><br>
        <h3 class="intro_2">ทิ้งบุหรี่และบุหรี่ไฟฟ้า<br>ที่คุณมีอยู่ให้หมด </h3>
        <br><br>
        
        <p class="intro_3">หาให้ทั่วว่าคุณอาจจะซุกซ่อนบุหรี่<br>ของคุณเอาไว้ที่ไหน ในกระเป๋าเสื้อ</p><br>
        <p class="intro_5">กระเป๋ากางเกง เสื้อแจ็คเก็ต ลิ้นชักโต๊ะทำงาน <br> โยนทิ้งไม่ให้เหลือแม้กระทั่งมวนเดียว</p><br>
        <p class="intro_6">ไม่ว่ามันจะมีราคาแพงแค่ไหน<br>ก็อย่าเสียดายเป็นอันขาด</p><br>

    </div>
</body>
<script>
	$(function(){
		let timeout = setTimeout(reload, 5000);
	});

	function reload() {
		location.href = "commit_3.php";
	}

</script>
</html>