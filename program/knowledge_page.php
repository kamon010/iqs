<?php
include('api/app.php');
include_once('php/video_embed.php');

$query = new DBControl();

$page = $_GET['p'] ?? '';
$id = $_GET['id'] ?? '';

if ($page == '') {
	echo '<script>history.back();</script>';
	exit;
}

$page_sql = mysqli_real_escape_string($query->connect, $page);

if ($id == '') {
	$id = $query->queryDB_to_string(
		"SELECT MIN(id)
		 FROM page
		 WHERE type='" . $page_sql . "'"
	);
}

if ($id == '') {
	$data = array(
		'detail' => '<div class="p-3 text-center">ไม่พบข้อมูล</div>'
	);

	$pre = 0;
	$next = 0;
} else {
	$id = intval($id);

	$pre = $query->queryDB_to_string(
		"SELECT id
		 FROM page
		 WHERE type='" . $page_sql . "'
		 AND id < " . $id . "
		 ORDER BY id DESC
		 LIMIT 1"
	);

	if ($pre == '') {
		$pre = 0;
	}

	$next = $query->queryDB_to_string(
		"SELECT id
		 FROM page
		 WHERE type='" . $page_sql . "'
		 AND id > " . $id . "
		 ORDER BY id ASC
		 LIMIT 1"
	);

	if ($next == '') {
		$next = 0;
	}

	$data = $query->queryDB_to_row(
		"SELECT *
		 FROM page
		 WHERE id=" . $id . "
		 AND type='" . $page_sql . "'
		 LIMIT 1"
	);

	if (empty($data)) {
		$data = array(
			'detail' => '<div class="p-3 text-center">ไม่พบข้อมูล</div>'
		);
	}
}

function fixImagePathOnly($html)
{
	if ($html == '') {
		return $html;
	}

	$html = str_replace('src="../uploads/', 'src="uploads/', $html);
	$html = str_replace("src='../uploads/", "src='uploads/", $html);

	$html = str_replace('src="../../uploads/', 'src="uploads/', $html);
	$html = str_replace("src='../../uploads/", "src='uploads/", $html);

	$html = str_replace('src="/uploads/', 'src="uploads/', $html);
	$html = str_replace("src='/uploads/", "src='uploads/", $html);

	$html = str_replace('src="./uploads/', 'src="uploads/', $html);
	$html = str_replace("src='./uploads/", "src='uploads/", $html);

	$html = str_replace(
		'src="http://localhost/uploads/',
		'src="uploads/',
		$html
	);

	$html = str_replace(
		"src='http://localhost/uploads/",
		"src='uploads/",
		$html
	);

	return $html;
}

function cleanContentForDisplay($html)
{
	if ($html == '') {
		return $html;
	}

	$html = preg_replace_callback(
		'/<input([^>]*?)\s+src=(["\'])(.*?)\2([^>]*)>/i',
		function ($m) {
			$before = $m[1];
			$quote = $m[2];
			$src = $m[3];
			$after = $m[4];

			$before = preg_replace(
				'/\s*type=(["\']).*?\1/i',
				'',
				$before
			);

			$before = preg_replace(
				'/\s*name=(["\']).*?\1/i',
				'',
				$before
			);

			$before = preg_replace(
				'/\s*value=(["\']).*?\1/i',
				'',
				$before
			);

			$after = preg_replace(
				'/\s*type=(["\']).*?\1/i',
				'',
				$after
			);

			$after = preg_replace(
				'/\s*name=(["\']).*?\1/i',
				'',
				$after
			);

			$after = preg_replace(
				'/\s*value=(["\']).*?\1/i',
				'',
				$after
			);

			return '<img' .
				$before .
				' src=' .
				$quote .
				$src .
				$quote .
				$after .
				'>';
		},
		$html
	);

	$html = preg_replace_callback(
		'/<img\b[^>]*>/i',
		function ($m) {
			$tag = $m[0];

			$tag = preg_replace(
				'/\s*style=(["\']).*?\1/i',
				'',
				$tag
			);

			$tag = preg_replace(
				'/\s*width=(["\']).*?\1/i',
				'',
				$tag
			);

			$tag = preg_replace(
				'/\s*height=(["\']).*?\1/i',
				'',
				$tag
			);

			return $tag;
		},
		$html
	);

	$html = preg_replace(
		'/<p\b[^>]*>(?:\s|&nbsp;|&#160;|\xC2\xA0|<br\s*\/?>)*<\/p>/iu',
		'',
		$html
	);

	$html = preg_replace(
		"/\n\s*\n/",
		"\n",
		$html
	);

	return $html;
}

$data['detail'] = fixImagePathOnly(
	$data['detail'] ?? ''
);

