<?
	include('api/app.php');
	$query = new DBControl();
	chkLogin();
?>
<html>
<head>
	<?
		getHead();
	?>
</head>
<body class="pages">

	<div class="top_menu">
        <div class="top_header">ให้ความรู้</div>
		<div class="bt_back pointer" onclick="history.back()"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
	</div>

    <div class="main main_page">
        <div class="container p-0">
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8 col-md-6 p-2 pl-4 pr-4" style="margin-top:20px">
                    <a href="test_a.php?w=<? echo $week; ?>">
                        <div class="menu">
                            <img src="img/icon/3_2.png" class="img-fluid">
                        </div>
                    </a>
                </div>
                <div class="col-2 col-md-3"></div>
				
                <div class="col-2 col-md-3"></div>
                <div class="col-8 col-md-3 p-2 pl-4 pr-4" style="margin-top:20px">
                    <a href="test_b.php?w=<? echo $week; ?>">
                        <div class="menu">
                            <img src="img/icon/3_3.png" class="img-fluid">
                        </div>
                    </a>
                </div>
                <div class="col-2 col-md-3"></div>

            </div>
		</div>
	</div>

	<div class="footer">
		<div class="container">

            <? 
				if($pre==0){ 
					echo '<div class="menu menu-3 text-left"></div>';
				}
				else{
					echo '<a href="assessment.php?w='.$pre.'"> <div class="menu menu-3 text-left"> <i class="fa fa-chevron-left" aria-hidden="true"></i> </div> </a>';
				}
			?>
			
			<a href="home.php">
				<div class="menu menu-3 active">
                    <p><i class="fa fa-home" aria-hidden="true"></i> HOME<p>
				</div>
			</a>

			<? 
				if($next==0){ 
					echo '<div class="menu menu-3 text-right"></div>';
				}
				else{
					echo '<a href="assessment.php?w='.$next.'"> <div class="menu menu-3 text-right"> <i class="fa fa-chevron-right" aria-hidden="true"></i> </div> </a>';
				}
			?>

		</div>
	</div>


</body>
<script>
	$(function(){

		

	});
</script>
</html>