<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

function current_user(): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }

    $stmt = db()->prepare('SELECT id, name, email, role FROM users WHERE id = :id');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch();

    return $user ?: null;
}

function login_user(array $user): void
{
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['role'] = $user['role'];
}

function logout_user(): void
{
    $_SESSION = [];
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
}

function require_login(): void
{
    if (empty($_SESSION['user_id'])) {
        header('Location: /auth/login.php');
        exit;
    }
}

function require_admin(): void
{
    require_login();

    if (($_SESSION['role'] ?? '') !== 'admin') {
        header('Location: /account.php');
        exit;
    }
}

function find_user_by_email(string $email): ?array
{
    $stmt = db()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    return $user ?: null;
}

function audit_login(int $userId, string $action): void
{
    $stmt = db()->prepare(
        'INSERT INTO sessions_audit (user_id, action, ip_address, user_agent)
         VALUES (:user_id, :action, :ip_address, :user_agent)'
    );
    $stmt->execute([
        'user_id' => $userId,
        'action' => $action,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? 'unknown', 0, 255),
    ]);
}
