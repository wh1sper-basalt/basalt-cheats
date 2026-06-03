<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';
require dirname(__DIR__) . '/connect.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('user-info.php');
}

$user = current_user();
$uid = (int) $user['id'];
$licenseId = (int) ($_POST['license_id'] ?? 0);
$action = (string) ($_POST['action'] ?? '');
$limitDays = (int) ($_POST['limit_days'] ?? 1);
if ($limitDays < 1) {
    $limitDays = 1;
}

$stmt = $mysqli->prepare(
    'SELECT lk.*, kp.duration_days, po.os_id AS order_os_id, c.os_id AS cheat_os_id
     FROM license_keys lk
     INNER JOIN purchase_orders po ON po.id = lk.order_id
     INNER JOIN key_plans kp ON kp.id = po.key_plan_id
     INNER JOIN cheats c ON c.id = po.cheat_id
     WHERE lk.id = ? AND lk.user_id = ?
     LIMIT 1'
);
$stmt->bind_param('ii', $licenseId, $uid);
$stmt->execute();
$license = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$license) {
    set_flash('dash_err', __('dash.license_not_found'));
    redirect('user-info.php');
}

$isNfaFlow = (int) ($license['order_os_id'] ?? 0) > 0 || (int) ($license['cheat_os_id'] ?? 0) === 2;

if ($action === 'activate') {
    if ($isNfaFlow) {
        $upd = $mysqli->prepare(
            'UPDATE license_keys
             SET status = \'active\',
                 activated_at = COALESCE(activated_at, NOW())
             WHERE id = ? AND user_id = ? LIMIT 1'
        );
        $upd->bind_param('ii', $licenseId, $uid);
    } else {
        $durationDays = (int) ($license['duration_days'] ?? 30);
        if ($durationDays === 0) {
            $upd = $mysqli->prepare(
                'UPDATE license_keys
                 SET status = \'active\',
                     activated_at = COALESCE(activated_at, NOW()),
                     expires_at = CASE
                         WHEN expires_at IS NOT NULL AND expires_at >= NOW() THEN expires_at
                         ELSE NULL
                     END
                 WHERE id = ? AND user_id = ? LIMIT 1'
            );
            $upd->bind_param('ii', $licenseId, $uid);
        } else {
            $upd = $mysqli->prepare(
                'UPDATE license_keys
                 SET status = \'active\',
                     activated_at = COALESCE(activated_at, NOW()),
                     expires_at = CASE
                         WHEN expires_at IS NOT NULL AND expires_at >= NOW() THEN expires_at
                         ELSE DATE_ADD(NOW(), INTERVAL ? DAY)
                     END
                 WHERE id = ? AND user_id = ? LIMIT 1'
            );
            $upd->bind_param('iii', $durationDays, $licenseId, $uid);
        }
    }
    $upd->execute();
    $upd->close();
    set_flash('dash_ok', __('dash.activated'));
} elseif ($action === 'freeze') {
    if ((int) $license['freeze_used'] === 1) {
        set_flash('dash_err', __('dash.freeze_already_used'));
    } elseif ((string) $license['status'] === 'frozen_auto') {
        set_flash('dash_err', __('dash.freeze_auto_block'));
    } else {
        $freezeMinutesStored = $limitDays * 24 * 60;
        $upd = $mysqli->prepare(
            'UPDATE license_keys
             SET status = \'frozen_manual\',
                 frozen_until = DATE_ADD(NOW(), INTERVAL ? DAY),
                 freeze_used = 1,
                 freeze_minutes_used = ?
             WHERE id = ? AND user_id = ? LIMIT 1'
        );
        $upd->bind_param('iiii', $limitDays, $freezeMinutesStored, $licenseId, $uid);
        $upd->execute();
        $upd->close();
        set_flash('dash_ok', __('dash.frozen_ok'));
    }
}

redirect('user-info.php');
