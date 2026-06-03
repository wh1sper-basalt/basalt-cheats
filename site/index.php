<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/connect.php';

$pageTitle = __('nav.home');
$mainClass = 'site-main index';
require COMPONENTS_PATH . '/header.php';

$isEn = current_lang() === 'en';
$games = $mysqli->query('SELECT g.*, (SELECT COUNT(*) FROM cheats c WHERE c.game_id = g.id) AS cheat_count FROM games g ORDER BY g.sort_order ASC, g.id ASC');

$osCards = [];
$osCardsRes = $mysqli->query('SELECT o.*, (SELECT COUNT(*) FROM cheats c WHERE c.os_id = o.id) AS cheat_count FROM os o ORDER BY o.sort_order ASC, o.id ASC');
if ($osCardsRes) {
    while ($o = $osCardsRes->fetch_assoc()) {
        $osCards[] = $o;
    }
    $osCardsRes->free();
}
?>

<section class="section s1 home-hero">
    <div class="container split hero-home-grid">
        <div class="stack">
            <div class="home-hero-spacer"></div>
            <h1 class="hero-title-home"><?= $isEn ? 'DOMINATE EVERY GAME' : 'ДОМИНИРУЙ В КАЖДОЙ ИГРЕ' ?></h1>
            <p class="lead"><?= $isEn ? 'Private modules with direct checkout and clear key terms.' : 'Приватные модули с понятной цепочкой покупки и сроком ключа.' ?></p>
            <div class="home-trust-row">
                <span class="home-trust-pill"><i></i><?= $isEn ? 'Safety' : 'Безопасность' ?></span>
                <span class="home-trust-pill"><i></i><?= $isEn ? 'Stability' : 'Стабильность' ?></span>
                <span class="home-trust-pill"><i></i><?= $isEn ? 'Support 24/7' : 'Поддержка 24/7' ?></span>
            </div>
            <div class="hero-actions">
                <a class="btn btn-primary" href="<?= h(asset('games.php')) ?>"><?= $isEn ? 'Go to catalog' : 'Перейти в каталог' ?></a>
            </div>
        </div>
        <div class="hero-quad hero-quad-links">
            <?php
            $quad = $mysqli->query('SELECT g.slug, g.title_ru, g.title_en, g.image_path, COUNT(c.id) AS cheat_count
                FROM games g
                LEFT JOIN cheats c ON c.game_id = g.id
                GROUP BY g.id, g.slug, g.title_ru, g.title_en, g.image_path
                ORDER BY g.sort_order ASC, g.id ASC
                LIMIT 3');

            if ($quad) {
                while ($q = $quad->fetch_assoc()) {
                    $qt = $isEn ? $q['title_en'] : $q['title_ru'];
                    ?>
                    <a class="hero-quad-card" href="<?= h(url_to('cheats.php', ['game' => $q['slug']])) ?>">
                        <img src="<?= h(asset($q['image_path'])) ?>" alt="<?= h($qt) ?>" loading="lazy">
                        <span class="hero-quad-pill"><?= (int) ($q['cheat_count'] ?? 0) ?> <?= $isEn ? 'modules' : 'модулей' ?></span>
                    </a>
                    <?php
                }
                $quad->free();
            }
            ?>
            <a class="hero-quad-card" href="<?= h(url_to('cheats.php', ['os' => 'hwid-spoofer'])) ?>">
                <img src="<?= h(asset('assets/img/games/hwid-spoofer.webp')) ?>" alt="HWID Spoofer" loading="lazy">
                <span class="hero-quad-pill">
                    <?php
                    $heroHwidCount = 0;
                    foreach ($osCards as $oc) {
                        if (($oc['slug'] ?? '') === 'hwid-spoofer') {
                            $heroHwidCount = (int) ($oc['cheat_count'] ?? 0);
                            break;
                        }
                    }
                    ?>
                    <?= $heroHwidCount ?> <?= $isEn ? 'editions' : 'изданий' ?>
                </span>
            </a>
        </div>
    </div>
</section>

<section class="section s2">
    <div class="container">
        <div class="stats-loop-wrap">
            <div class="stats-loop-track">
                <div class="stats-loop-item">
                    <strong>4500+</strong><span><?= $isEn ? 'Clients' : 'Клиентов' ?></span>
                </div>
                <div class="stats-loop-item">
                    <strong>1200+</strong><span><?= $isEn ? 'Reviews' : 'Отзывов' ?></span>
                </div>
                <div class="stats-loop-item">
                    <strong>99.3%</strong><span>Undetected</span>
                </div>
                <div class="stats-loop-item">
                    <strong><?= $isEn ? 'Instant' : 'Мгновенно' ?></strong><span><?= $isEn ? 'Delivery' : 'Выдача' ?></span>
                </div>
                <div class="stats-loop-item">
                    <strong><?= $isEn ? 'Regular' : 'Регулярно' ?></strong><span><?= $isEn ? 'Updates' : 'Обновления' ?></span>
                </div>
                <div class="stats-loop-item">
                    <strong><?= $isEn ? 'All top games' : 'Все топовые игры' ?></strong><span><?= $isEn ? 'Support' : 'Поддержка' ?></span>
                </div>
                <div class="stats-loop-item">
                    <strong>wh1sper</strong><span><?= $isEn ? 'Author' : 'Автор' ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section s3">
    <div class="container">
        <header class="ws-page-hero">
            <p class="ws-hero-badge"><?= h(__('shop.hero_badge')) ?></p>
            <h2 class="ws-page-title"><?= h(__('shop.hero_title_1')) ?> <span class="accent"><?= h(__('shop.hero_title_2')) ?></span></h2>
            <p class="ws-page-lead"><?= h(__('shop.hero_lead')) ?></p>
        </header>
        <div class="ws-catalog-toolbar">
            <label class="ws-search" for="catalog-search">
                <img src="<?= h(asset('assets/svg/search.svg')) ?>" width="16" height="16" alt="" aria-hidden="true">
                <input id="catalog-search" type="search" autocomplete="off" placeholder="<?= h(__('shop.search_games_placeholder')) ?>">
            </label>
        </div>
        <div class="catalog-grid ws-catalog" id="catalog-grid">
            <?php
            if ($games) {
                while ($g = $games->fetch_assoc()) {
                    $title = $isEn ? $g['title_en'] : $g['title_ru'];
                    $cnt = (int) ($g['cheat_count'] ?? 0);
                    $badge = $isEn ? $cnt . ' modules' : $cnt . ' модулей';
                    $blob = function_exists('mb_strtolower')
                        ? mb_strtolower($title . ' ' . $g['slug'], 'UTF-8')
                        : strtolower($title . ' ' . $g['slug']);
                    ?>
                    <article class="card card-hover product-card ws-game-card" data-search="<?= h($blob) ?>">
                        <a class="game-card-link" href="<?= h(url_to('cheats.php', ['game' => $g['slug']])) ?>">
                            <div class="ws-card-media">
                                <img src="<?= h(asset($g['image_path'])) ?>" alt="<?= h($title) ?>" width="640" height="400" loading="lazy">
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
                $games->free();
            }

            foreach ($osCards as $oc) {
                $title = $isEn ? $oc['title_en'] : $oc['title_ru'];
                $cnt = (int) ($oc['cheat_count'] ?? 0);
                $isNfaCard = ($oc['slug'] ?? '') === 'nfa-accounts';
                $badge = $isNfaCard
                    ? ($isEn ? $cnt . ' games' : $cnt . ' игр')
                    : ($isEn ? $cnt . ' editions' : $cnt . ' изданий');
                $blob = function_exists('mb_strtolower')
                    ? mb_strtolower($title . ' ' . $oc['slug'], 'UTF-8')
                    : strtolower($title . ' ' . $oc['slug']);
                ?>
                <article class="card card-hover product-card ws-game-card" data-search="<?= h($blob) ?>">
                    <a class="game-card-link" href="<?= h(url_to('cheats.php', ['os' => $oc['slug']])) ?>">
                        <div class="ws-card-media">
                            <img src="<?= h(asset($oc['image_path'])) ?>" alt="<?= h($title) ?>" width="640" height="400" loading="lazy">
                            <span class="ws-card-badge"><?= h($badge) ?></span>
                        </div>
                        <div class="ws-card-foot">
                            <div>
                                <h2><?= h($title) ?></h2>
                                <span class="ws-card-meta"><span class="ws-meta-dot" aria-hidden="true"></span> <?= (($oc['slug'] ?? '') === 'nfa-accounts') ? ($isEn ? 'Accounts' : 'Аккаунты') : ($isEn ? 'Utilities' : 'Утилиты') ?></span>
                            </div>
                            <span class="ws-card-open"><?= h(__('shop.card_open')) ?> →</span>
                        </div>
                    </a>
                </article>
                <?php
            }
            ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <header class="ws-page-hero" style="margin-bottom: 18px;">
            <p class="ws-hero-badge"><?= $isEn ? 'News channel' : 'Новостной канал' ?></p>
            <h2 class="ws-page-title"><?= $isEn ? 'Community updates' : 'Общение сообщества' ?></h2>
            <p class="ws-page-lead"><?= $isEn ? 'Announcements, changelogs, and key delivery status — without parsing.' : 'Анонсы, чейнджлоги и статусы выдачи ключей — без парсинга сообщений.' ?></p>
        </header>
        <article class="card card-hover tg-promo-card">
            <div class="tg-promo-head">
                <div class="tg-avatar" aria-hidden="true">
                    <img class="tg-avatar-img" src="<?= h(asset('assets/img/components/telegram-channel-logo.webp')) ?>" alt="" width="54" height="54" loading="lazy" onerror="this.remove();this.parentElement.classList.add('is-fallback');">
                    <span class="tg-avatar-fallback">TG</span>
                </div>
                <div>
                    <h3><?= $isEn ? 'Basalt Shop Channel' : 'Basalt Shop Channel' ?></h3>
                    <p class="muted"><?= $isEn ? 'Live announcements and maintenance status' : 'Анонсы, статусы обновлений и выдачи ключей' ?></p>
                </div>
            </div>
            <div class="tg-promo-stats">
                <span>1.2K <?= $isEn ? 'members' : 'участников' ?></span>
                <span>94% <?= $isEn ? 'active' : 'активность' ?></span>
                <span>32 <?= $isEn ? 'posts/week' : 'постов/нед' ?></span>
                <span>18 <?= $isEn ? 'avg replies' : 'средний ответ' ?> <?= $isEn ? 'min' : 'мин' ?></span>
                <span>24/7 <?= $isEn ? 'status' : 'статус' ?></span>
            </div>
            <div class="tg-discussion card-glass">
                <p class="muted" style="margin:0 0 10px;">
                    <?= $isEn ? 'Discussion preview' : 'Превью обсуждений' ?>
                </p>
                <script
                    async
                    src="https://telegram.org/js/telegram-widget.js?23"
                    data-telegram-discussion="contest/198"
                    data-comments-limit="5"
                ></script>
            </div>
            <a class="btn btn-ghost" href="<?= h(site_config()['telegram_url'] ?? '#') ?>" target="_blank" rel="noopener noreferrer">
                <?= $isEn ? 'Open channel' : 'Открыть канал' ?>
            </a>
        </article>
    </div>
</section>

<section class="section">
    <div class="container">
        <article class="card card-hover home-about-card">
            <h2><?= $isEn ? 'Buy private cheats for popular games' : 'Купить приватные читы для популярных игр 2026' ?></h2>
            <p class="muted"><?= h(__('index.seo_lead')) ?></p>
            <p class="muted"><?= $isEn
                ? 'If you need help with setup or updates, use the Support page: we guide you through the exact steps and verify the request details. No hidden subscriptions — only the term you choose.'
                : 'Если нужна помощь с установкой или обновлениями — используйте страницу «Поддержка»: там шаги и быстрый контакт с оператором. Без скрытых подписок — только выбранный вами срок.' ?></p>
            <div class="home-about-stats">
                <div><strong>10+</strong><span><?= $isEn ? 'top games' : 'топ-игр' ?></span></div>
                <div><strong>30+</strong><span><?= $isEn ? 'private modules' : 'приватных модулей' ?></span></div>
                <div><strong>24/7</strong><span><?= $isEn ? 'support online' : 'поддержка онлайн' ?></span></div>
            </div>
        </article>
    </div>
</section>

<?php require COMPONENTS_PATH . '/footer.php'; ?>
