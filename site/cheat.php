<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/connect.php';

$gameSlug = preg_replace('/[^a-z0-9\-]/i', '', (string) ($_GET['game'] ?? ''));
$osSlug = preg_replace('/[^a-z0-9\-]/i', '', (string) ($_GET['os'] ?? ''));
$cheatSlug = preg_replace('/[^a-z0-9\-]/i', '', (string) ($_GET['cheat'] ?? ''));
if ((($gameSlug === '' && $osSlug === '') || ($gameSlug !== '' && $osSlug !== '')) || $cheatSlug === '') {
    redirect('games.php');
}

if ($gameSlug !== '') {
    $scopeType = 'game';
    $scopeSlug = $gameSlug;
    $sql = 'SELECT c.*, g.slug AS scope_slug, g.title_ru AS scope_title_ru, g.title_en AS scope_title_en
            FROM cheats c INNER JOIN games g ON g.id = c.game_id
            WHERE g.slug = ? AND c.slug = ? LIMIT 1';
} else {
    $scopeType = 'os';
    $scopeSlug = $osSlug;
    $sql = 'SELECT c.*, o.slug AS scope_slug, o.title_ru AS scope_title_ru, o.title_en AS scope_title_en
            FROM cheats c INNER JOIN os o ON o.id = c.os_id
            WHERE o.slug = ? AND c.slug = ? LIMIT 1';
}
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('ss', $scopeSlug, $cheatSlug);
$stmt->execute();
$cheat = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$cheat) {
    redirect('games.php');
}

$mStmt = $mysqli->prepare('SELECT * FROM cheat_media WHERE cheat_id = ? ORDER BY sort_order ASC, id ASC');
$mStmt->bind_param('i', $cheat['id']);
$mStmt->execute();
$mediaRes = $mStmt->get_result();
$mediaRows = [];
while ($row = $mediaRes->fetch_assoc()) {
    $mediaRows[] = $row;
}
$mStmt->close();
if (count($mediaRows) < 3) {
    while (count($mediaRows) < 3) {
        $n = count($mediaRows) + 1;
        $mediaRows[] = [
            'image_path' => 'assets/img/cheats/albums/' . $cheatSlug . '-' . sprintf('%02d', $n) . '.png',
            'caption_ru' => '',
            'caption_en' => '',
        ];
    }
}

$fStmt = $mysqli->prepare('SELECT * FROM cheat_features WHERE cheat_id = ? ORDER BY feature_group ASC, sort_order ASC, id ASC');
$fStmt->bind_param('i', $cheat['id']);
$fStmt->execute();
$featuresRes = $fStmt->get_result();
$featureGroups = [];
while ($f = $featuresRes->fetch_assoc()) {
    $grp = trim((string) $f['feature_group']);
    if ($grp === '') {
        $grp = 'MISC';
    }
    if (!isset($featureGroups[$grp])) {
        $featureGroups[$grp] = [];
    }
    $featureGroups[$grp][] = $f;
}
$fStmt->close();

$pStmt = $mysqli->prepare('SELECT * FROM key_plans WHERE cheat_id = ? ORDER BY sort_order ASC, id ASC');
$pStmt->bind_param('i', $cheat['id']);
$pStmt->execute();
$plansRes = $pStmt->get_result();
$plans = [];
while ($p = $plansRes->fetch_assoc()) {
    $plans[] = $p;
}
$pStmt->close();
if (!$plans) {
    redirect('cheats.php', [$scopeType => $scopeSlug]);
}

$reqRows = [];
$reqStmt = $mysqli->prepare('SELECT req_group, req_name_ru, req_name_en FROM cheat_requirements WHERE cheat_id = ? ORDER BY sort_order ASC, id ASC');
if ($reqStmt) {
    $reqStmt->bind_param('i', $cheat['id']);
    $reqStmt->execute();
    $rres = $reqStmt->get_result();
    while ($r = $rres->fetch_assoc()) {
        $reqRows[] = $r;
    }
    $reqStmt->close();
}
$reqGroups = [];
foreach ($reqRows as $r) {
    $grp = trim((string) $r['req_group']);
    if ($grp === '') {
        $grp = 'general';
    }
    if (!isset($reqGroups[$grp])) {
        $reqGroups[$grp] = [];
    }
    $reqGroups[$grp][] = $r;
}

$isEn = current_lang() === 'en';
$pageTitle = $isEn ? $cheat['title_en'] : $cheat['title_ru'];
require COMPONENTS_PATH . '/header.php';

$gTitle = $isEn ? $cheat['scope_title_en'] : $cheat['scope_title_ru'];
$cTitle = $isEn ? $cheat['title_en'] : $cheat['title_ru'];
$cDesc = $isEn ? $cheat['description_en'] : $cheat['description_ru'];
$isNfaFlow = $scopeType === 'os' && $scopeSlug === 'nfa-accounts';

