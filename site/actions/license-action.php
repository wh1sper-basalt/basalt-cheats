<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';
require dirname(__DIR__) . '/connect.php';
require_login();

function license_wants_json(): bool
{
    $accept = (string) ($_SERVER['HTTP_ACCEPT'] ?? '');
    if (str_contains($accept, 'application/json')) {
        return true;
    }
    return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function license_json_response(bool $ok, string $message, ?array $license = null, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json; charset=UTF-8');
    $payload = ['ok' => $ok, 'message' => $message];
    if ($license !== null) {
        $payload['license'] = $license;
    }
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function license_format_expires(?string $expiresAt, string $status): string
{
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
    $isEn = current_lang() === 'en';

    return date($isEn ? 'M j, Y H:i' : 'd.m.Y H:i', $ts);
}

function license_row_payload(array $row): array
{
    $status = (string) $row['status'];

    return [
        'id' => (int) $row['id'],
        'status' => $status,
        'status_label' => license_status_label($status),
        'expires_at' => isset($row['expires_at']) ? (string) $row['expires_at'] : null,
        'expires_display' => license_format_expires(isset($row['expires_at']) ? (string) $row['expires_at'] : null, $status),
        'freeze_used' => (int) ($row['freeze_used'] ?? 0),
        'auto_frozen_reason' => (string) ($row['auto_frozen_reason'] ?? ''),
        'freeze_disabled' => (int) ($row['freeze_used'] ?? 0) === 1 || $status === 'frozen_auto',
    ];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if (license_wants_json()) {
        license_json_response(false, __('dash.license_not_found'), null, 405);
    }
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
    if (license_wants_json()) {
        license_json_response(false, __('dash.license_not_found'), null, 404);
    }
    set_flash('dash_err', __('dash.license_not_found'));
    redirect('user-info.php');
}

$isNfaFlow = (int) ($license['order_os_id'] ?? 0) > 0 || (int) ($license['cheat_os_id'] ?? 0) === 2;
$message = '';
$ok = true;

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
    $message = __('dash.activated');
} elseif ($action === 'freeze') {
    if ((int) $license['freeze_used'] === 1) {
        $ok = false;
        $message = __('dash.freeze_already_used');
    } elseif ((string) $license['status'] === 'frozen_auto') {
        $ok = false;
        $message = __('dash.freeze_auto_block');
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
        $message = __('dash.frozen_ok');
    }
} else {
    $ok = false;
    $message = __('dash.license_not_found');
}

$refStmt = $mysqli->prepare('SELECT * FROM license_keys WHERE id = ? AND user_id = ? LIMIT 1');
$refStmt->bind_param('ii', $licenseId, $uid);
$refStmt->execute();
$fresh = $refStmt->get_result()->fetch_assoc();
$refStmt->close();

if (license_wants_json()) {
    if (!$ok) {
        license_json_response(false, $message, null, 400);
    }
    license_json_response(true, $message, $fresh ? license_row_payload($fresh) : null);
}

if (!$ok) {
    set_flash('dash_err', $message);
} else {
    set_flash('dash_ok', $message);
}
redirect('user-info.php#licenses');
