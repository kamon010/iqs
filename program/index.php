<?php
	include('api/app.php');
	if(isset($_COOKIE['user_id'])){
		$link = 'home.php'; 
	}
	else{
		$link = 'login.php';
	}
?>
<html>
<head>
	<?php getHead(); ?>
</head>
<body class="page">
	<img src="img/logo.png" class="logo_img">
</body>
<script>
	$(function(){
		let timeout = setTimeout(reload, 2500);
	});

	function reload() {
		location.href = "<?php echo $link; ?>";
	}

</script>
</html>