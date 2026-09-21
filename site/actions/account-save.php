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
$section = (string) ($_POST['section'] ?? 'security');
$removeAvatar = isset($_POST['remove_avatar']) && (string) $_POST['remove_avatar'] === '1';

$hasAvatarPath = false;
$colRes = $mysqli->query("SHOW COLUMNS FROM users LIKE 'avatar_path'");
if ($colRes && $colRes->fetch_assoc()) {
    $hasAvatarPath = true;
}

$stmt = $mysqli->prepare('SELECT email, nickname, avatar_path FROM users WHERE id = ? AND is_deleted = 0 LIMIT 1');
$stmt->bind_param('i', $uid);
$stmt->execute();
$dbRow = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$dbRow) {
    redirect('user-info.php');
}

$email = (string) $dbRow['email'];
$nickname = (string) $dbRow['nickname'];
$avatarPath = $hasAvatarPath ? ($dbRow['avatar_path'] ?? null) : null;

if ($removeAvatar && $hasAvatarPath) {
    if (is_string($avatarPath) && $avatarPath !== '') {
        $oldAbs = ROOT_PATH . '/' . ltrim($avatarPath, '/');
        if (is_file($oldAbs)) {
            @unlink($oldAbs);
        }
    }
    $null = null;
    $upd = $mysqli->prepare('UPDATE users SET avatar_path = NULL WHERE id = ? LIMIT 1');
    $upd->bind_param('i', $uid);
    $upd->execute();
    $upd->close();
    $avatarPath = null;
    set_session_user([
        'id' => $uid,
        'email' => $email,
        'nickname' => $nickname,
        'avatar_path' => null,
    ]);
    set_flash('account_ok', __('account.avatar_removed'));
    redirect('user-info.php#account-appearance');
}

if ($section === 'appearance') {
    $newAvatar = null;
    if (isset($_FILES['avatar']) && is_array($_FILES['avatar']) && (int) ($_FILES['avatar']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
        $errCode = (int) ($_FILES['avatar']['error'] ?? UPLOAD_ERR_OK);
        if ($errCode !== UPLOAD_ERR_OK) {
            set_flash('account_err', __('account.avatar_upload_failed'));
            redirect('user-info.php#account-appearance');
        }
        $tmpPath = (string) ($_FILES['avatar']['tmp_name'] ?? '');
        $maxBytes = 3 * 1024 * 1024;
        $size = (int) ($_FILES['avatar']['size'] ?? 0);
        if ($size < 1 || $size > $maxBytes) {
            set_flash('account_err', __('account.avatar_upload_failed'));
            redirect('user-info.php#account-appearance');
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
            redirect('user-info.php#account-appearance');
        }
        $dirAbs = ROOT_PATH . '/uploads/avatars';
        if (!is_dir($dirAbs) && !mkdir($dirAbs, 0775, true) && !is_dir($dirAbs)) {
            set_flash('account_err', __('account.avatar_upload_failed'));
            redirect('user-info.php#account-appearance');
        }
        if (is_string($avatarPath) && $avatarPath !== '') {
            $oldAbs = ROOT_PATH . '/' . ltrim($avatarPath, '/');
            if (is_file($oldAbs)) {
                @unlink($oldAbs);
            }
        }
        $filename = 'u' . $uid . '_' . bin2hex(random_bytes(6)) . '.' . $allowed[$mime];
        $destAbs = $dirAbs . '/' . $filename;
        if (!move_uploaded_file($tmpPath, $destAbs)) {
            set_flash('account_err', __('account.avatar_upload_failed'));
            redirect('user-info.php#account-appearance');
        }
        $newAvatar = 'uploads/avatars/' . $filename;
    }

    if ($hasAvatarPath && $newAvatar !== null) {
        $upd = $mysqli->prepare('UPDATE users SET avatar_path = ? WHERE id = ? LIMIT 1');
        $upd->bind_param('si', $newAvatar, $uid);
        $upd->execute();
        $upd->close();
        $avatarPath = $newAvatar;
    }

    set_session_user([
        'id' => $uid,
        'email' => $email,
        'nickname' => $nickname,
        'avatar_path' => $avatarPath,
    ]);
    set_flash('account_ok', __('account.avatar_saved'));
    redirect('user-info.php#account-appearance');
}

if ($section === 'security') {
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $nickname = trim((string) ($_POST['nickname'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $passwordConfirm = (string) ($_POST['password_confirm'] ?? '');
    if ($nickname === '') {
        $nickname = 'user' . $uid;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash('account_err', __('auth.email_invalid'));
        redirect('user-info.php#account-security');
    }

    $chk = $mysqli->prepare('SELECT id FROM users WHERE email = ? AND is_deleted = 0 AND id <> ? LIMIT 1');
    $chk->bind_param('si', $email, $uid);
    $chk->execute();
    $exists = $chk->get_result()->fetch_assoc();
    $chk->close();
    if ($exists) {
        set_flash('account_err', __('auth.email_exists'));
        redirect('user-info.php#account-security');
    }

    if ($password !== '' && $password !== $passwordConfirm) {
        set_flash('account_err', __('account.password_mismatch'));
        redirect('user-info.php#account-security');
    }
    if ($password !== '' && strlen($password) < 6) {
        set_flash('account_err', __('auth.register_invalid'));
        redirect('user-info.php#account-security');
    }
    $passwordHash = $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : null;

    $upd = $mysqli->prepare(
        'UPDATE users SET email = ?, nickname = ?, password_hash = COALESCE(?, password_hash) WHERE id = ? LIMIT 1'
    );
    $upd->bind_param('sssi', $email, $nickname, $passwordHash, $uid);
    $upd->execute();
    $upd->close();

    set_session_user([
        'id' => $uid,
        'email' => $email,
        'nickname' => $nickname,
        'avatar_path' => $avatarPath,
    ]);
    set_flash('account_ok', __('auth.account_saved'));
    redirect('user-info.php#account-security');
}

redirect('user-info.php');
