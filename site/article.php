<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/connect.php';

$slug = preg_replace('/[^a-z0-9\-]/i', '', (string) ($_GET['slug'] ?? ''));
if ($slug === '') {
    http_response_code(404);
    echo 'Not found';
    exit;
}

$stmt = $mysqli->prepare('SELECT * FROM news_articles WHERE slug = ? LIMIT 1');
$stmt->bind_param('s', $slug);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();

if (!$row) {
    http_response_code(404);
    echo 'Not found';
    exit;
}

$isEn = current_lang() === 'en';
$pageTitle = $isEn ? $row['title_en'] : $row['title_ru'];
$body = $isEn ? $row['body_en'] : $row['body_ru'];

require COMPONENTS_PATH . '/header.php';
$breadcrumbs = [
    ['label' => __('nav.reviews'), 'href' => asset('reviews.php')],
    ['label' => $pageTitle, 'href' => null],
];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= $isEn ? 'Article' : 'Статья' ?></p>
        <h1 class="ws-page-title"><?= h($pageTitle) ?></h1>
        <p class="ws-page-lead muted"><?= h($row['published_at']) ?></p>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
        <p style="margin-top: 14px;">
            <a class="btn btn-ghost" href="<?= h(asset('reviews.php')) ?>"><?= h(__('common.back')) ?></a>
        </p>
    </header>
</div>
<div class="container card card-hover">
    <div class="article-body"><?= $body ?></div>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
