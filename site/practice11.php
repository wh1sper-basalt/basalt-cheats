<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$isEn = current_lang() === 'en';
$pageTitle = $isEn ? 'Practice 11 — Victory Day' : 'Практика 11 — День Победы';
require COMPONENTS_PATH . '/header.php';

$breadcrumbs = [
    ['label' => $pageTitle, 'href' => null],
];
$src = asset('practice11-victory/index.php');
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge">PR11</p>
        <h1 class="ws-page-title"><?= h($pageTitle) ?></h1>
        <p class="ws-page-lead"><?= h($isEn ? 'Isolated Victory Day mini-site (Bootstrap carousel + hero cities).' : 'Изолированный мини-сайт «День Победы» (карусель Bootstrap, города-герои).') ?></p>
    </header>
    <div class="console-frame" style="height: 75vh;">
        <iframe title="practice11-victory" src="<?= h($src) ?>" style="width:100%;height:100%;border:0;"></iframe>
    </div>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
