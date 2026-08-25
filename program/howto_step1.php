<?
	include('api/app.php');
	$query = new DBControl();
	chkLogin();

	$user_id = $_COOKIE["user_id"];
	if($user_id!=''){
		$sql = 'SELECT * FROM user WHERE user_id ='.$user_id;
		$user_data = $query->queryDB_to_row($sql);
	}
?>
<html>
<head>
	<?
		getHead();
	?>
</head>
<body class="pages">

	<div class="top_menu">
        <div class="top_header">ให้พันธะสัญญากับตัวเอง</div>
		<a href="home.php">
			<div class="bt_back"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
		</a>
	</div>

    <div class="main main_page">
        <div class="container p-0 text-center">
            <div class="row">
                <div class="col-12">
					<? echo $user_data['user_name']; ?><br>
			 		ขอสัญญากับตัวเองว่าจะเริ่มต้นเลิกบุหรี่
				</div>

				<div class="col-3 col-md-3"></div>
				<div class="col-6 col-md-6 p-2 pl-4 pr-4 text-center">
					<div> <img src="img/user.png" alt="อัพโหลดรูปภาพ" class="img-thumbnail"> </div>
				</div>
				<div class="col-3 col-md-3"></div> 

				<div class="col-12">
					ฉันจะเลิกบุหรี่ให้สำเร็จเพื่อคนที่ฉันรัก
				</div>

				
				<br><br><hr><br><br>

				<div class="col-12">
					คนที่ฉันรัก
				</div>
				<div class="col-3 col-md-3"></div>
				<div class="col-6 col-md-6 p-2 pl-4 pr-4 text-center">
					<div> <img src="img/user.png" alt="อัพโหลดรูปภาพ" class="img-thumbnail"> </div>
				</div>
				<div class="col-3 col-md-3"></div>
				<div class="col-12">
				 	<input type="text" class="form-control" placeholder="ชื่อคนที่ฉันรัก">
				</div>

				



            </div>
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