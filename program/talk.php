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
        <div class="top_header">กระทู้</div>
		<a href="home.php">
			<div class="bt_back"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
		</a>
	</div>

    <div class="main">
        <div class="container p-0">
            <table class="table-list">
                <tbody>
                    <?
                        $sql = "SELECT * FROM toppic ORDER BY toppic_date DESC";
                        $data = $query->queryDB_to_array($sql);
                        foreach($data as $key => $val){
                            echo '
                                <tr class="clickable-row" data-href="video.php?id=11">
                                    <td>'.$val['toppic_header'].'</td>
                                </tr>
                            ';
                        }
                    ?>
                </tbody>
            </table>
		</div>

		</div>
	</div>

	<div class="footer">
		<div class="container">

			<a href="#">
				<div class="menu menu-3 text-left">
					<!-- <i class="fa fa-chevron-left" aria-hidden="true"></i> -->
				</div>
			</a>
			
			<a href="home.php">
				<div class="menu menu-3 active">
                    <p><i class="fa fa-home" aria-hidden="true"></i> HOME<p>
				</div>
			</a>

			<a href="#">
				<div class="menu menu-3 text-right">
					<!-- <i class="fa fa-chevron-right" aria-hidden="true"></i> -->
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