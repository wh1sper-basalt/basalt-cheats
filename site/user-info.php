<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/connect.php';
require_login();

$user = current_user();
$uid = (int) $user['id'];

$hasAvatarPath = false;
$colRes = $mysqli->query("SHOW COLUMNS FROM users LIKE 'avatar_path'");
if ($colRes && $colRes->fetch_assoc()) {
    $hasAvatarPath = true;
}
$userSql = $hasAvatarPath
    ? 'SELECT id, email, nickname, avatar_path FROM users WHERE id = ? AND is_deleted = 0 LIMIT 1'
    : 'SELECT id, email, nickname, NULL AS avatar_path FROM users WHERE id = ? AND is_deleted = 0 LIMIT 1';
$stmt = $mysqli->prepare($userSql);
$stmt->bind_param('i', $uid);
$stmt->execute();
$dbUser = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$dbUser) {
    logout_user();
    redirect('index.php');
}

set_session_user($dbUser);

// Embedded dashboard data (licenses and actions)
$freezeLimitDays = 1;
$dashRows = [];
$unfreezeStmt = $mysqli->prepare(
    'UPDATE license_keys
     SET status = \'active\', frozen_until = NULL
     WHERE user_id = ? AND status = \'frozen_manual\' AND frozen_until IS NOT NULL AND frozen_until <= NOW()'
);
if ($unfreezeStmt) {
    $unfreezeStmt->bind_param('i', $uid);
    $unfreezeStmt->execute();
    $unfreezeStmt->close();
}

$syncSql = 'UPDATE license_keys lk
INNER JOIN purchase_orders po ON po.id = lk.order_id
INNER JOIN cheats c ON c.id = po.cheat_id
LEFT JOIN games g ON g.id = po.game_id
LEFT JOIN os o ON o.id = po.os_id
SET
lk.status = CASE
    WHEN (c.is_enabled = 0 OR (g.id IS NOT NULL AND g.is_enabled = 0) OR (o.id IS NOT NULL AND o.is_enabled = 0))
    THEN \'frozen_auto\'
    WHEN lk.status = \'frozen_auto\' THEN \'active\'
    ELSE lk.status
