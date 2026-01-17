<?php

declare(strict_types=1);

require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/mailer.php';

start_session();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Method not allowed.']);
    exit;
}

$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$name = trim((string) ($_POST['name'] ?? ''));
$role = trim((string) ($_POST['role'] ?? 'Member'));

if (!$email) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'message' => 'Valid email is required.']);
    exit;
}

try {
    send_template_mail($email, 'registration_confirmation', [
        'name' => $name !== '' ? $name : 'Member',
        'role' => $role !== '' ? $role : 'Member',
    ]);

    echo json_encode(['ok' => true]);
} catch (RuntimeException $exception) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => $exception->getMessage()]);
}