$data['detail'] = cleanContentForDisplay(
	$data['detail']
);

$data['detail'] = trcEmbedVideoLinksInHtml(
	$data['detail']
);

$page_url = urlencode($page);
?>

<!DOCTYPE html>
<html>

<head>
	<?php getHead(); ?>

	<style>
		html,
		body {
			width: 100%;
			min-height: 100%;
			overflow-x: hidden;
		}

		body.pages.reader-page {
			margin: 0 !important;
			padding: 0 !important;
			background: #eaf3f5 !important;
		}

		.reader-page .top_menu {
			position: fixed !important;
			top: 0 !important;
			left: 0 !important;
			width: 100% !important;
			height: 70px !important;
			margin: 0 !important;
			background: #ffffff !important;
			border-radius: 0 0 18px 18px !important;
			box-shadow: 0 8px 20px rgba(0, 85, 115, 0.10) !important;
			z-index: 9999 !important;
		}

		.reader-page .bt_back {
			position: absolute !important;
			top: 50% !important;
			left: 18px !important;
			width: 40px !important;
			height: 40px !important;
			margin: 0 !important;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			transform: translateY(-50%) !important;
			color: #2C3E50 !important;
			font-size: 24px !important;
			text-align: center !important;
			cursor: pointer;
			z-index: 2;
		}

		.reader-page .top_header {
			position: absolute !important;
			top: 50% !important;
			left: 64px !important;
			right: 64px !important;
			width: auto !important;
			margin: 0 !important;
			transform: translateY(-50%) !important;
			color: #2C3E50 !important;
			font-size: 20px !important;
			font-weight: 700 !important;
			text-align: center !important;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		.reader-page .main_page {
			width: calc(100% - 20px) !important;
			max-width: 820px !important;
			margin: 92px auto 90px !important;
			padding: 14px !important;
			background: #ffffff !important;
			border-radius: 12px !important;
			box-sizing: border-box !important;
			overflow: hidden !important;
			box-shadow: 0 6px 22px rgba(0, 85, 115, 0.06);
		}

		.reader-page .content-inner {
			width: 100% !important;
			max-width: 100% !important;
			margin: 0 auto !important;
			padding: 0 !important;
			text-align: center !important;
			box-sizing: border-box !important;
			overflow: hidden !important;
		}

		.reader-page .content-inner p {
			width: 100% !important;
			max-width: 100% !important;
			height: auto !important;
			min-height: 0 !important;
			margin: 0 0 12px !important;
			padding: 0 !important;
			text-align: center !important;
			overflow: visible !important;
		}

		.reader-page .content-inner img,
		.reader-page .content-inner input[type="image"] {
			display: block !important;
			width: auto !important;
			max-width: 100% !important;
			height: auto !important;
			max-height: none !important;
			margin: 0 auto !important;
			padding: 0 !important;
			object-fit: contain !important;
			border: 0 !important;
			box-sizing: border-box !important;
		}

		.reader-page .content-inner figure {
			width: 100% !important;
			max-width: 100% !important;
			margin-left: auto !important;
			margin-right: auto !important;
		}

		.reader-page .content-inner iframe,
		.reader-page .content-inner video {
			width: 100% !important;
			max-width: 100% !important;
			height: auto;
			margin-left: auto !important;
			margin-right: auto !important;
		}

		.reader-page .content-inner table {
			width: 100% !important;
			max-width: 100% !important;
			display: block !important;
			overflow-x: auto !important;
		}

		.reader-page .footer {
			position: fixed !important;
			left: 50% !important;
			bottom: 0 !important;
			width: min(calc(100% - 30px), 820px) !important;
			height: 62px !important;
			margin: 0 !important;
			padding: 0 !important;
			transform: translateX(-50%) !important;
			background: rgba(255, 255, 255, 0.97) !important;
			border-radius: 16px 16px 0 0 !important;
			box-shadow: 0 -6px 20px rgba(0, 85, 115, 0.10) !important;
			z-index: 9999 !important;
		}

		.reader-page .footer>.container {
			width: 100% !important;
			max-width: none !important;
			height: 100% !important;
			margin: 0 !important;
			padding: 7px 12px !important;
			display: grid !important;
			grid-template-columns: 1fr 90px 1fr !important;
			align-items: center !important;
			box-sizing: border-box !important;
		}

		.reader-page .footer-slot {
			width: 100%;
			height: 100%;
			display: flex;
			align-items: center;
		}

		.reader-page .footer-left {
			justify-content: flex-start;
		}

		.reader-page .footer-center {
			justify-content: center;
		}

		.reader-page .footer-right {
			justify-content: flex-end;
		}

		.reader-page .footer-slot a {
			display: flex;
			align-items: center;
			justify-content: center;
			color: inherit;
			text-decoration: none;
		}

		.reader-page .footer .menu {
			float: none !important;
			width: 46px !important;
			height: 46px !important;
			margin: 0 !important;
			padding: 0 !important;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			color: #536775 !important;
			font-size: 17px !important;
			border: 0 !important;
		}

		.reader-page .footer .menu.active {
			width: 76px !important;
			height: 38px !important;
			color: #ffffff !important;
			background: linear-gradient(135deg,
					#405568,
					#617383) !important;
			border-radius: 13px !important;
			box-shadow: 0 4px 12px rgba(44, 62, 80, 0.20) !important;
		}

		.reader-page .footer .menu.active i {
			color: #ffffff !important;
		}

		.reader-page .footer .menu:not(.active) {
			border-radius: 12px !important;
			transition:
				background 0.2s ease,
				transform 0.2s ease;
		}

		.reader-page .footer .menu:not(.active):hover {
			background: #eef4f6 !important;
			transform: translateY(-1px);
		}

		@media (max-width: 575px) {
			.reader-page .top_menu {
				height: 62px !important;
			}

			.reader-page .bt_back {
				left: 8px !important;
				width: 42px !important;
				height: 42px !important;
				font-size: 22px !important;
			}

			.reader-page .top_header {
				left: 52px !important;
				right: 52px !important;
				font-size: 16px !important;
			}

			.reader-page .main_page {
				width: calc(100% - 16px) !important;
				margin-top: 76px !important;
				margin-bottom: 82px !important;
				padding: 8px !important;
				border-radius: 10px !important;
			}

			.reader-page .content-inner {
				width: 100% !important;
			}

			.reader-page .content-inner img,
			.reader-page .content-inner input[type="image"] {
				width: 100% !important;
				max-width: 100% !important;
				height: auto !important;
			}

			.reader-page .footer {
				width: calc(100% - 16px) !important;
				height: 60px !important;
			}

			.reader-page .footer>.container {
				padding: 7px 10px !important;
				grid-template-columns: 1fr 82px 1fr !important;
			}

			.reader-page .footer .menu {
				width: 44px !important;
				height: 44px !important;
				font-size: 16px !important;
			}

			.reader-page .footer .menu.active {
				width: 70px !important;
				height: 36px !important;
			}
		}

		@media (min-width: 576px) and (max-width: 767px) {
			.reader-page .main_page {
				max-width: 540px !important;
			}

			.reader-page .footer {
				width: min(calc(100% - 30px), 540px) !important;
			}
		}

		@media (min-width: 768px) and (max-width: 991px) {
			.reader-page .main_page {
				max-width: 720px !important;
			}

			.reader-page .footer {
				width: min(calc(100% - 30px), 720px) !important;
			}
		}

		@media (min-width: 992px) and (max-width: 1399px) {
			.reader-page .main_page {
				max-width: 760px !important;
			}

			.reader-page .footer {
				width: min(calc(100% - 30px), 760px) !important;
			}
		}

		@media (min-width: 1400px) {
			.reader-page .main_page {
				max-width: 820px !important;
			}

			.reader-page .footer {
				width: min(calc(100% - 30px), 820px) !important;
			}
		}
	</style>
</head>

<body class="pages reader-page">

	<div class="top_menu">
		<div class="top_header">
			<?php echo htmlspecialchars($page, ENT_QUOTES, 'UTF-8'); ?>
		</div>

		<div
			class="bt_back"
			onclick="history.back()">
			<i
				class="fas fa-angle-left"
				aria-hidden="true"></i>
		</div>
	</div>

	<div class="main main_page">
		<div class="container p-0 content-inner">
			<?php echo $data['detail']; ?>
		</div>
	</div>

	<div class="footer">
		<div class="container">

			<div class="footer-slot footer-left">
				<?php if (intval($pre) > 0) { ?>
					<a
						href="knowledge_page.php?p=<?php echo $page_url; ?>&id=<?php echo intval($pre); ?>">
						<div class="menu">
							<i
								class="fa fa-chevron-left"
								aria-hidden="true"></i>
						</div>
					</a>
				<?php } ?>
			</div>

			<div class="footer-slot footer-center">
				<a href="home.php">
					<div class="menu active">
						<i
							class="fa fa-home"
							aria-hidden="true"></i>
					</div>
				</a>
			</div>

			<div class="footer-slot footer-right">
				<?php if (intval($next) > 0) { ?>
					<a
						href="knowledge_page.php?p=<?php echo $page_url; ?>&id=<?php echo intval($next); ?>">
						<div class="menu">
							<i
								class="fa fa-chevron-right"
								aria-hidden="true"></i>
						</div>
					</a>
				<?php } ?>
			</div>

		</div>
	</div>

</body>

</html>