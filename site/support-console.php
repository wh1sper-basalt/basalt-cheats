<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$pageTitle = __('console.title');
require COMPONENTS_PATH . '/header.php';
$ru = current_lang() === 'ru';
$base = asset('frames');
$breadcrumbs = [
    ['label' => __('nav.console'), 'href' => null],
];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= $ru ? 'Console' : 'Console' ?></p>
        <h1 class="ws-page-title"><?= h(__('console.title')) ?></h1>
        <p class="ws-page-lead"><?= $ru
            ? 'Четыре встроенных фрейма — микро-страницы поддержки в одном экране (структура iframe-лаба из курса).'
            : 'Four embedded frames — support micro-pages in one screen (iframe lab structure from the course).' ?></p>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>
</div>
<div class="container console-grid">
    <div>
        <h3 class="muted" style="text-transform:uppercase;font-size:0.75rem;letter-spacing:0.1em;">Docs</h3>
        <div class="console-frame">
            <iframe title="docs" src="<?= h($base) ?>/docs.php?lang=<?= h(current_lang()) ?>"></iframe>
        </div>
    </div>
    <div>
        <h3 class="muted" style="text-transform:uppercase;font-size:0.75rem;letter-spacing:0.1em;">Status</h3>
        <div class="console-frame">
            <iframe title="status" src="<?= h($base) ?>/status.php?lang=<?= h(current_lang()) ?>"></iframe>
        </div>
    </div>
    <div>
        <h3 class="muted" style="text-transform:uppercase;font-size:0.75rem;letter-spacing:0.1em;">Roadmap</h3>
        <div class="console-frame">
            <iframe title="roadmap" src="<?= h($base) ?>/roadmap.php?lang=<?= h(current_lang()) ?>"></iframe>
        </div>
    </div>
    <div>
        <h3 class="muted" style="text-transform:uppercase;font-size:0.75rem;letter-spacing:0.1em;">Onboarding</h3>
        <div class="console-frame">
            <iframe title="onboarding" src="<?= h($base) ?>/onboarding.php?lang=<?= h(current_lang()) ?>"></iframe>
        </div>
    </div>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
