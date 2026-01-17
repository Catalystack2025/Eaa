<?php

declare(strict_types=1);

$name = $data['name'] ?? 'Member';
$resetLink = $data['reset_link'] ?? '#';
$expiresIn = $data['expires_in'] ?? '30 minutes';

$subject = 'Reset your EAA portal password';

$html = <<<HTML
<p>Hi {$name},</p>
<p>We received a request to reset your password. Use the link below to set a new password:</p>
<p><a href="{$resetLink}">Reset your password</a></p>
<p>This link expires in {$expiresIn}. If you did not request this, please ignore this email.</p>
<p>Regards,<br>EAA Support Desk</p>
HTML;

$text = <<<TEXT
Hi {$name},

We received a request to reset your password. Use the link below to set a new password:
{$resetLink}

This link expires in {$expiresIn}. If you did not request this, please ignore this email.

Regards,
EAA Support Desk
TEXT;

return [
    'subject' => $subject,
    'html' => $html,
    'text' => $text,
];
