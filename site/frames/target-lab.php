<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/inc/mobile-guard.php';

$lang = $_GET['lang'] ?? 'ru';
$ru = $lang !== 'en';
$slot = (string) ($_GET['slot'] ?? '1');
$title = $slot === '2'
    ? ($ru ? 'Слот B: логистика ключей' : 'Slot B: key logistics')
    : ($ru ? 'Слот A: обзор витрины' : 'Slot A: storefront overview');

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="<?= $ru ? 'ru' : 'en' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></title>
    <style>
        body { margin: 0; font: 14px/1.5 system-ui; background: #0a0f14; color: #e8eaed; padding: 16px; }
        code { background: #111822; padding: 2px 6px; border-radius: 6px; }
    </style>
</head>
<body>
<h1><?= htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></h1>
<p><?= $ru
    ? 'ПР8: контент подгружается во фрейм по ссылке с target (как city-today.html).'
    : 'PR8: content loads into the parent iframe via target links (city-today pattern).' ?></p>
<p><code>slot=<?= htmlspecialchars($slot, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></code></p>
<script src="../assets/js/devtools-guard.js" defer></script>
</body>
</html>
