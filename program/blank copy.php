<?
	include('api/app.php');
	$query = new DBControl();

?>
<html>
<head>
	<?
		getHead();
	?>
</head>
<body class="pages">

	<div class="top_menu">
        <div class="top_header">HOME</div>
		<a href="home.php">
			<div class="bt_back"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
		</a>
	</div>

    <div class="main">
        <div class="container p-0">
            

		</div>
	</div>

	<div class="footer">
		<div class="container">

			<a href="#">
				<div class="menu menu-3 text-left">
					<i class="fa fa-chevron-left" aria-hidden="true"></i>
				</div>
			</a>
			
			<a href="home.php">
			 	<div class="menu menu-3m active">
                    <i class="fa fa-home" aria-hidden="true"></i>
				</div>
			</a>

			<a href="#">
				<div class="menu menu-3 text-right">
					<i class="fa fa-chevron-right" aria-hidden="true"></i>
				</div>
			</a>

		</div>
	</div>


</body>
<script>
	$(function(){

		

	});
</script>
</html>