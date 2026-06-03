<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$pageTitle = __('history.title');
require COMPONENTS_PATH . '/header.php';
$ru = current_lang() === 'ru';
$breadcrumbs = [
    ['label' => __('nav.history'), 'href' => null],
];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= $ru ? 'Timeline' : 'Timeline' ?></p>
        <h1 class="ws-page-title"><?= h(__('history.title')) ?></h1>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>
</div>
<div class="container card card-hover stack">
    <?php if ($ru) { ?>
        <p>2018 — запуск внутреннего конвейера лицензий для закрытой группы интеграторов.</p>
        <p>2020 — монохромный интерфейс и статусные чипы, отказ от «кабинетной» модели.</p>
        <p>2023 — публичная витрина читов, объединение выдачи ключей и поддержки в одном контуре.</p>
        <p>2026 — двуязычие RU/EN, рандомизированные QR-сессии и журнал заявок в MySQL.</p>
    <?php } else { ?>
        <p>2018 — internal license conveyor for a closed integrator group.</p>
        <p>2020 — monochrome UI with status chips; dropped heavyweight account portals.</p>
        <p>2023 — public module storefront; logistics + support unified.</p>
        <p>2026 — RU/EN, randomized QR sessions, MySQL request ledger.</p>
    <?php } ?>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
