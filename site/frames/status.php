<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/inc/mobile-guard.php';

$lang = $_GET['lang'] ?? 'ru';
$ru = $lang !== 'en';
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="<?= $ru ? 'ru' : 'en' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status</title>
    <style>
        body { margin:0; font: 14px/1.5 system-ui; background:#0a0f14; color:#e8eaed; padding:16px; }
        .ok { color:#5ee7df; }
    </style>
</head>
<body>
<h1><?= $ru ? 'Статус сервисов' : 'Service status' ?></h1>
<ul>
    <li class="ok"><?= $ru ? 'Выдача ключей — операционно' : 'Key issuance — operational' ?></li>
    <li class="ok"><?= $ru ? 'CDN артефактов — операционно' : 'Artifact CDN — operational' ?></li>
    <li><?= $ru ? 'Почта — ограниченно (используйте Telegram)' : 'Email — limited (use Telegram)' ?></li>
</ul>
<script src="../assets/js/devtools-guard.js" defer></script>
</body>
</html>
