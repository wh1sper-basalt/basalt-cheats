<?php

declare(strict_types=1);

/**
 * ПР9: вывод всех блоков главной из таблицы index_page (как sweetty/index.php).
 * Живая главная index.php использует отдельную вёрстку; эта страница демонстрирует данные из БД.
 */

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/connect.php';

$pageTitle = __('indexdb.title');
require COMPONENTS_PATH . '/header.php';

$ru = current_lang() === 'ru';
$col = $ru ? 'content_ru' : 'content_en';

$res = $mysqli->query('SELECT `id_element`, `alias`, `' . $col . '` AS body FROM `index_page` ORDER BY `id_element` ASC');
$rows = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $rows[] = $row;
    }
    $res->free();
}
$breadcrumbs = [
    ['label' => __('nav.indexdb'), 'href' => null],
];
?>

<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge">PR9</p>
        <h1 class="ws-page-title"><?= h(__('indexdb.title')) ?></h1>
        <p class="ws-page-lead"><?= h(__('indexdb.lead')) ?></p>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>
</div>

<div class="container stack" style="gap: 0;">
    <?php foreach ($rows as $block) { ?>
        <article class="index-db-block" data-alias="<?= h((string) ($block['alias'] ?? '')) ?>">
            <?= fix_db_hrefs((string) ($block['body'] ?? '')) ?>
        </article>
    <?php } ?>
</div>

<?php require COMPONENTS_PATH . '/footer.php'; ?>
