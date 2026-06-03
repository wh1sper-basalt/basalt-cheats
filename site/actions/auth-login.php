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

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    set_flash('auth_error', __('auth.invalid_credentials'));
    set_flash('auth_panel', 'login');
    header('Location: ' . $back);
    exit;
}

$stmt = $mysqli->prepare('SELECT id, email, password_hash, nickname, avatar_path FROM users WHERE email = ? AND is_deleted = 0 LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user || !password_verify($password, (string) $user['password_hash'])) {
    set_flash('auth_error', __('auth.invalid_credentials'));
    set_flash('auth_panel', 'login');
    header('Location: ' . $back);
    exit;
}

set_session_user($user);
set_flash('auth_ok', __('auth.login_success'));

if ($remember === '1') {
    $token = bin2hex(random_bytes(32));
    $hash = hash('sha256', $token);
    $uid = (int) ($user['id'] ?? 0);
    if ($uid > 0) {
        $stmt = $mysqli->prepare(
            'INSERT INTO user_auth_tokens (user_id, token_hash, expires_at)
             VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 DAY))'
        );
        if ($stmt) {
            $stmt->bind_param('is', $uid, $hash);
            $stmt->execute();
            $stmt->close();
            set_remember_cookie($token, 60 * 60 * 24 * 30);
        }
    }
}

header('Location: ' . $back);
exit;
