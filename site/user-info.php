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

$dashSql = 'SELECT lk.*, po.id AS order_id, po.order_status, po.payment_status, po.game_id, po.os_id,
            c.title_ru, c.title_en, c.slug AS cheat_slug, c.image_path,
            g.slug AS game_slug, o.slug AS os_slug,
            kp.label_ru, kp.label_en
            FROM license_keys lk
            INNER JOIN purchase_orders po ON po.id = lk.order_id
            INNER JOIN cheats c ON c.id = po.cheat_id
            INNER JOIN key_plans kp ON kp.id = po.key_plan_id
            LEFT JOIN games g ON g.id = po.game_id
            LEFT JOIN os o ON o.id = po.os_id
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
$defaultAvatar = asset('assets/svg/account-icon.svg');
?>
<div class="container account-page">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= h(__('auth.account')) ?></p>
        <h1 class="ws-page-title"><?= h(__('account.heading')) ?></h1>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>

    <section class="account-settings-grid" id="account-settings">
        <article class="card account-section" id="account-appearance">
            <h3><?= h(__('account.section_appearance')) ?></h3>
            <p class="account-section-lead"><?= h(__('account.section_appearance_lead')) ?></p>
            <div class="account-appearance-stack">
                <div class="avatar-preview avatar-preview--account" id="avatar-preview">
                    <?php if (!empty($dbUser['avatar_path'])) { ?>
                        <img id="avatar-preview-img" src="<?= h(asset((string) $dbUser['avatar_path'])) ?>" alt="avatar">
                    <?php } else { ?>
                        <img id="avatar-preview-img" src="<?= h($defaultAvatar) ?>" alt="avatar">
                    <?php } ?>
                </div>
                <form method="post" action="<?= h(asset('actions/account-save.php')) ?>" enctype="multipart/form-data" id="avatar-upload-form" class="account-appearance-actions">
                    <input type="hidden" name="section" value="appearance">
                    <input id="avatar-input" type="file" name="avatar" accept="image/png,image/jpeg,image/webp,image/gif" hidden>
                    <button class="btn btn-primary orb-btn" type="submit"><?= h(__('account.update_avatar')) ?></button>
                    <button class="btn btn-ghost orb-btn" type="button" id="avatar-change-open"><?= h(__('account.change_avatar')) ?></button>
                    <button class="btn btn-ghost orb-btn" type="button" id="avatar-crop-open"><?= h(__('account.crop')) ?></button>
                    <button class="btn btn-ghost orb-btn" type="button" id="avatar-remove-open"><?= h(__('account.remove_avatar')) ?></button>
                </form>
                <p class="muted account-appearance-hint" id="avatar-preview-hint"
                   data-msg-default="<?= h(__('account.avatar_preview_hint')) ?>"
                   data-msg-updated="<?= h(__('account.avatar_preview_updated')) ?>"
                   data-msg-too-large="<?= h(__('account.avatar_too_large')) ?>"
                   data-msg-not-image="<?= h(__('account.avatar_not_image')) ?>">
                    <?= h(__('account.avatar_preview_hint')) ?>
                </p>
            </div>
        </article>

        <article class="card account-section" id="account-security">
            <h3><?= h(__('account.section_security')) ?></h3>
            <p class="account-section-lead"><?= h(__('account.section_security_lead')) ?></p>
            <form class="form-grid" method="post" action="<?= h(asset('actions/account-save.php')) ?>">
                <input type="hidden" name="section" value="security">
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
                    <input type="password" name="password" autocomplete="new-password" minlength="6" placeholder="<?= h(__('account.password_optional')) ?>">
                </label>
                <label class="full">
                    <span><?= h(__('account.password_confirm')) ?></span>
                    <input type="password" name="password_confirm" autocomplete="new-password" minlength="6" placeholder="<?= h(__('account.password_optional')) ?>">
                </label>
                <div class="full">
                    <button class="btn btn-primary orb-btn" type="submit"><?= h(__('account.save_security')) ?></button>
                </div>
            </form>
        </article>

        <article class="card account-section" id="account-actions">
            <h3><?= h(__('account.section_actions')) ?></h3>
            <p class="account-section-lead"><?= h(__('account.section_actions_lead')) ?></p>
            <div class="account-actions-row">
                <form method="post" action="<?= h(asset('actions/auth-logout.php')) ?>">
                    <button class="btn btn-ghost orb-btn" type="submit"><?= h(__('auth.logout')) ?></button>
                </form>
                <button class="btn btn-danger orb-btn" type="button" id="account-delete-open"><?= h(__('auth.delete_account')) ?></button>
            </div>
        </article>
    </section>

    <header class="ws-page-hero account-licenses-hero">
        <p class="ws-hero-badge"><?= h(__('account.licenses_badge')) ?></p>
        <h2 class="ws-page-title"><?= h(__('account.licenses_heading')) ?></h2>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>

    <section class="dash-grid" id="licenses">
        <?php foreach ($dashRows as $r) {
            $name = $isEn ? $r['title_en'] : $r['title_ru'];
            $plan = $isEn ? $r['label_en'] : $r['label_ru'];
            $status = (string) $r['status'];
            $cheatSlug = (string) ($r['cheat_slug'] ?? '');
            $coverPath = trim((string) ($r['image_path'] ?? ''));
            if ($coverPath === '' && $cheatSlug !== '') {
                $coverPath = cheat_cover_path($cheatSlug);
            }
            $coverUrl = $coverPath !== '' ? asset($coverPath) : '';
            $gameId = (int) ($r['game_id'] ?? 0);
            $cheatUrl = $cheatSlug !== ''
                ? ($gameId > 0
                    ? url_to('cheat.php', ['game' => (string) $r['game_slug'], 'cheat' => $cheatSlug])
                    : url_to('cheat.php', ['os' => (string) $r['os_slug'], 'cheat' => $cheatSlug]))
                : route_games();
            ?>
            <article class="card card-hover dash-card dash-card--cover" data-license-card data-license-id="<?= (int) $r['id'] ?>"<?= $coverUrl !== '' ? ' style="--dash-cover: url(' . json_encode($coverUrl, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ')"' : '' ?>>
                <div class="dash-card-body">
                    <h3><?= h($name) ?></h3>
                    <p class="muted"><?= h($plan) ?></p>
                    <p><strong><?= h($isEn ? 'Key' : 'Ключ') ?>:</strong> <code><?= h((string) $r['key_value']) ?></code></p>
                    <p><strong><?= h($isEn ? 'Status' : 'Статус') ?>:</strong> <span data-license-status-label><?= h(license_status_label($status)) ?></span></p>
                    <p><strong><?= h($isEn ? 'Expires' : 'Действует до') ?>:</strong> <span data-license-expires><?= h($formatExpiresAt(isset($r['expires_at']) ? (string) $r['expires_at'] : null, $status)) ?></span></p>
                    <p class="muted" data-license-reason<?= empty($r['auto_frozen_reason']) ? ' hidden' : '' ?>><?= h((string) ($r['auto_frozen_reason'] ?? '')) ?></p>
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
                        <a class="btn btn-ghost" href="<?= h($cheatUrl) ?>"><?= h(__('account.license_details')) ?></a>
                    </div>
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
</div>

<div class="avatar-crop-modal" id="avatar-crop-modal" hidden>
    <div class="avatar-crop-backdrop" data-crop-close></div>
    <div class="avatar-crop-dialog" role="dialog" aria-modal="true" aria-labelledby="avatar-crop-title">
        <h3 id="avatar-crop-title"><?= h(__('account.crop')) ?></h3>
        <div class="avatar-crop-stage" id="avatar-crop-stage">
            <div class="avatar-crop-viewport" id="avatar-crop-viewport">
                <img id="avatar-crop-source" alt="" draggable="false">
            </div>
            <div class="avatar-crop-frame" aria-hidden="true">
                <span class="crop-corner crop-corner-tl"></span>
                <span class="crop-corner crop-corner-tr"></span>
                <span class="crop-corner crop-corner-bl"></span>
                <span class="crop-corner crop-corner-br"></span>
            </div>
        </div>
        <label class="avatar-crop-zoom-label">
            <span class="muted"><?= h(__('account.crop_zoom')) ?></span>
            <input type="range" id="avatar-crop-zoom" min="1" max="3" step="0.05" value="1">
        </label>
        <div class="avatar-crop-actions avatar-crop-actions--center">
            <button type="button" class="btn btn-ghost" data-crop-close><?= h(__('account.crop_cancel')) ?></button>
            <button type="button" class="btn btn-primary" id="avatar-crop-done"><?= h(__('account.crop_done')) ?></button>
        </div>
    </div>
</div>

<div class="account-delete-modal" id="account-delete-modal" hidden>
    <div class="avatar-crop-backdrop" data-delete-close></div>
    <div class="avatar-crop-dialog card" role="alertdialog" aria-modal="true">
        <h3><?= h(__('account.delete_confirm_title')) ?></h3>
        <p class="muted"><?= h(__('account.delete_confirm_lead')) ?></p>
        <form method="post" action="<?= h(asset('actions/account-delete.php')) ?>" class="avatar-crop-actions avatar-crop-actions--center">
            <button type="button" class="btn btn-ghost" data-delete-close><?= h(__('common.close')) ?></button>
            <button type="submit" class="btn btn-danger"><?= h(__('auth.delete_account')) ?></button>
        </form>
    </div>
</div>

<div class="account-delete-modal" id="avatar-remove-modal" hidden>
    <div class="avatar-crop-backdrop" data-avatar-remove-close></div>
    <div class="avatar-crop-dialog card" role="alertdialog" aria-modal="true">
        <h3><?= h(__('account.remove_avatar_title')) ?></h3>
        <p class="muted"><?= h(__('account.remove_avatar_confirm')) ?></p>
        <form method="post" action="<?= h(asset('actions/account-save.php')) ?>" class="avatar-crop-actions avatar-crop-actions--center" id="avatar-remove-form">
            <input type="hidden" name="remove_avatar" value="1">
            <button type="button" class="btn btn-ghost" data-avatar-remove-close><?= h(__('account.crop_cancel')) ?></button>
            <button type="submit" class="btn btn-danger"><?= h(__('account.remove_avatar')) ?></button>
        </form>
    </div>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
