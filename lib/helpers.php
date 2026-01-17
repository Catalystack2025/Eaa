<?php

declare(strict_types=1);

function start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function app_config(): array
{
    static $config = null;

    if ($config === null) {
        $config = require __DIR__ . '/../config/app.php';
    }

    return $config;
}

function url(string $path = ''): string
{
    $base = rtrim(app_config()['base_url'] ?? '', '/');
    $path = '/' . ltrim($path, '/');

    return $base . $path;
}

function asset(string $path): string
{
    $assetsBase = rtrim(app_config()['assets_url'] ?? '', '/');
    $path = '/' . ltrim($path, '/');

    if ($assetsBase !== '') {
        return $assetsBase . $path;
    }

    return url($path);
}

function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

function flash_set(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

function flash_get(string $key): ?string
{
    if (empty($_SESSION['flash'][$key])) {
        return null;
    }

    $message = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);

    return $message;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_verify(?string $token): bool
{
    return isset($_SESSION['csrf_token']) && is_string($token)
        && hash_equals($_SESSION['csrf_token'], $token);
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

function can_view_vendor_contact(?string $viewerRole): bool
{
    return in_array($viewerRole ?? '', ['member', 'admin'], true);
}
