<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';
require dirname(__DIR__) . '/connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('games.php');
}

$gameId = (int) ($_POST['game_id'] ?? 0);
$osId = (int) ($_POST['os_id'] ?? 0);
$cheatId = (int) ($_POST['cheat_id'] ?? 0);
$keyPlanId = (int) ($_POST['key_plan_id'] ?? 0);
$email = trim((string) ($_POST['email'] ?? ''));
$telegram = trim((string) ($_POST['telegram'] ?? ''));
$phone = trim((string) ($_POST['phone'] ?? ''));
$comment = trim((string) ($_POST['comment'] ?? ''));
$qrPayload = trim((string) ($_POST['qr_payload'] ?? ''));
$currentUser = current_user();
$userId = $currentUser ? (int) $currentUser['id'] : 0;

if ((($gameId < 1 && $osId < 1) || ($gameId > 0 && $osId > 0)) || $cheatId < 1 || $keyPlanId < 1 || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $qrPayload === '') {
    redirect('games.php', ['err' => 1]);
}

$chk = $mysqli->prepare(
    'SELECT 1 FROM key_plans kp
     INNER JOIN cheats c ON c.id = kp.cheat_id
     WHERE kp.id = ? AND c.id = ?
       AND ((? > 0 AND c.game_id = ? AND c.os_id IS NULL) OR (? > 0 AND c.os_id = ? AND c.game_id IS NULL))
     LIMIT 1'
);
$chk->bind_param('iiiiii', $keyPlanId, $cheatId, $gameId, $gameId, $osId, $osId);
$chk->execute();
$ok = $chk->get_result()->fetch_row();
$chk->close();
if (!$ok) {
    redirect('games.php', ['err' => 1]);
}

$stmt = $mysqli->prepare(
    'INSERT INTO payment_requests (game_id, os_id, cheat_id, key_plan_id, email, telegram, phone, comment, qr_payload, paid_ack)
     VALUES (NULLIF(?,0),NULLIF(?,0),?,?,?,?,?,?,?,?)'
);
if ($stmt === false) {
    redirect('games.php', ['err' => 1]);
}

$paidAck = 1;
$stmt->bind_param(
    'iiiisssssi',
    $gameId,
    $osId,
    $cheatId,
    $keyPlanId,
    $email,
    $telegram,
    $phone,
    $comment,
    $qrPayload,
    $paidAck
);
$stmt->execute();
$stmt->close();

$ordStmt = $mysqli->prepare(
    'INSERT INTO purchase_orders
    (user_id, game_id, os_id, cheat_id, key_plan_id, email, telegram, phone, comment, qr_payload, order_status, payment_status, paid_at)
    VALUES (NULLIF(?,0), NULLIF(?,0), NULLIF(?,0), ?, ?, ?, ?, ?, ?, ?, \'paid_waiting_review\', \'reported_paid\', NOW())'
);
if ($ordStmt !== false) {
    $ordStmt->bind_param(
        'iiiissssss',
        $userId,
        $gameId,
        $osId,
        $cheatId,
        $keyPlanId,
        $email,
        $telegram,
        $phone,
        $comment,
        $qrPayload
    );
    $ordStmt->execute();
    $orderId = (int) $ordStmt->insert_id;
    $ordStmt->close();

    if ($orderId > 0 && $userId > 0) {
        $genKey = strtoupper(bin2hex(random_bytes(12)));
        $keyVal = substr($genKey, 0, 8) . '-' . substr($genKey, 8, 8) . '-' . substr($genKey, 16);
        $kStmt = $mysqli->prepare(
            'INSERT INTO license_keys (order_id, user_id, key_value, status)
            VALUES (?, ?, ?, \'pending\')'
        );
        if ($kStmt !== false) {
            $kStmt->bind_param('iis', $orderId, $userId, $keyVal);
            $kStmt->execute();
            $kStmt->close();
        }
    }
}

redirect('answer.php', ['kind' => 'payment']);
