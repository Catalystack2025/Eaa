<?php

declare(strict_types=1);

$name = $data['name'] ?? 'Member';
$membershipId = $data['membership_id'] ?? 'Pending ID';

$subject = 'Your EAA membership is now active';

$html = <<<HTML
<p>Hi {$name},</p>
<p>Your membership has been approved and activated.</p>
<p><strong>Membership ID:</strong> {$membershipId}</p>
<p>You can now access member resources, event registrations, and council updates through the portal.</p>
<p>Welcome aboard,<br>EAA Council</p>
HTML;

$text = <<<TEXT
Hi {$name},

Your membership has been approved and activated.
Membership ID: {$membershipId}
You can now access member resources, event registrations, and council updates through the portal.

Welcome aboard,
EAA Council
TEXT;

return [
    'subject' => $subject,
    'html' => $html,
    'text' => $text,
];
