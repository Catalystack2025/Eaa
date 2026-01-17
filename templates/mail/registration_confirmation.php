<?php

declare(strict_types=1);

$name = $data['name'] ?? 'Member';
$role = $data['role'] ?? 'Member';

$subject = 'Registration received for EAA Portal';

$html = <<<HTML
<p>Hi {$name},</p>
<p>Thank you for registering as a {$role} with the Erode Architect Association portal.</p>
<p>We have received your details and the council team is reviewing your submission. We will notify you once your membership is activated.</p>
<p>Regards,<br>EAA Council</p>
HTML;

$text = <<<TEXT
Hi {$name},

Thank you for registering as a {$role} with the Erode Architect Association portal.
We have received your details and the council team is reviewing your submission. We will notify you once your membership is activated.

Regards,
EAA Council
TEXT;

return [
    'subject' => $subject,
    'html' => $html,
    'text' => $text,
];
