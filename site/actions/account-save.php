<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';
require dirname(__DIR__) . '/connect.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('user-info.php');
}

$user = current_user();
$uid = (int) $user['id'];
$email = strtolower(trim((string) ($_POST['email'] ?? '')));
$nickname = trim((string) ($_POST['nickname'] ?? ''));
$password = (string) ($_POST['password'] ?? '');
if ($nickname === '') {
    $nickname = 'user' . $uid;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash('account_err', __('auth.email_invalid'));
    redirect('user-info.php');
}

$stmt = $mysqli->prepare('SELECT id FROM users WHERE email = ? AND is_deleted = 0 AND id <> ? LIMIT 1');
$stmt->bind_param('si', $email, $uid);
$stmt->execute();
$exists = $stmt->get_result()->fetch_assoc();
$stmt->close();
if ($exists) {
    set_flash('account_err', __('auth.email_exists'));
    redirect('user-info.php');
}

$avatarPath = null;
if (isset($_FILES['avatar']) && is_array($_FILES['avatar']) && (int) ($_FILES['avatar']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
    $errCode = (int) ($_FILES['avatar']['error'] ?? UPLOAD_ERR_OK);
    if ($errCode !== UPLOAD_ERR_OK) {
        set_flash('account_err', __('account.avatar_upload_failed'));
        redirect('user-info.php');
    }
    $tmpPath = (string) ($_FILES['avatar']['tmp_name'] ?? '');
    $maxBytes = 3 * 1024 * 1024;
    $size = (int) ($_FILES['avatar']['size'] ?? 0);
    if ($size < 1 || $size > $maxBytes) {
        set_flash('account_err', __('account.avatar_upload_failed'));
        redirect('user-info.php');
    }
    $mime = (string) (mime_content_type($tmpPath) ?: '');
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];
    if (!isset($allowed[$mime])) {
        set_flash('account_err', __('account.avatar_upload_failed'));
        redirect('user-info.php');
    }
    $dirAbs = ROOT_PATH . '/uploads/avatars';
    if (!is_dir($dirAbs) && !mkdir($dirAbs, 0775, true) && !is_dir($dirAbs)) {
        set_flash('account_err', __('account.avatar_upload_failed'));
        redirect('user-info.php');
    }
    $filename = 'u' . $uid . '_' . bin2hex(random_bytes(6)) . '.' . $allowed[$mime];
    $destAbs = $dirAbs . '/' . $filename;
    if (!move_uploaded_file($tmpPath, $destAbs)) {
        set_flash('account_err', __('account.avatar_upload_failed'));
        redirect('user-info.php');
    }
    $avatarPath = 'uploads/avatars/' . $filename;
}

if ($password !== '' && strlen($password) < 6) {
    set_flash('account_err', __('auth.register_invalid'));
    redirect('user-info.php');
}
$passwordHash = $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : null;

$hasAvatarPath = false;
$colRes = $mysqli->query("SHOW COLUMNS FROM users LIKE 'avatar_path'");
if ($colRes && $colRes->fetch_assoc()) {
    $hasAvatarPath = true;
}

if ($hasAvatarPath) {
    $upd = $mysqli->prepare(
        'UPDATE users
         SET email = ?, nickname = ?,
             avatar_path = COALESCE(?, avatar_path),
             password_hash = COALESCE(?, password_hash)
         WHERE id = ? LIMIT 1'
    );
    $upd->bind_param('ssssi', $email, $nickname, $avatarPath, $passwordHash, $uid);
} else {
    $upd = $mysqli->prepare(
        'UPDATE users
         SET email = ?, nickname = ?,
             password_hash = COALESCE(?, password_hash)
         WHERE id = ? LIMIT 1'
    );
    $upd->bind_param('sssi', $email, $nickname, $passwordHash, $uid);
}
$upd->execute();
$upd->close();

$sessionAvatar = $avatarPath;
if ($sessionAvatar === null && $hasAvatarPath) {
    $avStmt = $mysqli->prepare('SELECT avatar_path FROM users WHERE id = ? LIMIT 1');
    if ($avStmt) {
        $avStmt->bind_param('i', $uid);
        $avStmt->execute();
        $avRow = $avStmt->get_result()->fetch_assoc();
        $avStmt->close();
        if ($avRow && !empty($avRow['avatar_path'])) {
            $sessionAvatar = (string) $avRow['avatar_path'];
        }
    }
}

set_session_user([
    'id' => $uid,
    'email' => $email,
    'nickname' => $nickname,
    'avatar_path' => $sessionAvatar,
]);
set_flash('account_ok', __('auth.account_saved'));
redirect('user-info.php');
