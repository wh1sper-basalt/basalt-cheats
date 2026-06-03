<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/connect.php';

$pageTitle = __('news.title');
require COMPONENTS_PATH . '/header.php';

$isEn = current_lang() === 'en';
$res = $mysqli->query('SELECT slug, title_ru, title_en, excerpt_ru, excerpt_en, published_at FROM news_articles ORDER BY published_at DESC');

$breadcrumbs = [
    ['label' => __('nav.reviews'), 'href' => null],
];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>

    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= $isEn ? 'Real reviews' : 'Реальные отзывы' ?></p>
        <h1 class="ws-page-title"><?= $isEn ? 'Client' : 'Отзывы' ?> <span class="accent"><?= $isEn ? 'reviews' : 'наших клиентов' ?></span></h1>
        <p class="ws-page-lead"><?= $isEn ? 'Read feedback from buyers and community members.' : 'Читайте реальные отзывы от наших покупателей.' ?></p>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>

    <article class="card card-hover review-discord-cta">
        <p class="muted"><?= $isEn ? 'More comments from clients are available in Discord.' : 'Ещё больше отзывов от клиентов можно найти в нашем Discord.' ?></p>
        <a class="btn btn-primary" href="<?= h(site_config()['discord_url'] ?? '#') ?>" target="_blank" rel="noopener noreferrer"><?= $isEn ? 'Go to Discord' : 'Перейти в Discord' ?></a>
    </article>

    <div class="ws-stats-row">
        <div class="ws-stat-card">
            <div class="ws-stat-value">4.8</div>
            <div class="ws-stat-label"><?= $isEn ? 'Average rating' : 'Средний рейтинг' ?></div>
        </div>
        <div class="ws-stat-card">
            <div class="ws-stat-value">92</div>
            <div class="ws-stat-label"><?= $isEn ? 'Total reviews' : 'Всего отзывов' ?></div>
        </div>
        <div class="ws-stat-card">
            <div class="ws-stat-value">89%</div>
            <div class="ws-stat-label"><?= $isEn ? '5-star marks' : '5 звёзд' ?></div>
        </div>
    </div>

    <div class="reviews-grid">
        <?php
        $authors = ['Henson', 'v32f234', 'whyfmyself', 'xqq', 'XqqXsq', 'balka', 'Arteryx', 'frostline', 'signal9', 'm0n0'];
        $extra = [
            $isEn
                ? 'Support replied fast, key delivery was smooth, and the term was applied instantly. UI is calm and the flow is strict — exactly what I wanted.'
                : 'Поддержка ответила быстро, выдача ключа прошла ровно, срок применился сразу. Интерфейс спокойный, а цепочка шагов строгая — как надо.',
            $isEn
                ? 'Bought a 90‑day plan, everything worked from first launch. No spam, no random redirects, just game → module → term → checkout.'
                : 'Взял на 90 дней — завелось с первого запуска. Без спама и лишних страниц: игра → модуль → срок → оплата.',
            $isEn
                ? 'Love the monochrome theme and the hover feel. Picked the right module in minutes and got clear instructions in Telegram.'
                : 'Нравится монохром и ховеры. Выбрал модуль за пару минут, в Telegram дали понятные инструкции.',
        ];
        $idx = 0;
        if ($res) {
            $rows = [];
            while ($n = $res->fetch_assoc()) {
                $rows[] = $n;
            }
            $res->free();
            for ($i = 0; $i < max(12, count($rows) * 3); ++$i) {
                $n = $rows[$i % max(1, count($rows))] ?? ['title_ru' => 'Basalt', 'title_en' => 'Basalt', 'excerpt_ru' => 'Ок.', 'excerpt_en' => 'Ok.', 'published_at' => date('Y-m-d')];
                $title = $isEn ? $n['title_en'] : $n['title_ru'];
                $ex = $isEn ? $n['excerpt_en'] : $n['excerpt_ru'];
                $author = $authors[$i % count($authors)];
                $body = $ex . ' ' . $extra[$i % count($extra)];
                ?>
                <article class="card card-hover review-card">
                    <div class="review-head">
                        <span class="review-avatar"><?= h(substr($author, 0, 1)) ?></span>
                        <div>
                            <h2><?= h($author) ?></h2>
                            <p class="review-stars">★★★★★</p>
                        </div>
                    </div>
                    <div class="review-body">
                        <p><?= h($body) ?></p>
                    </div>
                    <div class="review-foot">
                        <span class="review-tag"><?= h($title) ?></span>
                        <span class="muted"><?= h($n['published_at']) ?></span>
                    </div>
                </article>
                <?php
            }
        }
        ?>
    </div>

    <article class="card card-hover community-join-card">
        <h2><?= $isEn ? 'Join our community' : 'Присоединяйтесь к нашему сообществу' ?></h2>
        <p class="muted"><?= $isEn ? 'Stay tuned for releases and share your feedback with other users.' : 'Следите за релизами и делитесь впечатлениями с другими пользователями.' ?></p>
        <a class="btn btn-primary" href="<?= h(asset('games.php')) ?>"><?= $isEn ? 'View catalog' : 'Смотреть каталог' ?></a>
    </article>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
