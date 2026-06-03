<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/connect.php';

$gameSlug = preg_replace('/[^a-z0-9\-]/i', '', (string) ($_GET['game'] ?? ''));
$osSlug = preg_replace('/[^a-z0-9\-]/i', '', (string) ($_GET['os'] ?? ''));
$cheatSlug = preg_replace('/[^a-z0-9\-]/i', '', (string) ($_GET['cheat'] ?? ''));
$planId = (int) ($_GET['plan'] ?? 0);

if ((($gameSlug === '' && $osSlug === '') || ($gameSlug !== '' && $osSlug !== '')) || $cheatSlug === '' || $planId < 1) {
    redirect('games.php');
}

if ($gameSlug !== '') {
    $scopeType = 'game';
    $scopeSlug = $gameSlug;
    $sql = 'SELECT kp.id AS plan_id, kp.label_ru, kp.label_en, kp.duration_days, kp.price_rub,
            c.id AS cheat_id, c.title_ru AS cheat_title_ru, c.title_en AS cheat_title_en, c.slug AS cheat_slug,
            g.id AS game_id, NULL AS os_id, g.title_ru AS scope_title_ru, g.title_en AS scope_title_en, g.slug AS scope_slug
            FROM key_plans kp
            INNER JOIN cheats c ON c.id = kp.cheat_id
            INNER JOIN games g ON g.id = c.game_id
            WHERE g.slug = ? AND c.slug = ? AND kp.id = ? LIMIT 1';
} else {
    $scopeType = 'os';
    $scopeSlug = $osSlug;
    $sql = 'SELECT kp.id AS plan_id, kp.label_ru, kp.label_en, kp.duration_days, kp.price_rub,
            c.id AS cheat_id, c.title_ru AS cheat_title_ru, c.title_en AS cheat_title_en, c.slug AS cheat_slug,
            NULL AS game_id, o.id AS os_id, o.title_ru AS scope_title_ru, o.title_en AS scope_title_en, o.slug AS scope_slug
            FROM key_plans kp
            INNER JOIN cheats c ON c.id = kp.cheat_id
            INNER JOIN os o ON o.id = c.os_id
            WHERE o.slug = ? AND c.slug = ? AND kp.id = ? LIMIT 1';
}
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('ssi', $scopeSlug, $cheatSlug, $planId);
$stmt->execute();
$ord = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$ord) {
    redirect('games.php');
}

if (empty($_SESSION['pay_qr']) || isset($_GET['newqr'])) {
    $_SESSION['pay_qr'] = bin2hex(random_bytes(16));
}

$scopeId = $scopeType === 'game' ? (int) $ord['game_id'] : (int) $ord['os_id'];
$payload = 'BASALT-PAY:' . strtoupper($scopeType) . ':' . $scopeId . ':' . $ord['cheat_id'] . ':' . $ord['plan_id'] . ':' . $_SESSION['pay_qr'];
$qrImg = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&ecc=M&data=' . rawurlencode($payload);

$isEn = current_lang() === 'en';
$pageTitle = __('checkout.title');
require COMPONENTS_PATH . '/header.php';
$isNfaFlow = $scopeType === 'os' && $scopeSlug === 'nfa-accounts';

$gTitle = $isEn ? $ord['scope_title_en'] : $ord['scope_title_ru'];
$cTitle = $isEn ? $ord['cheat_title_en'] : $ord['cheat_title_ru'];
$pLabel = $isEn ? $ord['label_en'] : $ord['label_ru'];
$price = (int) $ord['price_rub'];
if ($isNfaFlow) {
    $qty = (int) $ord['duration_days'];
    $daysLabel = $qty . ' ' . ($isEn ? 'accounts' : 'аккаунтов');
} else {
    $daysLabel = (int) $ord['duration_days'] === 0
        ? ($isEn ? 'Forever' : 'Навсегда')
        : ((int) $ord['duration_days'] . ' ' . ($isEn ? 'days' : 'дн.'));
}

