<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';
require dirname(__DIR__) . '/connect_practice.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('practice10.php');
}

$user = current_user();
$uid = (int) ($user['id'] ?? 0);
$name = trim((string) ($_POST['name'] ?? ''));
$phone = trim((string) ($_POST['phone'] ?? ''));
$note = trim((string) ($_POST['note'] ?? ''));

if ($name === '' || $phone === '') {
    set_flash('practice10_err', current_lang() === 'en' ? 'Fill in required fields.' : 'Заполните обязательные поля.');
    redirect('practice10.php');
}

$filePath = null;
if (isset($_FILES['attachment']) && is_array($_FILES['attachment']) && (int) ($_FILES['attachment']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
    $errCode = (int) ($_FILES['attachment']['error'] ?? UPLOAD_ERR_OK);
    if ($errCode !== UPLOAD_ERR_OK) {
        set_flash('practice10_err', current_lang() === 'en' ? 'File upload failed.' : 'Ошибка загрузки файла.');
        redirect('practice10.php');
    }
    $tmpPath = (string) ($_FILES['attachment']['tmp_name'] ?? '');
    $size = (int) ($_FILES['attachment']['size'] ?? 0);
    if ($size < 1 || $size > (5 * 1024 * 1024)) {
        set_flash('practice10_err', current_lang() === 'en' ? 'File size must be up to 5MB.' : 'Размер файла должен быть до 5MB.');
        redirect('practice10.php');
    }

    $ext = strtolower((string) pathinfo((string) ($_FILES['attachment']['name'] ?? ''), PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'pdf', 'txt'];
    if ($ext === '' || !in_array($ext, $allowed, true)) {
        set_flash('practice10_err', current_lang() === 'en' ? 'Unsupported file type.' : 'Недопустимый тип файла.');
        redirect('practice10.php');
    }

    $dirAbs = ROOT_PATH . '/uploads/practice10';
    if (!is_dir($dirAbs) && !mkdir($dirAbs, 0775, true) && !is_dir($dirAbs)) {
        set_flash('practice10_err', current_lang() === 'en' ? 'Failed to create upload directory.' : 'Не удалось создать папку для загрузки.');
        redirect('practice10.php');
    }

    $fileName = 'p10_u' . $uid . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
    $destAbs = $dirAbs . '/' . $fileName;
    if (!move_uploaded_file($tmpPath, $destAbs)) {
        set_flash('practice10_err', current_lang() === 'en' ? 'Failed to save uploaded file.' : 'Не удалось сохранить загруженный файл.');
        redirect('practice10.php');
    }
    $filePath = 'uploads/practice10/' . $fileName;
}

$stmt = $mysqli_practice->prepare('INSERT INTO practice10_feedback (user_id, name, phone, note, file_path) VALUES (?,?,?,?,?)');
if ($stmt === false) {
    set_flash('practice10_err', current_lang() === 'en' ? 'Insert query failed.' : 'Не удалось записать данные в БД.');
    redirect('practice10.php');
}
$stmt->bind_param('issss', $uid, $name, $phone, $note, $filePath);
$stmt->execute();
$stmt->close();

set_flash('practice10_ok', current_lang() === 'en' ? 'Practice #10 record saved.' : 'Запись практики №10 сохранена.');
redirect('practice10.php');
