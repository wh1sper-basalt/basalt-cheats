<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$isEn = current_lang() === 'en';
$pageTitle = $isEn ? 'Practice 15 — ExpressJS' : 'Практика 15 — ExpressJS';
require COMPONENTS_PATH . '/header.php';

$breadcrumbs = [
    ['label' => $pageTitle, 'href' => null],
];

// Express-сервер поднимается отдельно (см. practice14-express/README.md).
// Порт по умолчанию — 3000.
$src = 'http://localhost:3000';
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge">PR15</p>
        <h1 class="ws-page-title"><?= h($pageTitle) ?></h1>
        <p class="ws-page-lead">
            <?= h($isEn
                ? 'Node.js + Express: user registration and authorization, personal account (dashboard)'
                : 'Node.js + Express: регистрация и авторизация пользователя, личный кабинет (дэшборд)') ?>
        </p>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>

    <p class="muted" style="max-width:760px;margin:0 auto 14px;text-align:center;">
        <?= $isEn
            ? 'Express runs on port 3000, separately from PHP/XAMPP. Start it with:'
            : 'Express запускается на порту 3000, отдельно от PHP/XAMPP. Запуск:' ?>
        <code>cd site/practice15-express &amp;&amp; npm install &amp;&amp; npm start</code>.
    </p>

    <div class="console-frame" style="height: 75vh;">
        <iframe title="practice15-express" src="<?= h($src) ?>" style="width:100%;height:100%;border:0;background:#0e0e0e;"></iframe>
    </div>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>