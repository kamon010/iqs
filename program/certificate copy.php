<?
	include('api/app.php');
	$query = new DBControl();

	$user_id = $_COOKIE["user_id"];

	$type = $_GET['type'];
		
?>
<html>
<head>
	<?
		getHead();
	?>
</head>
<body class="">
	<div class="container p-3">
		<?
			$user_data = $query->queryDB_to_row('SELECT * FROM user WHERE user_id ='.$user_id);
		?>
		<div style="padding-top:30%;">
			<div class="row">
				<div class="col-12 text-center">
					<img src="img/logo.png" height="150px">
					<p><b>ขอมอบเกียรติบัตรเพื่อแสดงว่า</b></p>
						
					<p><? echo $user_data['user_name']; ?></p>

					<p>ไม่มีโอกาสเสี่ยงที่จะสูบบุหรี่แบบ<? echo $type; ?>ในอนาคต</p>
					<p>จากการทดสอบประเมินโอกาสเสี่ยงที่จะสูบบุหรี่ไฟฟ้า<br>จากเว็บไซต์ www.iqstrc.com</p>

				</div>
			</div>
		</div>
	</div>

</body>
<script>
	$(function(){

		window.print();
	});
</script>
</html>