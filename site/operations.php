<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$pageTitle = __('operations.title');
require COMPONENTS_PATH . '/header.php';
$ru = current_lang() === 'ru';
$breadcrumbs = [
    ['label' => __('nav.operations'), 'href' => null],
];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= $ru ? 'NOC' : 'NOC' ?></p>
        <h1 class="ws-page-title"><?= h(__('operations.title')) ?></h1>
        <p class="ws-page-lead"><?= $ru
            ? 'Срез метрик NOC: нагрузка выдачи, инциденты, окна обслуживания.'
            : 'NOC snapshot: issuance load, incidents, maintenance windows.' ?></p>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>
</div>
<div class="container grid cols-3">
    <article class="card card-hover">
        <h3><?= $ru ? 'Очередь выдачи' : 'Issuance queue' ?></h3>
        <p class="muted"><?= $ru ? 'Среднее время 4м 12с' : 'Average 4m 12s' ?></p>
    </article>
    <article class="card card-hover">
        <h3><?= $ru ? 'Инциденты' : 'Incidents' ?></h3>
        <p class="muted"><?= $ru ? 'Открыто: 2 (P3)' : 'Open: 2 (P3)' ?></p>
    </article>
    <article class="card card-hover">
        <h3><?= $ru ? 'Окно обновлений' : 'Update window' ?></h3>
        <p class="muted"><?= $ru ? 'Сб 03:00–05:00 UTC' : 'Sat 03:00–05:00 UTC' ?></p>
    </article>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
