<?
	include('api/app.php');
	$query = new DBControl();

	$id = $_GET['id'];
	if($id==''){
		$id = $query->queryDB_to_string('SELECT MIN(id) FROM page WHERE type="วิธีใช้ผลิตภัณฑ์"');
	}

	$pre = $query->queryDB_to_string("SELECT id FROM page WHERE type='วิธีใช้ผลิตภัณฑ์' AND id<".$id." ORDER BY date ASC LIMIT 1");
	if($pre==''){
		$pre = 0;
	}
	$next = $query->queryDB_to_string("SELECT id FROM page WHERE type='วิธีใช้ผลิตภัณฑ์' AND id>".$id." ORDER BY date ASC LIMIT 1");
	if($next==''){
		$next = 0;
	}

	$sql = 'SELECT * FROM page WHERE id="'.$id.'" AND type="วิธีใช้ผลิตภัณฑ์" ORDER BY date ASC LIMIT 1';
	$data = $query->queryDB_to_row($sql);
?>
<html>
<head>
	<?
		getHead();
	?>
</head>
<body class="pages">
	<div class="top_menu">
        <div class="top_header">วิธีใช้ผลิตภัณฑ์</div>
		<a href="home.php">
			<div class="bt_back"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
		</a>
	</div>

    <div class="main main_page">
        <div class="container p-0">
           <? echo $sql2.$data['detail']; ?>
		</div>
	</div>

	<div class="footer">
		<div class="container">
			<? 
				if($pre==0){ 
					echo '<div class="menu menu-3 text-left"></div>';
				}
				else{
					echo '<a href="manual.php?id='.$pre.'"> <div class="menu menu-3 text-left"> <i class="fa fa-chevron-left" aria-hidden="true"></i> </div> </a>';
				}
			?>
			
			<a href="home.php">
			 	<div class="menu menu-3m active">
                    <i class="fa fa-home" aria-hidden="true"></i>
				</div>
			</a>

			<? 
				if($next==0){ 
					echo '<div class="menu menu-3 text-right"></div>';
				}
				else{
					echo '<a href="manual.php?id='.$next.'"> <div class="menu menu-3 text-right"> <i class="fa fa-chevron-right" aria-hidden="true"></i> </div> </a>';
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