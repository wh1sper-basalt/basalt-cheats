<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/connect.php';

$pageTitle = __('gallery.title');
require COMPONENTS_PATH . '/header.php';

$isEn = current_lang() === 'en';
$res = $mysqli->query('SELECT * FROM gallery_items ORDER BY sort_order ASC, id ASC');
$breadcrumbs = [
    ['label' => __('nav.gallery'), 'href' => null],
];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= $isEn ? 'Gallery' : 'Галерея' ?></p>
        <h1 class="ws-page-title"><?= h(__('gallery.title')) ?></h1>
        <p class="ws-page-lead"><?= $isEn ? 'Six showcase stills — swap files listed in IMAGES.md.' : 'Шесть кадров витрины — замените файлы по IMAGES.md.' ?></p>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>
</div>
<div class="container gallery-grid">
    <?php
    if ($res) {
        while ($g = $res->fetch_assoc()) {
            $cap = $isEn ? $g['caption_en'] : $g['caption_ru'];
            ?>
            <figure class="card card-hover">
                <img src="<?= h(asset($g['image_path'])) ?>" alt="<?= h($cap) ?>" width="480" height="360" loading="lazy">
                <figcaption><?= h($cap) ?></figcaption>
            </figure>
            <?php
        }
        $res->free();
    }
    ?>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
