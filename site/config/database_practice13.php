<?php

declare(strict_types=1);

return [
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'port' => (int) (getenv('DB_PORT') ?: 3306),
    'user' => getenv('DB_USER') ?: 'root',
    'pass' => getenv('DB_PASSWORD') !== false ? (string) getenv('DB_PASSWORD') : '',
    'name' => getenv('DB_PRACTICE13_NAME') ?: 'basalt_practice13',
];
