<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$pageTitle = __('iframelab.title');
require COMPONENTS_PATH . '/header.php';

$ru = current_lang() === 'ru';
$lang = current_lang();
$base = asset('frames/target-lab.php');
$urlA = $base . '?slot=1&lang=' . rawurlencode($lang);
$urlB = $base . '?slot=2&lang=' . rawurlencode($lang);
?>

<div class="container page-head">
    <h1><?= h(__('iframelab.title')) ?></h1>
    <p class="lead"><?= h(__('iframelab.lead')) ?></p>
</div>

<div class="container card card-hover" style="padding: 20px;">
    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:14px;">
        <a class="btn btn-ghost" href="<?= h($urlA) ?>" target="basalt_city_iframe"><?= $ru ? 'Загрузить слот A' : 'Load slot A' ?></a>
        <a class="btn btn-ghost" href="<?= h($urlB) ?>" target="basalt_city_iframe"><?= $ru ? 'Загрузить слот B' : 'Load slot B' ?></a>
    </div>
    <iframe
        title="<?= h($ru ? 'ПР8 iframe' : 'PR8 iframe') ?>"
        name="basalt_city_iframe"
        src="<?= h($urlA) ?>"
        style="width:100%;min-height:320px;border:1px solid rgba(255,255,255,.12);border-radius:12px;background:#070a0d;"
    ></iframe>
</div>

<?php require COMPONENTS_PATH . '/footer.php'; ?>
