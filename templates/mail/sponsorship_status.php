<?php

declare(strict_types=1);

$name = $data['name'] ?? 'Partner';
$company = $data['company'] ?? 'Your Organization';
$status = $data['status'] ?? 'Under Review';
$nextSteps = $data['next_steps'] ?? 'We will be in touch with next steps shortly.';

$subject = 'Sponsorship status update from EAA';

$html = <<<HTML
<p>Hi {$name},</p>
<p>Thank you for your interest in sponsoring EAA initiatives.</p>
<p><strong>Organization:</strong> {$company}<br>
<strong>Status:</strong> {$status}</p>
<p>{$nextSteps}</p>
<p>Regards,<br>EAA Sponsorship Team</p>
HTML;

$text = <<<TEXT
Hi {$name},

Thank you for your interest in sponsoring EAA initiatives.
Organization: {$company}
Status: {$status}

{$nextSteps}

Regards,
EAA Sponsorship Team
TEXT;

return [
    'subject' => $subject,
    'html' => $html,
    'text' => $text,
];
