<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$kind = $_GET['kind'] ?? 'contact';
$pageTitle = current_lang() === 'ru' ? 'Спасибо' : 'Thank you';
require COMPONENTS_PATH . '/header.php';

$msg = $kind === 'payment' ? __('answer.payment_ok') : __('answer.contact_ok');
$ru = current_lang() === 'ru';
$breadcrumbs = [
    ['label' => $ru ? 'Ответ' : 'Answer', 'href' => null],
];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= $ru ? 'Status' : 'Status' ?></p>
        <h1 class="ws-page-title"><?= h($msg) ?></h1>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
        <p style="margin-top: 14px;">
            <a class="btn btn-ghost" href="<?= h(asset('index.php')) ?>"><?= h(__('nav.home')) ?></a>
        </p>
    </header>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
