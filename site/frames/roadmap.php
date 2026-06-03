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
    <title>Roadmap</title>
    <style>
        body { margin:0; font: 14px/1.5 system-ui; background:#0a0f14; color:#e8eaed; padding:16px; }
    </style>
</head>
<body>
<h1>Roadmap</h1>
<ol>
    <li><?= $ru ? 'Webhook оплаты' : 'Payment webhooks' ?></li>
    <li><?= $ru ? 'Личный кабинет (опционально)' : 'Optional customer portal' ?></li>
    <li><?= $ru ? 'Расширенный журнал аудита' : 'Extended audit log' ?></li>
</ol>
<script src="../assets/js/devtools-guard.js" defer></script>
</body>
</html>
