<?php

declare(strict_types=1);

include __DIR__ . '/connect.php';

$dbPath = '';
if (!empty($_FILES['download']['name']) && ($_FILES['download']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
    $filename = md5(uniqid((string) mt_rand(), true));
    $ext = pathinfo((string) $_FILES['download']['name'], PATHINFO_EXTENSION);
    $uploadDir = __DIR__ . '/upload';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $dbPath = 'upload/' . $filename . '.' . $ext;
    move_uploaded_file((string) $_FILES['download']['tmp_name'], __DIR__ . '/' . $dbPath);
}

$name = (string) ($_POST['name'] ?? '');
$phone = (string) ($_POST['tel-number'] ?? '');
$stmt = $mysqli->prepare('INSERT INTO `feedback` (`name`, `phone`, `path`) VALUES (?,?,?)');
if ($stmt) {
    $stmt->bind_param('sss', $name, $phone, $dbPath);
    $stmt->execute();
    $stmt->close();
}

header('Location: answer.php');
exit;
