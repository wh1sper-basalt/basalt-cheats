<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';
require dirname(__DIR__) . '/connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('feedback.php');
}

$name = trim((string) ($_POST['name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$telegram = trim((string) ($_POST['telegram'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));

if ($name === '' || $email === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect('feedback.php', ['err' => 1]);
}

$stmt = $mysqli->prepare(
    'INSERT INTO contact_messages (name, email, telegram, message) VALUES (?,?,?,?)'
);
if ($stmt === false) {
    redirect('feedback.php', ['err' => 1]);
}

$stmt->bind_param('ssss', $name, $email, $telegram, $message);
$stmt->execute();
$stmt->close();

redirect('answer.php', ['kind' => 'contact']);
