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
$eventTitle = trim((string) ($_POST['event_title'] ?? ''));
$eventDate = trim((string) ($_POST['event_date'] ?? ''));
$eventLocation = trim((string) ($_POST['event_location'] ?? ''));

if (!$email) {
    flash_set('error', 'Valid email is required to send reminders.');
    redirect('admin/manage_events.php');
}

try {
    send_template_mail($email, 'event_reminder', [
        'name' => $name !== '' ? $name : 'Member',
        'event_title' => $eventTitle !== '' ? $eventTitle : 'Upcoming Event',
        'event_date' => $eventDate !== '' ? $eventDate : 'To be announced',
        'event_location' => $eventLocation !== '' ? $eventLocation : 'EAA Venue',
    ]);
    flash_set('success', 'Event reminder email sent.');
} catch (RuntimeException $exception) {
    flash_set('error', $exception->getMessage());
}

redirect('admin/manage_events.php');
