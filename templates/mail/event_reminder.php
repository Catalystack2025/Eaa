<?php

declare(strict_types=1);

$name = $data['name'] ?? 'Member';
$eventTitle = $data['event_title'] ?? 'Upcoming Event';
$eventDate = $data['event_date'] ?? 'To be announced';
$eventLocation = $data['event_location'] ?? 'EAA Venue';

$subject = "Reminder: {$eventTitle}";

$html = <<<HTML
<p>Hi {$name},</p>
<p>This is a reminder for the upcoming event you registered for:</p>
<p><strong>{$eventTitle}</strong><br>
{$eventDate}<br>
{$eventLocation}</p>
<p>We look forward to seeing you.</p>
<p>Regards,<br>EAA Events Desk</p>
HTML;

$text = <<<TEXT
Hi {$name},

This is a reminder for the upcoming event you registered for:
{$eventTitle}
{$eventDate}
{$eventLocation}

We look forward to seeing you.

Regards,
EAA Events Desk
TEXT;

return [
    'subject' => $subject,
    'html' => $html,
    'text' => $text,
];
