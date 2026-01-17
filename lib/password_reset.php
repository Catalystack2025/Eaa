<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

function create_password_reset(int $userId, int $ttlMinutes = 60): array
{
    $token = bin2hex(random_bytes(32));
    $tokenHash = hash('sha256', $token);
    $expiresAt = (new DateTimeImmutable("+{$ttlMinutes} minutes"))->format('Y-m-d H:i:s');

    $deleteStmt = db()->prepare('DELETE FROM password_resets WHERE user_id = :user_id');
    $deleteStmt->execute(['user_id' => $userId]);

    $insertStmt = db()->prepare(
        'INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)'
    );
    $insertStmt->execute([
        'user_id' => $userId,
        'token' => $tokenHash,
        'expires_at' => $expiresAt,
    ]);

    return [
        'token' => $token,
        'expires_at' => $expiresAt,
    ];
}

function find_valid_password_reset(string $token): ?array
{
    if ($token === '') {
        return null;
    }

    $tokenHash = hash('sha256', $token);

    $stmt = db()->prepare(
        'SELECT * FROM password_resets WHERE token = :token AND used_at IS NULL AND expires_at > NOW() LIMIT 1'
    );
    $stmt->execute(['token' => $tokenHash]);

    $reset = $stmt->fetch();

    return $reset ?: null;
}

function mark_password_reset_used(int $resetId): void
{
    $stmt = db()->prepare('UPDATE password_resets SET used_at = NOW() WHERE id = :id');
    $stmt->execute(['id' => $resetId]);
}