$newQrUrl = url_to('checkout.php', array_merge(
    [$scopeType => $scopeSlug, 'cheat' => $cheatSlug, 'plan' => (string) $planId, 'newqr' => '1']
));

$breadcrumbs = [
    ['label' => __('nav.shop'), 'href' => asset('games.php')],
    ['label' => $gTitle, 'href' => url_to('cheats.php', [$scopeType => $scopeSlug])],
    ['label' => $cTitle, 'href' => url_to('cheat.php', [$scopeType => $scopeSlug, 'cheat' => $cheatSlug])],
    ['label' => __('checkout.title'), 'href' => null],
];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>

    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= h(__('nav.checkout')) ?></p>
        <h1 class="ws-page-title"><?= h(__('checkout.title')) ?></h1>
        <p class="ws-page-lead"><?= h(__('checkout.lead')) ?></p>
    </header>

    <div class="order-summary card card-hover">
        <h2 class="summary-heading"><?= $isEn ? 'Your selection' : 'Ваш выбор' ?></h2>
        <ul class="summary-list">
            <li><span class="muted"><?= $scopeType === 'game' ? ($isEn ? 'Game' : 'Игра') : ($isEn ? 'Utility' : 'Утилита') ?></span> <?= h($gTitle) ?></li>
            <li><span class="muted"><?= $isEn ? ($isNfaFlow ? 'Game' : 'Cheat') : ($isNfaFlow ? 'Игра' : 'Чит') ?></span> <?= h($cTitle) ?></li>
            <li><span class="muted"><?= h($isNfaFlow ? ($isEn ? 'Account quantity' : 'Количество аккаунтов') : __('checkout.subscription_term')) ?></span> <?= h($pLabel) ?> (<?= h($daysLabel) ?>)</li>
            <li><span class="muted"><?= $isEn ? 'Price' : 'Цена' ?></span> <strong><?= h(number_format($price, 0, '', ' ')) ?> <?= $isEn ? 'RUB' : '₽' ?></strong></li>
        </ul>
    </div>

    <div class="checkout-layout">
        <div class="card card-hover stack">
            <div class="qr-wrap">
                <img src="<?= h($qrImg) ?>" width="275" height="275" alt="QR">
                <p class="muted qr-caption">
                    <?= h(__('checkout.qr_hint')) ?><br><code><?= h($payload) ?></code>
                </p>
                <a class="btn btn-ghost" href="<?= h($newQrUrl) ?>"><?= h(__('checkout.refresh_qr')) ?></a>
            </div>
            <?php require COMPONENTS_PATH . '/telegram-card.php'; ?>
        </div>
        <form class="card card-hover form-grid" method="post" action="<?= h(asset('actions/payment.php')) ?>">
            <input type="hidden" name="game_id" value="<?= (int) ($ord['game_id'] ?? 0) ?>">
            <input type="hidden" name="os_id" value="<?= (int) ($ord['os_id'] ?? 0) ?>">
            <input type="hidden" name="cheat_id" value="<?= (int) $ord['cheat_id'] ?>">
            <input type="hidden" name="key_plan_id" value="<?= (int) $ord['plan_id'] ?>">
            <input type="hidden" name="qr_payload" value="<?= h($payload) ?>">
            <h2 class="full form-section-title"><?= $isEn ? 'Contacts for delivery' : 'Контакты для выдачи' ?></h2>
            <label class="full">
                <span><?= h(__('checkout.telegram')) ?></span>
                <input type="text" name="telegram" maxlength="120" placeholder="@nickname">
            </label>
            <label class="full">
                <span><?= h(__('checkout.email')) ?></span>
                <input type="email" name="email" required autocomplete="email">
            </label>
            <label class="full">
                <span><?= h(__('checkout.phone')) ?></span>
                <input type="tel" name="phone" maxlength="64" autocomplete="tel">
            </label>
            <label class="full">
                <span><?= h(__('checkout.comment')) ?></span>
                <textarea name="comment" rows="4"></textarea>
            </label>
            <div class="full">
                <button class="btn btn-primary" type="submit"><?= h(__('checkout.paid')) ?></button>
            </div>
        </form>
    </div>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