END,
lk.auto_frozen_reason = CASE
    WHEN c.is_enabled = 0 THEN COALESCE(c.maintenance_note, \'Cheat disabled\')
    WHEN g.id IS NOT NULL AND g.is_enabled = 0 THEN COALESCE(g.maintenance_note, \'Game disabled\')
    WHEN o.id IS NOT NULL AND o.is_enabled = 0 THEN COALESCE(o.maintenance_note, \'Utility disabled\')
    ELSE NULL
END
WHERE lk.user_id = ?';
$syncStmt = $mysqli->prepare($syncSql);
if ($syncStmt) {
    $syncStmt->bind_param('i', $uid);
    $syncStmt->execute();
    $syncStmt->close();
}

$dashSql = 'SELECT lk.*, po.id AS order_id, po.order_status, po.payment_status,
            c.title_ru, c.title_en, kp.label_ru, kp.label_en
            FROM license_keys lk
            INNER JOIN purchase_orders po ON po.id = lk.order_id
            INNER JOIN cheats c ON c.id = po.cheat_id
            INNER JOIN key_plans kp ON kp.id = po.key_plan_id
            WHERE lk.user_id = ?
            ORDER BY lk.id DESC';
$dashStmt = $mysqli->prepare($dashSql);
if ($dashStmt) {
    $dashStmt->bind_param('i', $uid);
    $dashStmt->execute();
    $dres = $dashStmt->get_result();
    while ($d = $dres->fetch_assoc()) {
        $dashRows[] = $d;
    }
    $dashStmt->close();
}

$isEn = current_lang() === 'en';
$pageTitle = __('account.title');
require COMPONENTS_PATH . '/header.php';
$ok = flash_get('account_ok') ?? flash_get('dash_ok');
$err = flash_get('account_err') ?? flash_get('dash_err');

$formatExpiresAt = static function (?string $expiresAt, string $status) use ($isEn): string {
    if ($expiresAt === null || $expiresAt === '') {
        if (in_array($status, ['active', 'frozen_manual', 'frozen_auto'], true)) {
            return __('account.expires_lifetime');
        }

        return '—';
    }
    $ts = strtotime($expiresAt);
    if ($ts === false) {
        return $expiresAt;
    }

    return date($isEn ? 'M j, Y H:i' : 'd.m.Y H:i', $ts);
};
$breadcrumbs = [
    ['label' => __('auth.account'), 'href' => null],
];
?>
<div class="container account-page">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= h(__('auth.account')) ?></p>
        <h1 class="ws-page-title"><?= h(__('account.heading')) ?></h1>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>
    <section class="card card-hover stack">
        <?php if ($ok !== null) { ?><p class="form-alert is-ok"><?= h($ok) ?></p><?php } ?>
        <?php if ($err !== null) { ?><p class="form-alert is-error"><?= h($err) ?></p><?php } ?>
        <form class="form-grid" method="post" action="<?= h(asset('actions/account-save.php')) ?>" enctype="multipart/form-data">
            <div class="full account-layout">
                <article class="card avatar-card">
                    <h3><?= h(__('account.avatar')) ?></h3>
                    <div class="avatar-preview" id="avatar-preview">
                        <?php if (!empty($dbUser['avatar_path'])) { ?>
                            <img id="avatar-preview-img" src="<?= h(asset((string) $dbUser['avatar_path'])) ?>" alt="avatar">
                        <?php } else { ?>
                            <img id="avatar-preview-img" src="<?= h(asset('assets/img/components/account-icon.svg')) ?>" alt="avatar">
                        <?php } ?>
                    </div>
                    <label>
                        <span><?= h(__('account.update_avatar')) ?></span>
                        <input id="avatar-input" type="file" name="avatar" accept="image/png,image/jpeg,image/webp,image/gif">
                    </label>
                    <p class="muted" id="avatar-preview-hint" style="margin:8px 0 0;"
                       data-msg-default="<?= h(__('account.avatar_preview_hint')) ?>"
                       data-msg-updated="<?= h(__('account.avatar_preview_updated')) ?>"
                       data-msg-too-large="<?= h(__('account.avatar_too_large')) ?>"
                       data-msg-not-image="<?= h(__('account.avatar_not_image')) ?>">
                        <?= h(__('account.avatar_preview_hint')) ?>
                    </p>
                </article>
                <article class="card profile-card">
                    <div class="form-grid">
                        <label class="full">
                            <span><?= h(__('auth.nickname')) ?></span>
                            <input type="text" name="nickname" maxlength="120" value="<?= h((string) $dbUser['nickname']) ?>">
                        </label>
                        <label class="full">
                            <span><?= h(__('checkout.email')) ?></span>
                            <input type="email" name="email" required value="<?= h((string) $dbUser['email']) ?>">
                        </label>
                        <label class="full">
                            <span><?= h(__('account.new_password')) ?></span>
                            <input type="password" name="password" autocomplete="new-password" minlength="6">
                        </label>
                        <div class="full account-cta-row" aria-label="<?= h($isEn ? 'Account actions' : 'Действия с аккаунтом') ?>">
                            <button class="btn btn-primary orb-btn" type="submit"><?= h(__('common.save')) ?></button>
                            <button class="btn btn-ghost orb-btn" type="submit" formaction="<?= h(asset('actions/auth-logout.php')) ?>" formmethod="post"><?= h(__('auth.logout')) ?></button>
                            <button class="btn btn-danger orb-btn" type="submit" formaction="<?= h(asset('actions/account-delete.php')) ?>" formmethod="post" onclick="return confirm('<?= h($isEn ? 'Delete account?' : 'Удалить аккаунт?') ?>')"><?= h(__('auth.delete_account')) ?></button>
                        </div>
                    </div>
                </article>
            </div>
        </form>
    </section>

    <section class="card card-hover stack" id="licenses" style="margin-top:18px;">
        <header class="ws-page-hero" style="padding:0;">
            <p class="ws-hero-badge"><?= h($isEn ? 'Licenses' : 'Ключи') ?></p>
            <h2 class="ws-page-title" style="font-size:1.4rem;"><?= h($isEn ? 'Your keys and activation' : 'Ваши ключи и активация') ?></h2>
        </header>
        <section class="dash-grid">
            <?php foreach ($dashRows as $r) {
                $name = $isEn ? $r['title_en'] : $r['title_ru'];
                $plan = $isEn ? $r['label_en'] : $r['label_ru'];
                $status = (string) $r['status'];
                ?>
                <article class="card card-hover dash-card">
                    <h3><?= h($name) ?></h3>
                    <p class="muted"><?= h($plan) ?></p>
                    <p><strong><?= h($isEn ? 'Key' : 'Ключ') ?>:</strong> <code><?= h((string) $r['key_value']) ?></code></p>
                    <p><strong><?= h($isEn ? 'Status' : 'Статус') ?>:</strong> <?= h($status) ?></p>
                    <p><strong><?= h($isEn ? 'Expires' : 'Действует до') ?>:</strong> <?= h($formatExpiresAt(isset($r['expires_at']) ? (string) $r['expires_at'] : null, $status)) ?></p>
                    <?php if (!empty($r['auto_frozen_reason'])) { ?>
                        <p class="muted"><?= h((string) $r['auto_frozen_reason']) ?></p>
                    <?php } ?>
                    <div class="dash-actions">
                        <form method="post" action="<?= h(asset('actions/license-action.php')) ?>">
                            <input type="hidden" name="license_id" value="<?= (int) $r['id'] ?>">
                            <input type="hidden" name="action" value="activate">
                            <button class="btn btn-ghost" type="submit"><?= h($isEn ? 'Activate' : 'Активировать') ?></button>
                        </form>
                        <form method="post" action="<?= h(asset('actions/license-action.php')) ?>">
                            <input type="hidden" name="license_id" value="<?= (int) $r['id'] ?>">
                            <input type="hidden" name="action" value="freeze">
                            <input type="hidden" name="limit_days" value="<?= (int) $freezeLimitDays ?>">
                            <button class="btn btn-ghost" type="submit" <?= ((int) $r['freeze_used'] === 1 || $status === 'frozen_auto') ? 'disabled' : '' ?>><?= h($isEn ? 'Freeze' : 'Заморозить') ?></button>
                        </form>
                    </div>
                </article>
            <?php } ?>
            <?php if ($dashRows === []) { ?>
                <article class="card card-hover dash-card">
                    <h3><?= h($isEn ? 'No keys yet' : 'Ключей пока нет') ?></h3>
                    <p class="muted"><?= h($isEn ? 'Buy a plan to see licenses here.' : 'Купите тариф, и ключи появятся здесь.') ?></p>
                    <p><a class="btn btn-ghost" href="<?= h(route_games()) ?>"><?= h($isEn ? 'Open catalog' : 'Открыть каталог') ?></a></p>
                </article>
            <?php } ?>
        </section>
    </section>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
