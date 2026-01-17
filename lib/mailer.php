<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

function mail_config(): array
{
    $config = app_config()['mail'] ?? [];

    return array_merge([
        'smtp_host' => '',
        'smtp_port' => 587,
        'smtp_user' => '',
        'smtp_pass' => '',
        'smtp_encryption' => 'tls',
        'from_address' => '',
        'from_name' => 'EAA Portal',
    ], $config);
}

function render_mail_template(string $template, array $data): array
{
    $path = __DIR__ . '/../templates/mail/' . $template . '.php';

    if (!file_exists($path)) {
        throw new RuntimeException("Mail template not found: {$template}");
    }

    $payload = (static function (array $data, string $path): array {
        return require $path;
    })($data, $path);

    if (!isset($payload['subject'], $payload['html'])) {
        throw new RuntimeException("Mail template {$template} missing subject or html body.");
    }

    $payload['text'] = $payload['text'] ?? trim(strip_tags($payload['html']));

    return $payload;
}

function send_template_mail(string $to, string $template, array $data = []): bool
{
    $payload = render_mail_template($template, $data);

    return send_mail(
        $to,
        (string) $payload['subject'],
        (string) $payload['html'],
        (string) $payload['text']
    );
}

function send_mail(string $to, string $subject, string $htmlBody, ?string $textBody = null): bool
{
    $config = mail_config();
    $fromAddress = trim((string) ($config['from_address'] ?? ''));
    $fromName = trim((string) ($config['from_name'] ?? 'EAA Portal'));

    if ($fromAddress === '') {
        throw new RuntimeException('Mail from address is not configured.');
    }

    $textBody = $textBody ?? trim(strip_tags($htmlBody));
    $boundary = bin2hex(random_bytes(16));
    $headers = [
        'From: ' . format_mailbox($fromName, $fromAddress),
        'MIME-Version: 1.0',
        'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
    ];

    $message = build_multipart_message($boundary, $textBody, $htmlBody);

    return smtp_send_message(
        $config,
        $fromAddress,
        $to,
        $subject,
        implode("\r\n", $headers),
        $message
    );
}

function format_mailbox(string $name, string $address): string
{
    $safeName = trim(preg_replace('/[\r\n]+/', ' ', $name));
    $safeAddress = trim(preg_replace('/[\r\n]+/', '', $address));

    if ($safeName === '') {
        return $safeAddress;
    }

    return sprintf('"%s" <%s>', addslashes($safeName), $safeAddress);
}

function build_multipart_message(string $boundary, string $textBody, string $htmlBody): string
{
    $textBody = normalize_line_endings($textBody);
    $htmlBody = normalize_line_endings($htmlBody);

    $parts = [
        '--' . $boundary,
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: 8bit',
        '',
        $textBody,
        '--' . $boundary,
        'Content-Type: text/html; charset=UTF-8',
        'Content-Transfer-Encoding: 8bit',
        '',
        $htmlBody,
        '--' . $boundary . '--',
        '',
    ];

    return implode("\r\n", $parts);
}

function normalize_line_endings(string $content): string
{
    return preg_replace("/\r\n|\r|\n/", "\r\n", $content);
}

function smtp_send_message(array $config, string $from, string $to, string $subject, string $headers, string $message): bool
{
    $host = (string) ($config['smtp_host'] ?? '');
    $port = (int) ($config['smtp_port'] ?? 587);

    if ($host === '') {
        throw new RuntimeException('SMTP host is not configured.');
    }

    $socket = fsockopen($host, $port, $errno, $errstr, 15);
    if (!$socket) {
        throw new RuntimeException("SMTP connection failed: {$errstr} ({$errno})");
    }

    try {
        smtp_expect($socket, [220]);

        $hostname = gethostname() ?: 'localhost';
        smtp_command($socket, 'EHLO ' . $hostname, [250]);

        if (($config['smtp_encryption'] ?? '') === 'tls') {
            smtp_command($socket, 'STARTTLS', [220]);
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                throw new RuntimeException('Failed to enable TLS encryption for SMTP.');
            }
            smtp_command($socket, 'EHLO ' . $hostname, [250]);
        }

        $user = (string) ($config['smtp_user'] ?? '');
        $pass = (string) ($config['smtp_pass'] ?? '');
        if ($user !== '' || $pass !== '') {
            smtp_command($socket, 'AUTH LOGIN', [334]);
            smtp_command($socket, base64_encode($user), [334]);
            smtp_command($socket, base64_encode($pass), [235]);
        }

        smtp_command($socket, 'MAIL FROM:<' . $from . '>', [250]);
        smtp_command($socket, 'RCPT TO:<' . $to . '>', [250, 251]);
        smtp_command($socket, 'DATA', [354]);

        $payload = build_smtp_payload($to, $subject, $headers, $message);
        fwrite($socket, $payload);
        smtp_expect($socket, [250]);

        smtp_command($socket, 'QUIT', [221]);
    } finally {
        fclose($socket);
    }

    return true;
}

function build_smtp_payload(string $to, string $subject, string $headers, string $message): string
{
    $safeSubject = trim(preg_replace('/[\r\n]+/', ' ', $subject));
    $safeTo = trim(preg_replace('/[\r\n]+/', '', $to));
    $message = preg_replace('/^\./m', '..', $message);

    $payloadLines = [
        'To: ' . $safeTo,
        'Subject: ' . $safeSubject,
        $headers,
        '',
        $message,
    ];

    return implode("\r\n", $payloadLines) . "\r\n.\r\n";
}

function smtp_command($socket, string $command, array $expectedCodes): string
{
    fwrite($socket, $command . "\r\n");

    return smtp_expect($socket, $expectedCodes);
}

function smtp_expect($socket, array $expectedCodes): string
{
    $response = '';

    while (($line = fgets($socket, 515)) !== false) {
        $response .= $line;
        if (preg_match('/^\d{3} /', $line)) {
            break;
        }
    }

    $code = (int) substr($response, 0, 3);
    if (!in_array($code, $expectedCodes, true)) {
        throw new RuntimeException('Unexpected SMTP response: ' . trim($response));
    }

    return $response;
}
