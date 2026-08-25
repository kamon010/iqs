<?
	include('api/app.php');
	include_once('php/video_embed.php');
	$query = new DBControl();
?>
<html>
<head>
	<?
		getHead();
        $data = $query->queryDB_to_row('SELECT * FROM page WHERE id=1');
	?>
    <style>
		.video-open-link {
			display: inline-block;
			margin-top: 12px;
			color: #2f7887;
			font-size: 14px;
			font-weight: 600;
		}
    </style>
</head>
<body class="pages">

	<div class="top_menu">
        <div class="top_header">คลิปให้ความรู้ E-Learning</div>
		<a href="home.php">
			<div class="bt_back"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
		</a>
	</div>

    <div class="main">
        <div class="container p-0">
            <div class="box_white">
				<? echo trcRenderVideoEmbed($data['name'] ?? '', 'E-Learning video'); ?>
                <? echo trcEmbedVideoLinksInHtml($data['detail'] ?? ''); ?>

                <div class="p-2"><a></a><a href="e-learning.php?test=1"><button class="bt_big bg-dark"><i class="fa fa-exclamation-circle pr-2" aria-hidden="true"></i>ทดสอบความรู้อีกครั้ง </button></a><a></a></div>
            </div>

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
