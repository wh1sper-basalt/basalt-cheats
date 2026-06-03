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
$cr = $stmt->get_result();
$cheat = $cr->fetch_assoc();
$stmt->close();
if (!$cheat) {
    redirect('games.php');
}

$isEn = current_lang() === 'en';
$pageTitle = $isEn ? $cheat['title_en'] : $cheat['title_ru'];
require COMPONENTS_PATH . '/header.php';
$isNfaFlow = $scopeType === 'os' && $scopeSlug === 'nfa-accounts';

$pstmt = $mysqli->prepare('SELECT * FROM key_plans WHERE cheat_id = ? ORDER BY sort_order ASC, id ASC');
$pstmt->bind_param('i', $cheat['id']);
$pstmt->execute();
$pres = $pstmt->get_result();

// Functional features
$featRows = [];
$featStmt = $mysqli->prepare('SELECT feature_group, feature_name_ru, feature_name_en FROM cheat_features WHERE cheat_id = ? ORDER BY sort_order ASC, id ASC');
if ($featStmt) {
    $featStmt->bind_param('i', $cheat['id']);
    $featStmt->execute();
    $fres = $featStmt->get_result();
    while ($r = $fres->fetch_assoc()) {
        $featRows[] = $r;
    }
    $featStmt->close();
}

// System requirements
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

$gTitle = $isEn ? $cheat['scope_title_en'] : $cheat['scope_title_ru'];
$cTitle = $isEn ? $cheat['title_en'] : $cheat['title_ru'];
$breadcrumbs = [
    ['label' => __('nav.shop'), 'href' => asset('games.php')],
    ['label' => $gTitle, 'href' => url_to('cheats.php', [$scopeType => $scopeSlug])],
    ['label' => $cTitle, 'href' => null],
];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>

    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= h($cTitle) ?></p>
        <h1 class="ws-page-title"><?= h($isNfaFlow ? __('shop.select_accounts') : __('shop.pick_duration')) ?></h1>
        <p class="ws-page-lead"><?= h($isNfaFlow
                ? ($isEn ? 'Choose how many NFA accounts you need before checkout.' : 'Выберите нужное количество NFA-аккаунтов перед оплатой.')
                : ($isEn ? 'Lock the term before checkout — you can still go back to other modules.' : 'Зафиксируйте срок перед оплатой — можно вернуться к другим модулям.')) ?></p>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>

    <div class="plans-grid">
        <?php
        while ($p = $pres->fetch_assoc()) {
            $label = $isEn ? $p['label_en'] : $p['label_ru'];
            $price = (int) $p['price_rub'];
            $href = url_to('checkout.php', [
                $scopeType => $scopeSlug,
                'cheat' => $cheatSlug,
                'plan' => (string) $p['id'],
            ]);
            if ($isNfaFlow) {
                $qty = (int) $p['duration_days'];
                $daysLabel = $qty . ' ' . ($isEn ? 'accounts' : 'аккаунтов');
            } else {
                $daysLabel = (int) $p['duration_days'] === 0
                    ? ($isEn ? 'Forever' : 'Навсегда')
                    : ((int) $p['duration_days'] . ' ' . ($isEn ? 'days' : 'дн.'));
            }
            ?>
            <a class="card card-hover plan-tile <?= $isNfaFlow ? 'nfa-plan-tile' : '' ?>" href="<?= h($href) ?>">
                <span class="ws-status-pill" style="align-self: flex-start;"><?= $isEn ? 'Ready' : 'Готово' ?></span>
                <h3><?= h($label) ?></h3>
                <p class="plan-days muted"><?= h($daysLabel) ?></p>
                <p class="plan-price"><?= h(number_format($price, 0, '', ' ')) ?> <?= $isEn ? 'RUB' : '₽' ?></p>
                <?php if ($isNfaFlow && (int) $p['duration_days'] >= 5) { ?>
                    <span class="muted"><?= h($isEn ? 'Popular choice' : 'Популярный выбор') ?></span>
                <?php } ?>
                <span class="btn btn-ghost"><?= h(__('nav.checkout')) ?> <img src="<?= h(asset('assets/svg/arrow-right.svg')) ?>" width="14" height="14" alt=""></span>
            </a>
            <?php
        }
        $pres->free();
        $pstmt->close();
        ?>
    </div>

    <section class="card card-hover stack" style="margin-top:18px;">
        <header class="ws-page-hero" style="padding:0;">
            <p class="ws-hero-badge"><?= h($isEn ? 'Details' : 'Детали') ?></p>
            <h2 class="ws-page-title" style="font-size:1.4rem;"><?= h($isEn ? 'Compatibility and features' : 'Совместимость и функционал') ?></h2>
            <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
        </header>

        <div class="grid cols-2" style="margin-top:14px;">
            <article class="card card-hover stack">
                <h3><?= h($isEn ? 'Compatibility' : 'Совместимость') ?></h3>
                <?php if ($reqRows === []) { ?>
                    <p class="muted"><?= h($isEn ? 'No requirements provided yet.' : 'Требования пока не добавлены.') ?></p>
                <?php } else { ?>
                    <ul class="list">
                        <?php foreach ($reqRows as $r) { ?>
                            <li><?= h($isEn ? $r['req_name_en'] : $r['req_name_ru']) ?></li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </article>

            <article class="card card-hover stack">
                <h3><?= h($isEn ? 'Functional' : 'Функционал') ?></h3>
                <?php if ($featRows === []) { ?>
                    <p class="muted"><?= h($isEn ? 'No features provided yet.' : 'Функции пока не добавлены.') ?></p>
                <?php } else { ?>
                    <ul class="list">
                        <?php foreach ($featRows as $r) { ?>
                            <li><?= h($isEn ? $r['feature_name_en'] : $r['feature_name_ru']) ?></li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </article>
        </div>
    </section>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
