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

	<div class="knowledge-page">

		<!-- Header -->
		<div class="knowledge-header">
			<div class="knowledge-back pointer" onclick="history.back()">
				<i class="fa fa-angle-left"></i>
			</div>
			<div class="knowledge-title">ให้ความรู้</div>
			<div class="knowledge-space"></div>
		</div>

		<!-- Intro -->
		<div class="knowledge-hero">
			<div>
				<div class="knowledge-hero-sub">ศูนย์ความรู้</div>
				<div class="knowledge-hero-title">เรียนรู้เพื่อสุขภาพที่ดีขึ้น</div>
			</div>
			<div class="knowledge-hero-icon">
				<i class="fa fa-lightbulb-o"></i>
			</div>
		</div>

		<!-- Menu -->
		<div class="knowledge-list">

			<a href="knowledge_page.php?p=ผลกระทบต่อสุขภาพ" class="knowledge-card">
				<div class="knowledge-card-img">
					<img src="img/icon/n1.png">
				</div>
				<div class="knowledge-card-info">
					<div class="knowledge-card-title">ผลกระทบต่อสุขภาพ</div>
					<div class="knowledge-card-desc">เรียนรู้ผลเสียจากบุหรี่ต่อร่างกาย</div>
				</div>
				<div class="knowledge-card-arrow">
					<i class="fa fa-angle-right"></i>
				</div>
			</a>

			<a href="knowledge_page.php?p=กฎหมายที่ควรรู้เกี่ยวกับบุหรี่" class="knowledge-card">
				<div class="knowledge-card-img">
					<img src="img/icon/n2.png">
				</div>
				<div class="knowledge-card-info">
					<div class="knowledge-card-title">กฎหมายเกี่ยวกับบุหรี่</div>
					<div class="knowledge-card-desc">ข้อควรรู้เกี่ยวกับบุหรี่และบุหรี่ไฟฟ้า</div>
				</div>
				<div class="knowledge-card-arrow">
					<i class="fa fa-angle-right"></i>
				</div>
			</a>

			<a href="knowledge_page.php?p=สมุนไพรช่วยเลิกบุหรี่" class="knowledge-card">
				<div class="knowledge-card-img">
					<img src="img/icon/n4.png">
				</div>
				<div class="knowledge-card-info">
					<div class="knowledge-card-title">สมุนไพรช่วยเลิกบุหรี่</div>
					<div class="knowledge-card-desc">ตัวช่วยธรรมชาติสำหรับการเลิกบุหรี่</div>
				</div>
				<div class="knowledge-card-arrow">
					<i class="fa fa-angle-right"></i>
				</div>
			</a>

			<a href="game.php" class="knowledge-card">
				<div class="knowledge-card-img">
					<img src="img/icon/n3.png">
				</div>
				<div class="knowledge-card-info">
					<div class="knowledge-card-title">เกมความรู้</div>
					<div class="knowledge-card-desc">ทบทวนความรู้ผ่านกิจกรรมสนุก ๆ</div>
				</div>
				<div class="knowledge-card-arrow">
					<i class="fa fa-angle-right"></i>
				</div>
			</a>

		</div>

	</div>

	<div class="footer">
		<div class="container">
			<div class="menu menu-3 text-left"></div>
			<a href="home.php">
				<div class="menu menu-3m active">
					<i class="fa fa-home" aria-hidden="true"></i>
				</div>
			</a>
			<div class="menu menu-3 text-left"></div>
		</div>
	</div>

</body>
</html>