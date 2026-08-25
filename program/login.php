<?php
include('api/app.php');
?>

<html>

<head>
	<?php
	getHead();
	?>
	<link rel="stylesheet" href="css/login.css">
</head>

<body class="pages_2">
	<div class="login-page">
		<div class="login-container">
			<div class="login-header">
				<div class="logo-box">
					<img src="img/logo.png" alt="TRC Logo">
				</div>
				<!-- <h1>
					Welcome<br>
					Back!
				</h1> -->
				<p>
					Already Registered? Login here.
				</p>
			</div>
			<div class="login-form">
				<div class="form-group">
					<label
						class="form-label"
						for="xxyy">
						ชื่อผู้ใช้
					</label>
					<div class="input-wrapper">
						<i class="fa fa-user input-icon"></i>
						<input
							class="login-input"
							id="xxyy"
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
							class="login-input pw"
							id="ppww"
							type="password"
							placeholder="รหัสผ่าน"
							autocomplete="current-password">
					</div>
				</div>
				<div class="login-alert">
					<div
						id="alertlogin"
						class="text-danger"></div>
				</div>
				<button
					id="bt_login"
					class="login-btn"
					type="button">
					<i class="fa fa-sign-in"></i>
					<span>
						เข้าสู่ระบบ
					</span>
				</button>
			</div>
			<div class="or-divider">

			</div>
			<div class="social-login">

				<a
					href="https://www.trc.or.th/"
					target="_blank"
					rel="noopener noreferrer"
					class="social-item social-link"
					aria-label="TRC Website">
					<img
						src="img/icon/website.png"
						alt="TRC Website">
				</a>

				<a
					href="https://www.facebook.com/TRCTobaccoControlResearch"
					target="_blank"
					rel="noopener noreferrer"
					class="social-item social-link"
					aria-label="Facebook TRC Tobacco Control Research">
					<img
						src="img/icon/facebook.png"
						alt="Facebook">
				</a>

				<a
					href="https://www.tiktok.com/@trc_production"
					target="_blank"
					rel="noopener noreferrer"
					class="social-item social-link"
					aria-label="TikTok TRC Production">
					<img
						src="img/icon/tiktok.jpg"
						alt="TikTok">
				</a>

			</div>
			<div class="register-box">
				<span>
					ยังไม่มีบัญชี?
				</span>
				<a href="intro_1.php">
					สมัครสมาชิก
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
			function login() {
				$("#alertlogin").html("");
				$.ajax({
					type: "POST",
					url: "api/login.php",
					data: {
						xxyy: $("#xxyy").val(),
						ppww: $("#ppww").val()
					},

					success: function(result) {
						$("#alertlogin").html(result);
					},

					error: function() {
						$("#alertlogin").html(
							"ไม่สามารถเชื่อมต่อระบบได้ กรุณาลองใหม่"
						);
					}
				});
			}

			$("#bt_login").click(function(event) {
				event.preventDefault();
				login();

			});

			$("#xxyy, #ppww").keydown(function(event) {
				if (event.key === "Enter") {
					event.preventDefault();
					login();
				}
			});
		});
	</script>
</body>

</html>