$breadcrumbs = [
    ['label' => __('nav.shop'), 'href' => asset('games.php')],
    ['label' => $gTitle, 'href' => url_to('cheats.php', [$scopeType => $scopeSlug])],
    ['label' => $cTitle, 'href' => null],
];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= h($gTitle) ?></p>
        <h1 class="ws-page-title"><?= h($cTitle) ?></h1>
        <p class="ws-page-lead"><?= h($cDesc) ?></p>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>

    <section class="cheat-purchase-grid">
        <div class="cheat-purchase-media">
            <div class="cheat-slider" data-cheat-slider>
                <?php foreach ($mediaRows as $idx => $m) { ?>
                    <figure class="cheat-slide <?= $idx === 0 ? 'is-active' : '' ?>">
                        <img src="<?= h(asset((string) $m['image_path'])) ?>" alt="<?= h($cTitle . ' #' . ($idx + 1)) ?>" width="1920" height="1080" loading="lazy" decoding="async">
                    </figure>
                <?php } ?>
                <button class="cheat-slider-arrow is-prev" type="button" data-slide-dir="-1" aria-label="<?= h($isEn ? 'Previous image' : 'Предыдущее изображение') ?>">
                    <img src="<?= h(asset('assets/svg/chevron-left.svg')) ?>" width="18" height="18" alt="">
                </button>
                <button class="cheat-slider-arrow is-next" type="button" data-slide-dir="1" aria-label="<?= h($isEn ? 'Next image' : 'Следующее изображение') ?>">
                    <img src="<?= h(asset('assets/svg/chevron-right.svg')) ?>" width="18" height="18" alt="">
                </button>
            </div>
        </div>
        <div class="cheat-purchase-side">
            <form method="get" action="<?= h(asset('checkout.php')) ?>" class="cheat-purchase-form" id="cheat-purchase-form">
                <input type="hidden" name="<?= h($scopeType) ?>" value="<?= h($scopeSlug) ?>">
                <input type="hidden" name="cheat" value="<?= h($cheatSlug) ?>">
                <h2 class="cheat-purchase-title"><?= h($isNfaFlow ? __('shop.select_accounts') : __('shop.pick_subscription')) ?></h2>
                <div class="custom-select-wrap custom-select-grayscale" data-custom-select>
                    <input type="hidden" name="plan" value="<?= (int) $plans[0]['id'] ?>" data-custom-input>
                    <button type="button" class="custom-select-trigger" data-custom-trigger>
                        <span data-custom-label>
                            <?php
                            $firstLabel = $isEn ? $plans[0]['label_en'] : $plans[0]['label_ru'];
                            $firstPrice = number_format((int) $plans[0]['price_rub'], 0, '', ' ');
                            echo h($firstLabel . ' — ' . $firstPrice . ($isEn ? ' RUB' : ' ₽'));
                            ?>
                        </span>
                        <span class="custom-select-caret"><img src="<?= h(asset('assets/svg/chevron-down.svg')) ?>" width="14" height="14" alt=""></span>
                    </button>
                    <ul class="custom-select-menu" data-custom-menu hidden>
                        <?php foreach ($plans as $idx => $p) {
                            $label = $isEn ? $p['label_en'] : $p['label_ru'];
                            $price = number_format((int) $p['price_rub'], 0, '', ' ');
                            ?>
                            <li>
                                <button
                                    type="button"
                                    class="custom-select-option<?= $idx === 0 ? ' is-selected' : '' ?>"
                                    data-custom-option
                                    data-value="<?= (int) $p['id'] ?>"
                                    data-label="<?= h($label . ' — ' . $price . ($isEn ? ' RUB' : ' ₽')) ?>"
                                >
                                    <?= h($label . ' — ' . $price . ($isEn ? ' RUB' : ' ₽')) ?>
                                </button>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="cheat-purchase-pay">
                    <button class="btn btn-primary" type="submit"><?= h($isEn ? 'Payment' : 'Оплата') ?></button>
                </div>
            </form>
        </div>
    </section>

    <?php if ($reqGroups !== []) { ?>
    <section class="cheat-feature-section stack">
        <h2><?= h($isEn ? 'Compatibility' : 'Совместимость') ?></h2>
        <div class="cheat-feature-grid">
            <?php foreach ($reqGroups as $groupName => $rows) { ?>
                <article class="card card-hover">
                    <h3><?= h(strtoupper($groupName)) ?></h3>
                    <ul class="list">
                        <?php foreach ($rows as $r) { ?>
                            <li><?= h($isEn ? $r['req_name_en'] : $r['req_name_ru']) ?></li>
                        <?php } ?>
                    </ul>
                </article>
            <?php } ?>
        </div>
    </section>
    <?php } ?>

    <section class="cheat-feature-section stack">
        <h2><?= h($isEn ? 'Features' : 'Функционал') ?></h2>
        <div class="cheat-feature-grid">
            <?php foreach ($featureGroups as $groupName => $rows) { ?>
                <article class="card card-hover">
                    <h3><?= h($groupName) ?></h3>
                    <ul class="list">
                        <?php foreach ($rows as $f) { ?>
                            <li><?= h($isEn ? $f['feature_name_en'] : $f['feature_name_ru']) ?></li>
                        <?php } ?>
                    </ul>
                </article>
            <?php } ?>
        </div>
    </section>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
