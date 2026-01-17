<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

$today = new DateTimeImmutable('today');
$reminderDate = $today->modify('+1 day')->format('Y-m-d');

$stmt = db()->prepare(
    'SELECT e.id AS event_id,
            e.title,
            e.start_date,
            e.end_date,
            e.location,
            u.id AS user_id,
            u.full_name,
            u.email
     FROM events e
     INNER JOIN event_registrations er ON er.event_id = e.id
     INNER JOIN users u ON u.id = er.user_id
     LEFT JOIN event_reminders rem
        ON rem.event_id = e.id
       AND rem.user_id = u.id
       AND rem.reminder_date = :reminder_date
     WHERE e.is_public = 1
       AND e.start_date = :reminder_date
       AND rem.id IS NULL'
);
$stmt->execute(['reminder_date' => $reminderDate]);
$reminders = $stmt->fetchAll();

if (!$reminders) {
    echo "No reminders to send for {$reminderDate}." . PHP_EOL;
    exit(0);
}

$from = 'no-reply@eaa.local';
$sentCount = 0;

foreach ($reminders as $reminder) {
    $eventTitle = $reminder['title'];
    $recipient = $reminder['email'];
    $subject = "Reminder: {$eventTitle} is tomorrow";
    $message = sprintf(
        "Hello %s,\n\nThis is a reminder that \"%s\" is scheduled for %s at %s.\n\nThank you,\nEAA Team",
        $reminder['full_name'],
        $eventTitle,
        (new DateTimeImmutable($reminder['start_date']))->format('F j, Y'),
        $reminder['location'] ?: 'TBD'
    );
    $headers = "From: {$from}\r\nReply-To: {$from}\r\n";

    if (mail($recipient, $subject, $message, $headers)) {
        $insert = db()->prepare(
            'INSERT INTO event_reminders (event_id, user_id, reminder_date)
             VALUES (:event_id, :user_id, :reminder_date)'
        );
        $insert->execute([
            'event_id' => (int) $reminder['event_id'],
            'user_id' => (int) $reminder['user_id'],
            'reminder_date' => $reminderDate,
        ]);
        $sentCount++;
        echo "Sent reminder to {$recipient} for event {$eventTitle}." . PHP_EOL;
    } else {
        echo "Failed to send reminder to {$recipient} for event {$eventTitle}." . PHP_EOL;
    }
}

echo "Total reminders sent: {$sentCount}." . PHP_EOL;
