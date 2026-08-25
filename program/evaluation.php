<?
include('api/app.php');
$query = new DBControl();
chkLogin();

$user_id = $_COOKIE["user_id"] ?? 0;
$user_data = $query->queryDB_to_row('SELECT * FROM user WHERE user_id=' . $user_id);
$university_id = $user_data['university_id'] ?? 0;
?>
<html>

<head>
	<?
	getHead();
	?>
</head>

<body class="pages">

	<div class="top_menu">
		<div class="top_header">แบบประเมินความพึงพอใจ</div>
		<a href="home.php">
			<div class="bt_back"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
		</a>
	</div>

	<div class="main satisfaction-main">
		<div class="container p-0">

			<div class="satisfaction-card">
				<div class="satisfaction-head">
					<div class="satisfaction-icon">
						<i class="fa fa-university"></i>
					</div>
					<div>
						<div class="satisfaction-title">เลือกแบบประเมิน</div>
						<div class="satisfaction-desc">แบบประเมินของมหาวิทยาลัยคุณ</div>
					</div>
				</div>

				<?
				if (intval($university_id) > 0) {
					$datas = $query->queryDB_to_array(
						'SELECT * FROM university 
							 WHERE university_id=' . intval($university_id) . ' 
							 AND university_detail!=""'
					);
				} else {
					$datas = $query->queryDB_to_array(
						'SELECT * FROM university 
							 WHERE university_detail!="" 
							 ORDER BY university_id'
					);
				}

				if (!empty($datas)) {
					foreach ($datas as $index => $val) {
						echo '
							<a href="' . $val['university_detail'] . '" class="university-card">
								<div class="university-icon">
									<i class="fa fa-graduation-cap"></i>
								</div>
								<div class="university-info">
									<div class="university-name">' . $val['university_name'] . '</div>
									<div class="university-sub">กดเพื่อทำแบบประเมินความพึงพอใจ</div>
								</div>
								<div class="university-arrow">
									<i class="fa fa-angle-right"></i>
								</div>
							</a>
							';
					}
				} else {
					echo '
						<div class="satisfaction-empty">
							<i class="fa fa-info-circle"></i>
							<div>ไม่พบแบบประเมินของมหาวิทยาลัยนี้</div>
						</div>
						';
				}
				?>
			</div>

		</div>
	</div>

	<div class="footer">
		<div class="container">

			<a href="#">
				<div class="menu menu-3 text-left">
				</div>
			</a>

			<a href="home.php">
				<div class="menu menu-3m active">
					<i class="fa fa-home" aria-hidden="true"></i>
				</div>
			</a>

			<a href="#">
				<div class="menu menu-3 text-right">
				</div>
			</a>

		</div>
	</div>

</body>

</html>
