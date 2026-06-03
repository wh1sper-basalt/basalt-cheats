<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';
require dirname(__DIR__) . '/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    logout_user();

    $cookie = remember_cookie_value();
    if ($cookie !== '') {
        $hash = hash('sha256', $cookie);
        $stmt = $mysqli->prepare('UPDATE user_auth_tokens SET revoked = 1 WHERE token_hash = ? LIMIT 1');
        if ($stmt) {
            $stmt->bind_param('s', $hash);
            $stmt->execute();
            $stmt->close();
        }
    }
    clear_remember_cookie();
}

redirect('index.php');
