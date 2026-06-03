<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/connect.php';

$pageTitle = __('chronicle.title');
require COMPONENTS_PATH . '/header.php';

$isEn = current_lang() === 'en';
$res = $mysqli->query('SELECT c.slug, c.title_ru, c.title_en, c.image_path, g.slug AS game_slug, g.title_ru AS game_ru, g.title_en AS game_en
    FROM cheats c INNER JOIN games g ON g.id = c.game_id ORDER BY c.id DESC');
?>
<div class="container">
    <?php
    $breadcrumbs = [
        ['label' => __('nav.blog'), 'href' => null],
    ];
    require COMPONENTS_PATH . '/breadcrumbs.php';
    ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= $isEn ? 'Documentation hub' : 'Блог обновлений' ?></p>
        <h1 class="ws-page-title"><?= $isEn ? 'Recently updated' : 'Недавно обновлено' ?> <span class="accent"><?= $isEn ? 'modules' : 'модули' ?></span></h1>
        <p class="ws-page-lead"><?= $isEn ? 'Cards with current module status and quick guides.' : 'Карточки читов с актуальным статусом и быстрым переходом.' ?></p>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>
    
    <div class="catalog-grid ws-catalog">
        <?php
        if ($res) {
            while ($n = $res->fetch_assoc()) {
                $title = $isEn ? $n['title_en'] : $n['title_ru'];
                $game = $isEn ? $n['game_en'] : $n['game_ru'];
                ?>
                <article class="card card-hover product-card ws-game-card">
                    <a class="game-card-link" href="<?= h(url_to('plans.php', ['game' => $n['game_slug'], 'cheat' => $n['slug']])) ?>">
                        <div class="ws-card-media">
                            <img src="<?= h(asset($n['image_path'])) ?>" alt="<?= h($title) ?>" loading="lazy" decoding="async">
                            <span class="ws-card-badge ws-badge-live"><i aria-hidden="true"></i>UP TO DATE</span>
                        </div>
                        <div class="ws-card-foot">
                            <div>
                                <h2><?= h($title) ?></h2>
                                <span class="ws-card-meta"><span aria-hidden="true">⌕</span> <?= h($game) ?></span>
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
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
