<?php
function trcGetVideoEmbed($videoValue)
{
	$videoValue = trim(html_entity_decode((string) $videoValue, ENT_QUOTES, 'UTF-8'));

	if ($videoValue === '') {
		return array(
			'type' => 'empty',
			'src' => '',
			'open_url' => '',
			'label' => ''
		);
	}

	if (preg_match('~tiktok\.com/player/v1/([0-9]+)~i', $videoValue, $match) ||
		preg_match('~tiktok\.com/@[^/]+/video/([0-9]+)~i', $videoValue, $match) ||
		preg_match('~tiktok\.com/.*/video/([0-9]+)~i', $videoValue, $match)) {
		$videoId = $match[1];
		$openUrl = $videoValue;

		if (preg_match('~tiktok\.com/player/v1/[0-9]+~i', $openUrl)) {
			$openUrl = 'https://www.tiktok.com/@/video/' . $videoId;
		}

		return array(
			'type' => 'tiktok',
			'src' => 'https://www.tiktok.com/player/v1/' . rawurlencode($videoId) . '?controls=1&progress_bar=1&play_button=1&fullscreen_button=1&timestamp=1&rel=0',
			'open_url' => $openUrl,
			'label' => 'เปิดบน TikTok',
			'video_id' => $videoId
		);
	}

	if (preg_match('~drive\.google\.com/file/d/([^/]+)~i', $videoValue, $match) ||
		preg_match('~drive\.google\.com/open\?id=([^&]+)~i', $videoValue, $match) ||
		preg_match('~drive\.google\.com/uc\?id=([^&]+)~i', $videoValue, $match)) {
		$fileId = $match[1];

		return array(
			'type' => 'drive',
			'src' => 'https://drive.google.com/file/d/' . rawurlencode($fileId) . '/preview',
			'open_url' => 'https://drive.google.com/file/d/' . rawurlencode($fileId) . '/view',
			'label' => 'เปิดวิดีโอ'
		);
	}

	if (preg_match('~youtube\.com/watch\?(?:[^#]*&)?v=([A-Za-z0-9_-]+)~i', $videoValue, $match) ||
		preg_match('~youtube\.com/embed/([A-Za-z0-9_-]+)~i', $videoValue, $match) ||
		preg_match('~youtube\.com/shorts/([A-Za-z0-9_-]+)~i', $videoValue, $match) ||
		preg_match('~youtu\.be/([A-Za-z0-9_-]+)~i', $videoValue, $match)) {
		$videoValue = $match[1];
	}

	if (preg_match('~^[A-Za-z0-9_-]{6,}$~', $videoValue)) {
		return array(
			'type' => 'youtube',
			'src' => 'https://www.youtube.com/embed/' . rawurlencode($videoValue) . '?controls=1&playsinline=1&rel=0&modestbranding=1&fs=1',
			'open_url' => '',
			'label' => ''
		);
	}

	if (preg_match('~\.(mp4|webm|ogg)(?:\?.*)?$~i', $videoValue)) {
		return array(
			'type' => 'file',
			'src' => $videoValue,
			'open_url' => $videoValue,
			'label' => 'เปิดวิดีโอ'
		);
	}

	return array(
		'type' => 'url',
		'src' => '',
		'open_url' => $videoValue,
		'label' => 'เปิดลิงก์วิดีโอ'
	);
}

function trcRenderVideoEmbed($videoValue, $title = 'Video')
{
	$video = trcGetVideoEmbed($videoValue);
	$type = $video['type'];

	if ($type === 'empty') {
		return '<div class="trc-video-empty">ยังไม่ได้ตั้งค่าวิดีโอ</div>';
	}

	$safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
	$safeSrc = htmlspecialchars($video['src'], ENT_QUOTES, 'UTF-8');
	$safeOpenUrl = htmlspecialchars($video['open_url'], ENT_QUOTES, 'UTF-8');
	$safeLabel = htmlspecialchars($video['label'], ENT_QUOTES, 'UTF-8');

	if ($type === 'tiktok') {
		$html = '<div class="trc-video-embed-wrap trc-video-embed-tiktok">';
		$html .= '<iframe src="' . $safeSrc . '" title="' . $safeTitle . '" frameborder="0" referrerpolicy="origin" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share; fullscreen" allowfullscreen></iframe>';
		$html .= '</div>';
		$html .= '<div class="text-center"><a class="video-open-link" href="' . $safeOpenUrl . '" target="_blank" rel="noopener">' . $safeLabel . '</a></div>';

		return $html;
	}

	if ($type === 'youtube' || $type === 'drive') {
		$wrapClass = 'trc-video-embed-wrap';

		$html = '<div class="' . $wrapClass . '">';
		$html .= '<iframe src="' . $safeSrc . '" title="' . $safeTitle . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share; fullscreen" allowfullscreen></iframe>';
		$html .= '</div>';

		if ($safeOpenUrl !== '') {
			$html .= '<div class="text-center"><a class="video-open-link" href="' . $safeOpenUrl . '" target="_blank" rel="noopener">' . $safeLabel . '</a></div>';
		}

		return $html;
	}

	if ($type === 'file') {
		$html = '<div class="trc-video-embed-wrap"><video controls playsinline preload="metadata" src="' . $safeSrc . '"></video></div>';

		if ($safeOpenUrl !== '') {
			$html .= '<div class="text-center"><a class="video-open-link" href="' . $safeOpenUrl . '" target="_blank" rel="noopener">' . $safeLabel . '</a></div>';
		}

		return $html;
	}

	return '<div class="trc-video-link-card"><a href="' . $safeOpenUrl . '" target="_blank" rel="noopener"><i class="fa fa-external-link" aria-hidden="true"></i> ' . $safeLabel . '</a></div>';
}

function trcIsEmbeddableVideoUrl($value)
{
	$type = trcGetVideoEmbed($value)['type'];
	return in_array($type, array('youtube', 'tiktok', 'drive', 'file'), true);
}

function trcEmbedVideoLinksInHtml($html)
{
	if ($html === null || trim((string) $html) === '') {
		return $html;
	}

	$html = preg_replace_callback('/<p\b[^>]*>\s*(?:<a\b[^>]*href=(["\'])(https?:\/\/[^"\']+)\1[^>]*>.*?<\/a>|(https?:\/\/[^\s<]+))\s*<\/p>/is', function ($match) {
		$url = isset($match[2]) && $match[2] !== '' ? $match[2] : $match[3];
		$url = html_entity_decode($url, ENT_QUOTES, 'UTF-8');

		if (trcIsEmbeddableVideoUrl($url)) {
			return trcRenderVideoEmbed($url);
		}

		return $match[0];
	}, $html);

	$html = preg_replace_callback('/<a\b[^>]*href=(["\'])(https?:\/\/[^"\']+)\1[^>]*>.*?<\/a>/is', function ($match) {
		if (stripos($match[0], 'video-open-link') !== false ||
			stripos($match[0], 'trc-tiktok-open') !== false ||
			stripos($match[0], 'trc-tiktok-button') !== false) {
			return $match[0];
		}

		$url = html_entity_decode($match[2], ENT_QUOTES, 'UTF-8');

		if (trcIsEmbeddableVideoUrl($url)) {
			return trcRenderVideoEmbed($url);
		}

		return $match[0];
	}, $html);

	$html = preg_replace_callback('/(?<!["\'=])(https?:\/\/[^\s<]+)/i', function ($match) {
		$url = rtrim($match[1], '.,);');

		if (trcIsEmbeddableVideoUrl($url)) {
			return trcRenderVideoEmbed($url);
		}

		return $match[0];
	}, $html);

	return $html;
}
?>
