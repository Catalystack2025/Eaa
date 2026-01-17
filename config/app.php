<?php

declare(strict_types=1);

return [
    'name' => 'EAA Portal',
    'base_url' => getenv('APP_URL') ?: '',
    'assets_url' => getenv('ASSETS_URL') ?: '',
    'mail' => [
        'smtp_host' => getenv('SMTP_HOST') ?: '',
        'smtp_port' => (int) (getenv('SMTP_PORT') ?: 587),
        'smtp_user' => getenv('SMTP_USER') ?: '',
        'smtp_pass' => getenv('SMTP_PASS') ?: '',
        'smtp_encryption' => getenv('SMTP_ENCRYPTION') ?: 'tls',
        'from_address' => getenv('MAIL_FROM_ADDRESS') ?: '',
        'from_name' => getenv('MAIL_FROM_NAME') ?: 'EAA Portal',
    ],
];
