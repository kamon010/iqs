<?
	include('api/app.php');
	$query = new DBControl();
	chkLogin();

	$user_id = $_COOKIE["user_id"];
		
?>
<html>
<head>
	<?
		getHead();
	?>
</head>
<body class="pages">

	<div class="top_menu">
        <div class="top_header">ผลการประเมิน</div>
		<a href="home.php">
			<div class="bt_back"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
		</a>
	</div>

    <div class="main">
        <div class="container p-3">
			<?
				$test = $query->queryDB_to_row('SELECT * FROM game WHERE user_id='.$user_id.' ORDER BY id DESC');
				if(sizeof($test)){ 
					if(checkTest($test['score'])){
						$str = '<div class="p-2"><a href="knowledge.php"><button id="sent" class="bt_big bg-dark"><i class="fa fa-exclamation-circle pr-2" aria-hidden="true"></i>แหล่งความรู้</button><a></div>';
					}
					else{
						$user_data = $query->queryDB_to_row('SELECT * FROM user WHERE user_id ='.$user_id);
						$str = '
							<div class="box_white">
								<div class="row">
									<div class="col-12 text-center">
										<img src="img/logo.png" height="100px">
										<p><b>ขอมอบเกียรติบัตรเพื่อแสดงว่า</b></p>
										<p>'.$user_data['user_name'].'</p>
										<p>มีความรู้เกี่ยวกับบุหรี่มวนและบุหรี่ไฟฟ้าในระดับสูง</p>

										<a href="certificate_game.php">
											<button class="bt_big bg-dark">
												<i class="fa fa-download pr-2"></i>
												ดาวน์โหลดเกียรติบัตร
											</button>
										</a>
									</div>
								</div>
							</div>
						';
					}
					echo '
					<div class="box_white">
						<div class="row">
							<div class="col-12 text-center">
								<p><b>คะแนนที่คุณทำได้</b></p>
								<h4 class="pt-2">'.$test['score'].' คะแนน</h4>
								<p style="color:#ff9900">'.getTest($test['score']).'</p>
							</div>
							<div class="col-12 text-center">
								<small>'.getTextTest($test['score']).'</small>
							</div>
							<div class="col-12">
								'.$str.'
							</div>
						</div>
					</div>
					';
				}
				else{
					echo '
					<script>
						Swal.fire({
							html: "ขออภัย ! <br> ท่ายยังไม่ได้ทำแบบสอบถาม !",
							type: "error",
							confirmButtonText: " ตกลง ",
							confirmButtonColor: "#3085d6",
							timer: 5000, 
						}).then((result) => {
							location.href = "screening.php";
						});
					</script>
					';
				}

				function checkTest($score){
					if($score<=6){
						return true;
					}
					else{
						return false;
					}
				}

				function getTest($score){
					if($score<6){
						return 'ไม่ผ่านเกณฑ์';
					}
					else if($score<9){
						return 'มีความรู้ปานกลาง';
					}
					else{
						return 'มีความรู้สูง
						';
					}
				}
				function getTextTest($score){
					if($score<6){
						return 'เสียใจด้วย <br> ท่านมีความรู้เกี่ยวกับบุหรี่และบุหรี่ไฟฟ้าไม่ผ่านเกณฑ์ เราขอแนะนำให้ศึกษาเพิ่มเติม';
					}
					else if($score<9){
						return 'ยินดีด้วย <br> ท่านมีความรู้เกี่ยวกับบุหรี่และบุหรี่ไฟฟ้าระดับปานกลาง แต่เราเชื่อมั่นว่าคุณสามารถได้คะแนนสูงกว่านี้
						ถ้าศึกษาเพิ่มเติม';
					}
					else{
						return 'ยินดีด้วย <br> ท่านมีความรู้เกี่ยวกับบุหรี่และบุหรี่ไฟฟ้าระดับสูง ';
					}
				}
			?>
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
<script>
	$(function(){


	});
</script>
</html>