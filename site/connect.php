<?php

declare(strict_types=1);

if (!defined('CONFIG_PATH')) {
    define('CONFIG_PATH', __DIR__ . '/config');
}

$db = require CONFIG_PATH . '/database.php';

$mysqli = @new mysqli(
    $db['host'],
    $db['user'],
    $db['pass'],
    $db['name'],
    $db['port']
);

if ($mysqli->connect_errno) {
    http_response_code(503);
    echo 'Database unavailable.';
    exit;
}

$mysqli->set_charset('utf8mb4');

// Persistent login tokens (Remember Me)
$mysqli->query(
    'CREATE TABLE IF NOT EXISTS user_auth_tokens (
        id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT NOT NULL,
        token_hash CHAR(64) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        last_used_at TIMESTAMP NULL DEFAULT NULL,
        expires_at DATETIME NOT NULL,
        revoked TINYINT(1) NOT NULL DEFAULT 0,
        UNIQUE KEY uniq_token_hash (token_hash),
        KEY idx_user_id (user_id),
        KEY idx_expires (expires_at),
        CONSTRAINT fk_auth_tokens_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
);

maybe_restore_user_from_remember($mysqli);
