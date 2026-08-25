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
<body class="page text-center">
	<div class="container" style="padding-top:10vh">
		<form id="from">
		<div class="row">
			<div class="col-12">
				<? echo $user_data['user_name']; ?><br>
				ขอสัญญากับตัวเองว่าจะเริ่มต้นเลิกบุหรี่
			</div>

			<div class="col-3 col-md-3"></div>
			<div class="col-6 col-md-6 p-2 pl-4 pr-4 text-center">
				<div> 
					<img src="<? echo $user_data['user_img']; ?>" class="img-thumbnail"> 
				</div>
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
				<div> 
					<input id="img" name="img" type="hidden" value="img/user.png">
					<input id="uploadFile" type="file" hidden>
					<img id="upload" src="img/user.png" alt="อัพโหลดรูปภาพ" class="img-thumbnail"> 
					<div id="show_loader"></div>
				</div>
			</div>
			<div class="col-3 col-md-3"></div>
			<div class="col-12 pl-5 pr-5">
				<input id="other_name" name="other_name" type="text" class="form-control" placeholder="ชื่อคนที่ฉันรัก">
			</div>

			<br><br><hr><br><br>

			<div class="col-12 p-5">
				<p>กำหนดวันที่จะเริ่มต้นหยุดสูบบุหรี่</p>
				<label class="checkbox">
					เริ่มตั้งแต่วันนี้ดีที่สุด   <input type="radio" name="q1" value="1" checked><span class="checkmark checkmark-w"></span>
				</label>
				<label class="checkbox">
					เริ่มตั้งแต่พรุ่งนี้ดีที่สุด <input type="radio" name="q1" value="2"><span class="checkmark checkmark-w"></span>
				</label>
				<label class="checkbox">
					เริ่มวันที่มีความหมายกับฉันที่สุด  <input type="radio" name="q1" value="3"><span class="checkmark checkmark-w"></span>
				</label>
				<input id="date" name="date" type="date" class="form-control" placeholder="วันที่" value="<? echo date('Y-m-d'); ?>" >
			</div>

			<div class="col-12 pl-5 pr-5 pb-5">
				<button id="sent" class="bt_big bg-dark"><i class="fa fa-send pr-2" aria-hidden="true"></i>เริ่ม</button>
			</div>

			<div class="col-12">
				<div id="show_error"></div>
			</div>
		</div>
		
	</div>
	</form>
</body>
<script>
	$(function(){

		$("#sent").click(function(event){
			event.preventDefault();
			$.ajax({
				type: "POST",
				url: "api/howto.php",
				data: $("#from").serialize() ,
				success: function(result){
					$("#show_error").html(result);
				}
			});		
		});

		$("input[type=radio][name=q1]").change(function() {
			if (this.value==1) {
				$("#date").val("<? echo date('Y-m-d'); ?>");
			}
			else if (this.value==2) {
				$("#date").val("<? echo date('Y-m-d',strtotime("+1 days")); ?>");
			}
			else{
				$("#date").focus();
			}
		});

		
		$("#upload").on("click",function(event){
			$("#uploadFile").show().click().hide();
			event.preventDefault();
		});

        $('#uploadFile').on('change', function() {
			var file_data = $('#uploadFile').prop('files')[0];   
			var form_data = new FormData();                  
			form_data.append('file', file_data);              
			$.ajax({
				url: 'php/ajax_upload_image.php?type=other', // point to server-side PHP script 
				dataType: 'text',  // what to expect back from the PHP script, if anything
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,                         
				type: 'post',
				beforeSend:function(result){
					$("#show_loader").html('<img src="img/loader.gif" alt="รอสักครู่">');
					$("#img_old").hide();
					 
				}, 
				success: function(result){
					$("#show_loader").html(result);
				}
			 });
		});
	});
</script>
</html>