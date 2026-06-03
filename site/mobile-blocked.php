<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$c = site_config();
$brand = $c['brand'] ?? 'Basalt';
$logoPath = $c['logo_path'] ?? 'assets/img/components/logo.png';
$faviconPath = $c['favicon_path'] ?? 'assets/img/components/icon.ico';
$cssBase = asset('assets/css');
?>
<!DOCTYPE html>
<html lang="<?= h(current_lang()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h(__('mobile_blocked.title')) ?> · <?= h($brand) ?></title>
    <meta name="theme-color" content="#070707">
    <meta name="robots" content="noindex">
    <link rel="icon" type="image/x-icon" href="<?= h(asset($faviconPath)) ?>">
    <link rel="stylesheet" href="<?= h($cssBase) ?>/variables.css">
    <link rel="stylesheet" href="<?= h($cssBase) ?>/reset.css">
    <link rel="stylesheet" href="<?= h($cssBase) ?>/animations.css">
    <link rel="stylesheet" href="<?= h($cssBase) ?>/base.css">
    <link rel="stylesheet" href="<?= h($cssBase) ?>/layout.css">
    <link rel="stylesheet" href="<?= h($cssBase) ?>/components.css">
    <link rel="stylesheet" href="<?= h($cssBase) ?>/pages.css">
</head>
<body class="mobile-blocked-page">
<div class="bg-stack animated-bg-ws" aria-hidden="true">
    <div class="floating-orbs">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>
    <div class="bg-photo"></div>
    <div class="bg-scrim"></div>
</div>
<main class="mobile-blocked-main">
    <section class="mobile-blocked-card glass-dock">
        <img class="mobile-blocked-logo" src="<?= h(asset($logoPath)) ?>" width="64" height="64" alt="<?= h($brand) ?>" draggable="false">
        <h1><?= h(__('mobile_blocked.title')) ?></h1>
        <p class="lead"><?= h(__('mobile_blocked.lead')) ?></p>
        <p class="muted"><?= h(__('mobile_blocked.hint')) ?></p>
    </section>
</main>
<script src="<?= h(asset('assets/js/devtools-guard.js')) ?>" defer></script>
</body>
</html>
