<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/connect.php';

$pageTitle = __('nav.shop');
require COMPONENTS_PATH . '/header.php';

$isEn = current_lang() === 'en';
$res = $mysqli->query('SELECT g.*, (SELECT COUNT(*) FROM cheats c WHERE c.game_id = g.id) AS cheat_count FROM games g ORDER BY g.sort_order ASC, g.id ASC');
$osRes = $mysqli->query('SELECT o.*, (SELECT COUNT(*) FROM cheats c WHERE c.os_id = o.id) AS cheat_count FROM os o ORDER BY o.sort_order ASC, o.id ASC');

$breadcrumbs = [
    ['label' => __('nav.shop'), 'href' => null],
];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>

    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= h(__('shop.hero_badge')) ?></p>
        <h1 class="ws-page-title">
            <?= h(__('shop.hero_title_1')) ?> <span class="accent"><?= h(__('shop.hero_title_2')) ?></span>
        </h1>
        <p class="ws-page-lead"><?= h(__('shop.hero_lead')) ?></p>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>
    <div class="ws-stats-row">
        <div class="ws-stat-card">
            <div class="ws-stat-value">24/7</div>
            <div class="ws-stat-label"><?= h(__('shop.stat_clients')) ?></div>
        </div>
        <div class="ws-stat-card">
            <div class="ws-stat-value">120+</div>
            <div class="ws-stat-label"><?= h(__('shop.stat_reviews')) ?></div>
        </div>
        <div class="ws-stat-card">
            <div class="ws-stat-value"><?= h(__('shop.stat_delivery_instant')) ?></div>
            <div class="ws-stat-label"><?= h(__('shop.stat_delivery')) ?></div>
        </div>
    </div>

    <div class="ws-catalog-toolbar">
        <label class="ws-search" for="catalog-search">
            <img src="<?= h(asset('assets/svg/search.svg')) ?>" width="16" height="16" alt="" aria-hidden="true">
            <input id="catalog-search" type="search" autocomplete="off" placeholder="<?= h(__('shop.search_games_placeholder')) ?>">
        </label>
    </div>

    <div class="catalog-grid ws-catalog" id="catalog-grid">
        <?php
        if ($res) {
            while ($g = $res->fetch_assoc()) {
                $title = $isEn ? $g['title_en'] : $g['title_ru'];
                $tag = $isEn ? $g['tagline_en'] : $g['tagline_ru'];
                $img = asset($g['image_path']);
                $cnt = (int) ($g['cheat_count'] ?? 0);
                $badge = $isEn ? $cnt . ' modules' : $cnt . ' модулей';
                $blob = function_exists('mb_strtolower')
                    ? mb_strtolower($title . ' ' . $tag . ' ' . $g['slug'], 'UTF-8')
                    : strtolower($title . ' ' . $tag . ' ' . $g['slug']);
                ?>
                <article class="card card-hover product-card ws-game-card" data-search="<?= h($blob) ?>">
                    <a class="game-card-link" href="<?= h(url_to('cheats.php', ['game' => $g['slug']])) ?>">
                        <div class="ws-card-media">
                            <span class="ws-status-pill ws-card-status"><?= $isEn ? 'Up to date' : 'Актуально' ?></span>
                            <img src="<?= h($img) ?>" alt="<?= h($title) ?>" width="640" height="400" loading="lazy" decoding="async">
                            <span class="ws-card-badge"><?= h($badge) ?></span>
                        </div>
                        <div class="ws-card-foot">
                            <div>
                                <h2><?= h($title) ?></h2>
                                <span class="ws-card-meta"><span class="ws-meta-dot" aria-hidden="true"></span> <?= h(__('shop.card_category')) ?></span>
                            </div>
                            <span class="ws-card-open"><?= h(__('shop.card_open')) ?> →</span>
                        </div>
                    </a>
                </article>
                <?php
            }
            $res->free();
        }
        ?>
    </div>

    <?php if ($osRes && $osRes->num_rows > 0) { ?>
        <header class="ws-page-hero" style="margin-top: 46px; margin-bottom: 18px;">
            <p class="ws-hero-badge"><?= $isEn ? 'Utilities' : 'Утилиты' ?></p>
            <h2 class="ws-page-title"><?= $isEn ? 'Utilities and accounts' : 'Утилиты и аккаунты' ?></h2>
            <p class="ws-page-lead"><?= $isEn ? 'HWID Spoofer, NFA accounts and related catalogs.' : 'HWID Spoofer, NFA-аккаунты и связанные каталоги.' ?></p>
            <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
        </header>

        <div class="catalog-grid ws-catalog">
            <?php
            while ($o = $osRes->fetch_assoc()) {
                $title = $isEn ? $o['title_en'] : $o['title_ru'];
                $tag = $isEn ? $o['tagline_en'] : $o['tagline_ru'];
                $img = asset($o['image_path']);
                $cnt = (int) ($o['cheat_count'] ?? 0);
                $isNfaCard = ($o['slug'] ?? '') === 'nfa-accounts';
                $badge = $isNfaCard
                    ? ($isEn ? $cnt . ' games' : $cnt . ' игр')
                    : ($isEn ? $cnt . ' editions' : $cnt . ' изданий');
                $blob = function_exists('mb_strtolower')
                    ? mb_strtolower($title . ' ' . $tag . ' ' . $o['slug'], 'UTF-8')
                    : strtolower($title . ' ' . $tag . ' ' . $o['slug']);
                ?>
                <article class="card card-hover product-card ws-game-card" data-search="<?= h($blob) ?>">
                    <a class="game-card-link" href="<?= h(url_to('cheats.php', ['os' => $o['slug']])) ?>">
                        <div class="ws-card-media">
                            <span class="ws-status-pill ws-card-status"><?= $isEn ? 'Up to date' : 'Актуально' ?></span>
                            <img src="<?= h($img) ?>" alt="<?= h($title) ?>" width="640" height="400" loading="lazy" decoding="async">
                            <span class="ws-card-badge"><?= h($badge) ?></span>
                        </div>
                        <div class="ws-card-foot">
                            <div>
                                <h2><?= h($title) ?></h2>
                                <span class="ws-card-meta"><span class="ws-meta-dot" aria-hidden="true"></span> <?= $isNfaCard ? ($isEn ? 'Accounts' : 'Аккаунты') : ($isEn ? 'Utility' : 'Утилита') ?></span>
                            </div>
                            <span class="ws-card-open"><?= h(__('shop.card_open')) ?> →</span>
                        </div>
                    </a>
                </article>
                <?php
            }
            $osRes->free();
            ?>
        </div>
    <?php } ?>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
