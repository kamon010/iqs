<?php
// วางไฟล์นี้แทนที่: program/admin/php/ajax_upload_image.php
// ใช้คู่กับ picture_edit_fixed.php

header('Content-Type: application/json; charset=utf-8');

$fieldName = isset($_GET['name']) && $_GET['name'] !== '' ? $_GET['name'] : 'detail';

if (!isset($_FILES['file'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ไม่พบไฟล์ที่อัปโหลด'
    ]);
    exit;
}

$file = $_FILES['file'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode([
        'success' => false,
        'message' => 'อัปโหลดไฟล์ไม่สำเร็จ error code: ' . $file['error']
    ]);
    exit;
}

$originalName = $file['name'];
$tmpName = $file['tmp_name'];
$ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];

if (!in_array($ext, $allowedExtensions, true)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid file format. รองรับเฉพาะ jpg, jpeg, png, gif, webp, pdf เท่านั้น'
    ]);
    exit;
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $tmpName);
finfo_close($finfo);

$allowedMimeTypes = [
    'jpg'  => ['image/jpeg'],
    'jpeg' => ['image/jpeg'],
    'png'  => ['image/png'],
    'gif'  => ['image/gif'],
    'webp' => ['image/webp'],
    'pdf'  => ['application/pdf']
];

if (!isset($allowedMimeTypes[$ext]) || !in_array($mime, $allowedMimeTypes[$ext], true)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid file format. ชนิดไฟล์จริงไม่ตรงกับนามสกุลไฟล์',
        'debug_mime' => $mime
    ]);
    exit;
}

$uploadDir = __DIR__ . '/../../uploads/images/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$newFileName = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
$targetPath = $uploadDir . $newFileName;

if (!move_uploaded_file($tmpName, $targetPath)) {
    echo json_encode([
        'success' => false,
        'message' => 'ไม่สามารถบันทึกไฟล์ลงโฟลเดอร์ uploads/images ได้'
    ]);
    exit;
}

$savePath = 'uploads/images/' . $newFileName;
$previewPath = '../' . $savePath;

$isPdf = ($ext === 'pdf');

if ($isPdf) {
    $previewHtml = '<div id="previewFile" class="file-preview"><iframe src="' . htmlspecialchars($previewPath, ENT_QUOTES, 'UTF-8') . '" width="100%" height="500px"></iframe><div class="card_bt" onclick="deleteFile(\'' . htmlspecialchars($newFileName, ENT_QUOTES, 'UTF-8') . '\', event)"><i class="fa fa-trash"></i></div></div>';
} else {
    $previewHtml = '<div id="previewFile" class="card file-preview"><img src="' . htmlspecialchars($previewPath, ENT_QUOTES, 'UTF-8') . '" style="height:150px"><div class="card_bt" onclick="deleteFile(\'' . htmlspecialchars($newFileName, ENT_QUOTES, 'UTF-8') . '\', event)"><i class="fa fa-trash"></i></div></div>';
}

// คืนค่าเป็น JSON เพื่อให้หน้า picture_edit.php จัดการต่อได้แน่นอน
// path คือค่าที่จะเก็บใน input name="detail" และบันทึกลง DB
echo json_encode([
    'success' => true,
    'field' => $fieldName,
    'path' => $savePath,
    'file_name' => $newFileName,
    'preview_html' => $previewHtml
]);
