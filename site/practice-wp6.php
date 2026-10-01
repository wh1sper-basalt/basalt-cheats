<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$pageTitle = t('practice_wp1.title');
require COMPONENTS_PATH . '/header.php';

$breadcrumbs = [
    ['label' => $pageTitle, 'href' => null],
];

// WordPress развёрнут отдельной папкой в XAMPP
$wp = 'http://localhost/wp-lab/';

$buttons = [
    ['label' => t('practice_wp1.btn_site'),     'url' => $wp],
    ['label' => t('practice_wp1.btn_admin'),    'url' => $wp . 'wp-admin/'],
    ['label' => t('practice_wp1.btn_about'),    'url' => $wp . 'about/'],
    ['label' => t('practice_wp1.btn_contacts'), 'url' => $wp . 'contacts/'],
];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge">WP-1</p>
        <h1 class="ws-page-title"><?= h($pageTitle) ?></h1>
        <p class="ws-page-lead"><?= h(t('practice_wp1.lead')) ?></p>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>

    <div class="practice-toolbar" style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap;margin:0 auto 14px;">
        <?php foreach ($buttons as $b): ?>
            <button type="button" class="ws-btn" data-url="<?= h($b['url']) ?>">
                <?= h($b['label']) ?>
            </button>
        <?php endforeach; ?>
    </div>

    <div class="console-frame" style="height: 75vh;">
        <iframe id="practice-frame"
                title="practice-wp1"
                src="<?= h($wp) ?>"
                style="width:100%;height:100%;border:0;background:#fff;"></iframe>
    </div>
</div>

<script>
(function () {
    var frame = document.getElementById('practice-frame');
    if (!frame) return;
    document.querySelectorAll('.practice-toolbar [data-url]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            frame.src = btn.dataset.url;
        });
    });
})();
</script>

<?php require COMPONENTS_PATH . '/footer.php'; ?>