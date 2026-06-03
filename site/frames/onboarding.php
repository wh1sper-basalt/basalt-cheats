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
    <title>Onboarding</title>
    <style>
        body { margin:0; font: 14px/1.5 system-ui; background:#0a0f14; color:#e8eaed; padding:16px; }
    </style>
</head>
<body>
<h1><?= $ru ? 'Онбординг' : 'Onboarding' ?></h1>
<p><?= $ru ? '1) Оплатите через QR. 2) Отметьте «Я оплатил». 3) Напишите в Telegram с номером заявки из ответа.' : '1) Pay via QR. 2) Check “I have paid”. 3) Ping Telegram with the ticket id from our reply.' ?></p>
<script src="../assets/js/devtools-guard.js" defer></script>
</body>
</html>
