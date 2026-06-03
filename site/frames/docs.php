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
    <title>Docs</title>
    <style>
        body { margin:0; font: 14px/1.5 system-ui; background:#0a0f14; color:#e8eaed; padding:16px; }
        a { color:#5ee7df; }
        code { background:#111822; padding:2px 6px; border-radius:6px; }
    </style>
</head>
<body>
<h1><?= $ru ? 'Документация' : 'Documentation' ?></h1>
<p><?= $ru ? 'Быстрые шаги после оплаты: сохраните QR payload, откройте Telegram-канал, дождитесь ключа.' : 'After payment: keep the QR payload, open the Telegram channel, wait for the key.' ?></p>
<p><code>basalt-cli verify --token YOUR_PAYLOAD</code></p>
<script src="../assets/js/devtools-guard.js" defer></script>
</body>
</html>
