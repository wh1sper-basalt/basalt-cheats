<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$c = site_config();
$pageTitle = __('feedback.title');
require COMPONENTS_PATH . '/header.php';
$isEn = current_lang() === 'en';
$tg = $c['telegram_url'] ?? '#';
$dc = trim((string) ($c['discord_url'] ?? ''));
$breadcrumbs = [
    ['label' => __('nav.support'), 'href' => null],
];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>

    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= $isEn ? 'Support' : 'Поддержка' ?></p>
        <h1 class="ws-page-title">
            <?= h(__('support.hero_title_1')) ?> <span class="accent"><?= h(__('support.hero_title_2')) ?></span>
        </h1>
        <p class="ws-page-lead"><?= h(__('support.hero_lead')) ?></p>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>

    <div class="ws-stats-row">
        <div class="ws-stat-card">
            <div class="ws-stat-value"><?= h(__('support.stat247')) ?></div>
            <div class="ws-stat-label"><?= h(__('support.stat247_sub')) ?></div>
        </div>
        <div class="ws-stat-card">
            <div class="ws-stat-value"><?= h(__('support.stat_tg')) ?></div>
            <div class="ws-stat-label"><?= h(__('support.stat_tg_sub')) ?></div>
        </div>
        <div class="ws-stat-card">
            <div class="ws-stat-value"><?= h(__('support.stat_secure')) ?></div>
            <div class="ws-stat-label"><?= h(__('support.stat_secure_sub')) ?></div>
        </div>
    </div>

    <div class="ws-support-grid">
        <article class="card card-hover ws-support-card">
            <div class="ws-support-head">
                <div>
                    <h2><?= h(__('support.discord_title')) ?></h2>
                    <p class="muted"><?= h(__('support.discord_sub')) ?></p>
                </div>
                <span class="ws-time-badge"><?= h(__('support.discord_time')) ?></span>
            </div>
            <ul class="ws-steps">
                <?php for ($s = 1; $s <= 5; ++$s) { ?>
                    <li><span class="ws-step-num"><?= $s ?></span><span><?= h(__('support.step' . $s)) ?></span></li>
                <?php } ?>
            </ul>
            <?php if ($dc !== '') { ?>
                <a class="btn btn-ghost ws-support-cta" href="<?= h($dc) ?>" target="_blank" rel="noopener noreferrer"><?= h(__('support.discord_btn')) ?> ↗</a>
            <?php } else { ?>
                <p class="muted" style="padding: 0 18px 16px;"><?= $isEn ? 'Discord URL is not configured yet.' : 'Ссылка на Discord пока не настроена (site.yaml → discord_url).' ?></p>
            <?php } ?>
        </article>
        <article class="card card-hover ws-support-card">
            <div class="ws-support-head">
                <div>
                    <h2><?= h(__('support.tg_title')) ?></h2>
                    <p class="muted"><?= h(__('support.tg_sub')) ?></p>
                </div>
                <span class="ws-time-badge"><?= h(__('support.tg_time')) ?></span>
            </div>
            <ul class="ws-steps">
                <?php for ($s = 1; $s <= 5; ++$s) { ?>
                    <li><span class="ws-step-num"><?= $s ?></span><span><?= h(__('support.step' . $s)) ?></span></li>
                <?php } ?>
            </ul>
            <a class="btn btn-primary ws-support-cta" href="<?= h($tg) ?>" target="_blank" rel="noopener noreferrer"><?= h(__('support.telegram_btn')) ?> ↗</a>
        </article>
    </div>

    <div style="margin-top: 32px;">
        <h2 style="margin-bottom: 12px;"><?= $isEn ? 'Write to us' : 'Написать нам' ?></h2>
        <?php if (isset($_GET['err'])) { ?>
            <p class="muted" style="color:#ff8a8a;"><?= $isEn ? 'Please check the form fields.' : 'Проверьте поля формы.' ?></p>
        <?php } ?>
        <?php require COMPONENTS_PATH . '/feedback-section.php'; ?>
    </div>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
