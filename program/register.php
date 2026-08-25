<?php
include('api/app.php');
include_once('php/applicant_options.php');

$query = new DBControl();

$user_id = $_COOKIE["user_id"] ?? '';

$applicant_types = getApplicantTypeOptions();

$applicant_levels = array(
	'university' => getApplicantLevelOptions('university'),
	'secondary' => getApplicantLevelOptions('secondary'),
	'primary' => getApplicantLevelOptions('primary'),
	'general' => getApplicantLevelOptions('general')
);
?>

<html>

<head>
	<?php
	getHead();
	?>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="css/login.css">
	<link rel="stylesheet" href="css/register.css">
</head>

<body class="pages_2">

	<div class="register-page">
		<div class="register-container">
			<div class="register-header">
				<div class="logo-box">
					<img src="img/logo.png" alt="TRC Logo">
				</div>
				<p>Already Registered here.</p>
			</div>

			<form id="regis_from" class="register-form">
				<div class="profile-upload">
					<input
						id="img"
						name="img"
						type="hidden"
						value="img/user.png">
					<input
						id="uploadFile"
						type="file"
						accept="image/*"
						hidden>
					<button
						type="button"
						class="profile-button"
						id="profileButton">
						<img
							id="upload"
							src="img/user.png"
							alt="อัพโหลดรูปภาพ">
					</button>
					<div class="profile-upload-text">
						แตะเพื่อเลือกรูปภาพ
					</div>
					<div id="show_loader"></div>
				</div>
				<div class="register-grid">
					<div class="form-group">
						<label
							class="form-label"
							for="applicant_type">
							ประเภทผู้สมัคร
						</label>
						<div class="input-wrapper">
							<i class="fa fa-user input-icon"></i>
							<select
								class="register-input register-select"
								name="applicant_type"
								id="applicant_type">
								<option value="">
									== เลือกประเภทผู้สมัคร ==
								</option>
								<?php
								foreach ($applicant_types as $key => $label) {
									echo '<option value="' . $key . '">' . $label . '</option>';
								}
								?>
							</select>
						</div>
					</div>
					<div
						class="form-group"
						id="applicant_level_wrap">
						<label
							class="form-label"
							for="applicant_level">
							ระดับ
						</label>
						<div class="input-wrapper">
							<i class="fa fa-graduation-cap input-icon"></i>
							<select
								class="register-input register-select"
								name="applicant_level"
								id="applicant_level">
								<option value="">
									== เลือกระดับ ==
								</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label
							class="form-label"
							for="eemm">
							ชื่อผู้ใช้
						</label>
						<div class="input-wrapper">
							<i class="fa fa-user input-icon"></i>
							<input
								class="register-input"
								id="eemm"
								name="eemm"
								type="text"
								placeholder="ชื่อผู้ใช้"
								autocomplete="username">
						</div>
					</div>
					<div class="form-group">
						<label
							class="form-label"
							for="ppww">
							รหัสผ่าน
						</label>
						<div class="input-wrapper">
							<i class="fa fa-lock input-icon"></i>
							<input
								class="register-input"
								id="ppww"
								name="ppww"
								type="password"
								placeholder="รหัสผ่าน"
								autocomplete="new-password">
						</div>
					</div>
					<!-- <div class="form-group">
                        <label
                            class="form-label"
                            for="name"
                        >
                            ชื่อ - นามสกุล
                        </label>
                        <div class="input-wrapper">
                            <i class="fa fa-id-card input-icon"></i>
                            <input
                                class="register-input"
                                id="name"
                                name="name"
                                type="text"
                                placeholder="ชื่อ - นามสกุล"
                            >
                        </div>
                    </div> -->
					<div class="form-group">

						<label
							class="form-label"
							for="birthday">
							วัน เดือน ปีเกิด
						</label>

						<div class="input-wrapper">

							<i class="fa fa-calendar input-icon"></i>

							<input
								class="register-input"
								id="birthday"
								name="birthday"
								type="date">

						</div>

					</div>
					<div class="form-group form-group-full">
						<div class="form-label">
							เพศ
						</div>
						<div class="gender-options">
							<label class="gender-option">
								<input
									type="radio"
									name="gender"
									value="ชาย">
								<span>ชาย</span>
							</label>
							<label class="gender-option">
								<input
									type="radio"
									name="gender"
									value="หญิง">
								<span>หญิง</span>
							</label>
							<label class="gender-option">
								<input
									type="radio"
									name="gender"
									value="อื่นๆ">
								<span>อื่นๆ</span>
							</label>
						</div>
					</div>
					<div class="pdpa-section form-group-full">
						<label class="pdpa-item">
							<input
								type="checkbox"
								id="pdpa1"
								name="pdpa1"
								value="1">
							<span>
								ข้าพเจ้ายินยอมให้ศูนย์วิจัยและจัดการความรู้เพื่อการควบคุมยาสูบ (ศจย.) เก็บและใช้ข้อมูลส่วนบุคคลของข้าพเจ้า เพื่อวัตถุประสงค์ด้านการศึกษา การวิจัย และการให้บริการ
							</span>
						</label>
						<label class="pdpa-item">
							<input
								type="checkbox"
								id="pdpa2"
								name="pdpa2"
								value="1">
							<span>
								ข้าพเจ้ายินยอมให้ศูนย์วิจัยและจัดการความรู้เพื่อการควบคุมยาสูบ (ศจย.) เปิดเผยหรือส่งต่อข้อมูลส่วนบุคคลแก่หน่วยงานภายนอกที่เกี่ยวข้อง ตามความจำเป็นและเป็นไปตามกฎหมาย
							</span>
						</label>
						<label class="pdpa-item">
							<input
								type="checkbox"
								id="pdpa3"
								name="pdpa3"
								value="0">
							<span>
								ข้าพเจ้าไม่ยินยอมให้เก็บ ใช้ หรือเปิดเผยข้อมูลส่วนบุคคลของข้าพเจ้าเกินกว่าที่จำเป็น
							</span>
						</label>
					</div>
					<div class="register-alert form-group-full">
						<div
							id="alertlogin"
							class="text-danger"></div>
					</div>
					<div class="form-group-full">
						<button
							id="sent"
							class="login-btn"
							type="submit">
							<i class="fa fa-user-plus"></i>
							<span>
								สมัครสมาชิก
							</span>
						</button>
					</div>
				</div>
			</form>

			<div class="login-link">
				<span>
					มีบัญชีอยู่แล้ว?
				</span>
				<a href="login.php">
					เข้าสู่ระบบ
				</a>
			</div>
			<div class="copyright-box">
				<?php
				getCopyRight();
				?>
			</div>
		</div>
	</div>

	<script>
		$(function() {

			var applicantLevels = <?php
									echo json_encode(
										$applicant_levels,
										JSON_UNESCAPED_UNICODE
									);
									?>;

			function renderApplicantLevel() {
				var type = $('#applicant_type').val();
				var levels = applicantLevels[type] || {};
				var html =
					'<option value="">== เลือกระดับ ==</option>';
				var hasLevel = false;
				$.each(levels, function(value, label) {
					hasLevel = true;
					html +=
						'<option value="' +
						value +
						'">' +
						label +
						'</option>';
				});
				$('#applicant_level').html(html);
				if (hasLevel) {
					$('#applicant_level_wrap').show();
				} else {
					$('#applicant_level_wrap').hide();
					$('#applicant_level').val('');
				}
			}

			$('#applicant_type').on(
				'change',
				renderApplicantLevel
			);

			renderApplicantLevel();
			$('#sent').click(function(event) {
				event.preventDefault();
				$.ajax({
					type: 'POST',
					url: 'api/register.php',
					data: $('#regis_from').serialize(),
					success: function(result) {
						$('#alertlogin').html(result);
					}
				});
			});

			$('#profileButton, #upload').on(
				'click',
				function(event) {
					event.preventDefault();
					$('#uploadFile').trigger('click');

				}
			);

			$('#uploadFile').on(
				'change',
				function() {
					var fileData =
						$('#uploadFile').prop('files')[0];
					if (!fileData) {
						return;
					}
					var formData = new FormData();
					formData.append(
						'file',
						fileData
					);

					$.ajax({

						url: 'php/ajax_upload_image.php?type=insert',
						dataType: 'text',
						cache: false,
						contentType: false,
						processData: false,
						data: formData,
						type: 'post',
						beforeSend: function() {
							$('#show_loader').html(
								'<img src="img/loader.gif" alt="รอสักครู่">'
							);
						},

						success: function(result) {
							$('#show_loader').html(result);
						}
					});
				}
			);
		});
	</script>
</body>

</html>