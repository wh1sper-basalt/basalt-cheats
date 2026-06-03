<?php

declare(strict_types=1);

$c = site_config();
$url = $c['telegram_url'] ?? '#';
$handle = $c['telegram_handle'] ?? '';
?>
<aside class="card card-hover telegram-card">
    <h3><?= h(__('telegram.cta')) ?></h3>
    <p class="muted"><?= h($handle) ?></p>
    <a class="btn btn-primary" href="<?= h($url) ?>" target="_blank" rel="noopener noreferrer"><?= h(__('telegram.join')) ?></a>
</aside>
