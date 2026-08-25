<?
include('api/app.php');
include_once('php/applicant_options.php');
$query = new DBControl();
chkLogin();

$user_id = $_COOKIE["user_id"] ?? '';

if ($user_id != '') {
	$sql = "
SELECT user.*
FROM user
WHERE user.user_id = $user_id
";
	$user_data = $query->queryDB_to_row($sql);
	$applicant_text = getApplicantEducationLabel($user_data['applicant_type'] ?? 'university', $user_data['applicant_level'] ?? '');
}
?>
<html>

<head>
	<?
	getHead();
	if (!isset($_COOKIE['user_id'])) {
		echo '<script> location.href = "login.php"; </script>';
	}
	?>
</head>

<body class="pages">

	<div class="top_menu">
		<div class="top_header">ข้อมูลส่วนตัว</div>
		<a href="home.php">
			<div class="bt_back"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
		</a>
	</div>

	<div class="main profile-modern">
		<div class="container">

			<div class="profile-modern-header">

				<img id="upload"
					src="<? echo $user_data['user_img']; ?>"
					alt="โปรไฟล์"
					class="profile-modern-img">

				<div class="profile-modern-info">
					<div class="profile-modern-name">
						<? echo $user_data['user_name']; ?>
					</div>

					<div class="profile-modern-email">
						<?
						if (!empty($user_data['user_birthday'])) {
							echo "วันเกิด: " . date("d/m/Y", strtotime($user_data['user_birthday']));
						}
						?>
					</div>
					<button type="button" class="profile-modern-btn">
						แก้ไขรูปภาพ
					</button>
				</div>

			</div>

			<input id="img" name="img" type="hidden" value="<? echo $user_data['user_img']; ?>">
			<input id="uploadFile" type="file" hidden>
			<div id="show_loader"></div>

			<div class="profile-modern-list">

				<div class="profile-modern-item">
					<div class="profile-modern-left">
						<i class="fa fa-user" aria-hidden="true"></i>
						<span><? echo $user_data['user_name']; ?></span>
					</div>
				</div>

				<div class="profile-modern-item">
					<div class="profile-modern-left">
						<i class="fa fa-envelope" aria-hidden="true"></i>
						<span><? echo $user_data['user_username']; ?></span>
					</div>
				</div>

				<div class="profile-modern-item">
					<div class="profile-modern-left">
						<i class="fa fa-graduation-cap"></i>
						<span><? echo $applicant_text; ?></span>
					</div>
				</div>



				<a href="api/logout.php">
					<div class="profile-modern-item profile-modern-logout">
						<div class="profile-modern-left">
							<i class="fa fa-sign-out" aria-hidden="true"></i>
							<span>ออกจากระบบ</span>
						</div>
						<i class="fa fa-angle-right"></i>
					</div>
				</a>

			</div>

		</div>
	</div>


</body>
<script>
	$(function() {

		$("#upload, .profile-modern-btn").on("click", function(event) {
			$("#uploadFile").show().click().hide();
			event.preventDefault();
		});

		$('#uploadFile').on('change', function() {
			var file_data = $('#uploadFile').prop('files')[0];
			var form_data = new FormData();
			form_data.append('file', file_data);

			$.ajax({
				url: 'php/ajax_upload_image.php?type=edit',
				dataType: 'text',
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,
				type: 'post',
				beforeSend: function(result) {
					$("#show_loader").html('<img src="img/loader.gif" alt="รอสักครู่">');
				},
				success: function(result) {
					$("#show_loader").html(result);
				}
			});
		});

	});
</script>

</html>
