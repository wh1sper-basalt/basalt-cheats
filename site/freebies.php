<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$pageTitle = __('freebies.title');
require COMPONENTS_PATH . '/header.php';
$isEn = current_lang() === 'en';
$breadcrumbs = [
    ['label' => __('freebies.title'), 'href' => null],
];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= h(__('freebies.badge')) ?></p>
        <h1 class="ws-page-title"><?= h(__('freebies.heading')) ?></h1>
        <p class="ws-page-lead"><?= h(__('freebies.lead')) ?></p>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>

    <div class="ws-freebies-grid">
        <article class="card card-hover ws-freebie-card">
            <div class="ws-freebie-icon" aria-hidden="true">▣</div>
            <h2><?= h(__('freebies.media_title')) ?></h2>
            <p class="muted"><?= h(__('freebies.media_body')) ?></p>
            <div class="spacer"></div>
            <a class="btn btn-ghost" href="<?= h(asset('feedback.php')) ?>"><?= h(__('freebies.media_cta')) ?> <img src="<?= h(asset('assets/svg/arrow-right.svg')) ?>" width="14" height="14" alt=""></a>
        </article>
        <article class="card card-hover ws-freebie-card">
            <div class="ws-freebie-icon" aria-hidden="true">%</div>
            <h2><?= h(__('freebies.ref_title')) ?></h2>
            <p class="muted"><?= h(__('freebies.ref_body')) ?></p>
            <div class="spacer"></div>
            <a class="btn btn-ghost" href="<?= h(asset('faq.php')) ?>"><?= h(__('freebies.ref_cta')) ?> <img src="<?= h(asset('assets/svg/arrow-right.svg')) ?>" width="14" height="14" alt=""></a>
        </article>
    </div>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
