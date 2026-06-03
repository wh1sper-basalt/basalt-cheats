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

$stmt = $mysqli->prepare('SELECT email FROM users WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $uid);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$row) {
    logout_user();
    redirect('index.php');
}

$email = strtolower((string) $row['email']);
$ins = $mysqli->prepare('INSERT INTO deleted_accounts (email, blocked_until) VALUES (?, DATE_ADD(NOW(), INTERVAL 30 DAY))');
$ins->bind_param('s', $email);
$ins->execute();
$ins->close();

$upd = $mysqli->prepare('UPDATE users SET is_deleted = 1, updated_at = NOW() WHERE id = ? LIMIT 1');
$upd->bind_param('i', $uid);
$upd->execute();
$upd->close();

logout_user();
set_flash('auth_ok', __('auth.account_deleted'));
redirect('index.php');
