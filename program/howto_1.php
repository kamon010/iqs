<?
	include('api/app.php');
?>
<html>
<head>
	<? getHead(); ?>
</head>
<body class="page text-center">
    <div style="padding-top:10vh">
        <h4 class="intro_1 text-orange">ให้พันธะสัญญากับตัวเอง</h4>
        <br><br>
        <h5 class="intro_2">เราดีใจที่คุณมีใจที่แน่วแน่ที่จะ<br>เลิกบุหรี่และบุหรี่ไฟฟ้า</h5>
        <br><br>

        <h5 class="intro_3">หยุดสูบบุหรี่แล้วได้อะไร</h5>
        
        <p class="intro_4">✓ ลดความเสี่ยงโรคหัวใจ</p>
        <p class="intro_4">✓ โรคปอดโรคไต</p>
        <p class="intro_5">✓ โรคติดเชื้อ และมะเร็ง</p>
        <p class="intro_5">✓ ลดภาวะกระดูกพรุน</p>
        <p class="intro_6">✓ ผิวหนังสดใสดูอ่อนเยาว์ขึ้น</p>
        <p class="intro_6">✓ สมรรถภาพทางเพศจะดีขึ้น</p>

    </div>
</body>
<script>
	$(function(){
		let timeout = setTimeout(reload, 7000);
	});

	function reload() {
		location.href = "howto_2.php";
	}

</script>
</html>