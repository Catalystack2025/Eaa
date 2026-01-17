<?php

declare(strict_types=1);

require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/mailer.php';

start_session();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method not allowed.';
    exit;
}

$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$name = trim((string) ($_POST['name'] ?? ''));
$membershipId = trim((string) ($_POST['membership_id'] ?? ''));

if (!$email) {
    flash_set('error', 'Valid email is required to send activation.');
    redirect('admin/dashboard.php');
}

try {
    send_template_mail($email, 'membership_activation', [
        'name' => $name !== '' ? $name : 'Member',
        'membership_id' => $membershipId !== '' ? $membershipId : 'Pending ID',
    ]);
    flash_set('success', 'Activation email sent successfully.');
} catch (RuntimeException $exception) {
    flash_set('error', $exception->getMessage());
}

redirect('admin/dashboard.php');
