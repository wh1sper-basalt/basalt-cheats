<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';
require dirname(__DIR__) . '/connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$email = strtolower(trim((string) ($_POST['email'] ?? '')));
$password = (string) ($_POST['password'] ?? '');
$back = (string) ($_POST['back'] ?? asset('index.php'));
$remember = (string) ($_POST['remember'] ?? '');

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
    set_flash('auth_register_error', __('auth.register_invalid'));
    set_flash('auth_panel', 'register');
    header('Location: ' . $back);
    exit;
}

$banStmt = $mysqli->prepare('SELECT blocked_until FROM deleted_accounts WHERE email = ? AND blocked_until > NOW() ORDER BY id DESC LIMIT 1');
$banStmt->bind_param('s', $email);
$banStmt->execute();
$blocked = $banStmt->get_result()->fetch_assoc();
$banStmt->close();
if ($blocked) {
    set_flash('auth_register_error', __('auth.register_blocked'));
    set_flash('auth_panel', 'register');
    header('Location: ' . $back);
    exit;
}

$chkStmt = $mysqli->prepare('SELECT id FROM users WHERE email = ? AND is_deleted = 0 LIMIT 1');
$chkStmt->bind_param('s', $email);
$chkStmt->execute();
$exists = $chkStmt->get_result()->fetch_assoc();
$chkStmt->close();
if ($exists) {
    set_flash('auth_register_error', __('auth.register_exists'));
    set_flash('auth_panel', 'register');
    header('Location: ' . $back);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$insStmt = $mysqli->prepare('INSERT INTO users (email, password_hash, nickname) VALUES (?, ?, ?)');
$nickname = strstr($email, '@', true) ?: 'user';
$insStmt->bind_param('sss', $email, $hash, $nickname);
$insStmt->execute();
$uid = (int) $insStmt->insert_id;
$insStmt->close();

if ($uid > 0) {
    set_session_user(['id' => $uid, 'email' => $email, 'nickname' => $nickname]);
    set_flash('auth_ok', __('auth.register_success'));

    if ($remember === '1') {
        $token = bin2hex(random_bytes(32));
        $hashToken = hash('sha256', $token);
        $tStmt = $mysqli->prepare(
            'INSERT INTO user_auth_tokens (user_id, token_hash, expires_at)
             VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 DAY))'
        );
        if ($tStmt) {
            $tStmt->bind_param('is', $uid, $hashToken);
            $tStmt->execute();
            $tStmt->close();
            set_remember_cookie($token, 60 * 60 * 24 * 30);
        }
    }
} else {
    set_flash('auth_register_error', __('auth.register_failed'));
    set_flash('auth_panel', 'register');
}
header('Location: ' . $back);
exit;
