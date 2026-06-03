<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/connect.php';

$gameSlug = preg_replace('/[^a-z0-9\-]/i', '', (string) ($_GET['game'] ?? ''));
$osSlug = preg_replace('/[^a-z0-9\-]/i', '', (string) ($_GET['os'] ?? ''));
if (($gameSlug === '' && $osSlug === '') || ($gameSlug !== '' && $osSlug !== '')) {
    redirect('games.php');
}

if ($gameSlug !== '') {
    $scopeType = 'game';
    $stmt = $mysqli->prepare('SELECT * FROM games WHERE slug = ? LIMIT 1');
    $stmt->bind_param('s', $gameSlug);
    $stmt->execute();
    $scopeRes = $stmt->get_result();
    $scope = $scopeRes->fetch_assoc();
    $stmt->close();
    if (!$scope) {
        redirect('games.php');
    }
    $scopeId = (int) $scope['id'];
    $scopeSlug = $gameSlug;
    $listSql = 'SELECT c.*, (SELECT COUNT(*) FROM key_plans kp WHERE kp.cheat_id = c.id) AS plan_count FROM cheats c WHERE c.game_id = ? ORDER BY c.sort_order ASC, c.id ASC';
} else {
    $scopeType = 'os';
    $stmt = $mysqli->prepare('SELECT * FROM os WHERE slug = ? LIMIT 1');
    $stmt->bind_param('s', $osSlug);
    $stmt->execute();
    $scopeRes = $stmt->get_result();
    $scope = $scopeRes->fetch_assoc();
    $stmt->close();
    if (!$scope) {
        redirect('games.php');
    }
    $scopeId = (int) $scope['id'];
    $scopeSlug = $osSlug;
    $listSql = 'SELECT c.*, (SELECT COUNT(*) FROM key_plans kp WHERE kp.cheat_id = c.id) AS plan_count FROM cheats c WHERE c.os_id = ? ORDER BY c.sort_order ASC, c.id ASC';
}

$isEn = current_lang() === 'en';
$pageTitle = $isEn ? $scope['title_en'] : $scope['title_ru'];
require COMPONENTS_PATH . '/header.php';
$isNfaFlow = $scopeType === 'os' && $scopeSlug === 'nfa-accounts';

$stmt = $mysqli->prepare($listSql);
$stmt->bind_param('i', $scopeId);
$stmt->execute();
$res = $stmt->get_result();

$gTitle = $isEn ? $scope['title_en'] : $scope['title_ru'];
$breadcrumbs = [
    ['label' => __('nav.shop'), 'href' => asset('games.php')],
    ['label' => $gTitle, 'href' => null],
];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>

    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= h($gTitle) ?></p>
        <h1 class="ws-page-title">
            <?= h($isNfaFlow ? ($isEn ? 'Pick a game' : 'Выберите игру') : ($isEn ? 'Pick a module' : 'Выберите модуль')) ?>
        </h1>
        <p class="ws-page-lead"><?= h($isEn ? $scope['tagline_en'] : $scope['tagline_ru']) ?></p>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>

    <div class="ws-catalog-toolbar">
        <label class="ws-search" for="catalog-search">
            <img src="<?= h(asset('assets/svg/search.svg')) ?>" width="16" height="16" alt="" aria-hidden="true">
            <input id="catalog-search" type="search" autocomplete="off" placeholder="<?= h($isNfaFlow ? ($isEn ? 'Search games in NFA catalog' : 'Поиск игр в каталоге NFA') : __('shop.search_modules_placeholder')) ?>">
        </label>
    </div>

    <div class="catalog-grid ws-catalog" id="catalog-grid">
        <?php
        while ($c = $res->fetch_assoc()) {
            $title = $isEn ? $c['title_en'] : $c['title_ru'];
            $desc = $isEn ? $c['description_en'] : $c['description_ru'];
            $img = asset($c['image_path']);
            $pc = (int) ($c['plan_count'] ?? 0);
            $badge = $isEn ? $pc . ' plans' : $pc . ' тарифов';
            $blob = function_exists('mb_strtolower')
                ? mb_strtolower($title . ' ' . $desc . ' ' . $c['slug'], 'UTF-8')
                : strtolower($title . ' ' . $desc . ' ' . $c['slug']);
            ?>
            <article class="card card-hover product-card ws-game-card" data-search="<?= h($blob) ?>">
                <a class="game-card-link" href="<?= h(url_to('cheat.php', [$scopeType => $scopeSlug, 'cheat' => $c['slug']])) ?>">
                    <div class="ws-card-media">
                        <span class="undetect-badge"><i></i><?= $isNfaFlow ? 'OFFLINE' : 'UNDETECT' ?></span>
                        <img src="<?= h($img) ?>" alt="<?= h($title) ?>" width="640" height="400" loading="lazy" decoding="async">
                        <span class="ws-card-badge"><?= h($badge) ?></span>
                    </div>
                    <div class="ws-card-foot">
                        <div>
                            <h2><?= h($title) ?></h2>
                            <span class="ws-card-meta"><span class="ws-meta-dot" aria-hidden="true"></span> <?= $isNfaFlow ? ($isEn ? 'Game' : 'Игра') : ($isEn ? 'Module' : 'Модуль') ?></span>
                        </div>
                        <span class="ws-card-open"><?= h(__('shop.card_open')) ?> →</span>
                    </div>
                </a>
            </article>
            <?php
        }
        $res->free();
        $stmt->close();
        ?>
    </div>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
