<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$isEn = current_lang() === 'en';
$pageTitle = $isEn ? 'Practice 11 — Hero cities' : 'Практика 11 — Города-герои';
require COMPONENTS_PATH . '/header.php';

$breadcrumbs = [
    ['label' => $isEn ? 'Practice 11' : 'Практика 11', 'href' => asset('practice11.php')],
    ['label' => $pageTitle, 'href' => null],
];
$src = asset('practice11-victory/hero-city.php');
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge">PR11</p>
        <h1 class="ws-page-title"><?= h($pageTitle) ?></h1>
    </header>
    <div class="console-frame" style="height: 75vh;">
        <iframe title="practice11-cities" src="<?= h($src) ?>" style="width:100%;height:100%;border:0;"></iframe>
    </div>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
