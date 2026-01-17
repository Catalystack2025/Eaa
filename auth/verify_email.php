<?php

declare(strict_types=1);

require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../config/db.php';

start_session();

$pageTitle = 'Email Verification | EAA';
$message = 'Invalid verification link.';
$status = 'error';

$token = trim((string) ($_GET['token'] ?? ''));

if ($token !== '') {
    $stmt = db()->prepare(
        'SELECT email_verifications.id, email_verifications.user_id, email_verifications.expires_at, users.email_verified_at
         FROM email_verifications
         JOIN users ON users.id = email_verifications.user_id
         WHERE email_verifications.token = :token
         LIMIT 1'
    );
    $stmt->execute(['token' => $token]);
    $verification = $stmt->fetch();

    if ($verification) {
        $expiresAt = new DateTime($verification['expires_at']);
        $now = new DateTime();

        if ($expiresAt < $now) {
            $message = 'This verification link has expired. Please request a new one.';
        } elseif ($verification['email_verified_at']) {
            $message = 'This email has already been verified.';
            $status = 'success';
        } else {
            $update = db()->prepare('UPDATE users SET email_verified_at = NOW() WHERE id = :id');
            $update->execute(['id' => $verification['user_id']]);

            $delete = db()->prepare('DELETE FROM email_verifications WHERE id = :id');
            $delete->execute(['id' => $verification['id']]);

            $message = 'Email verification successful. Your account is now queued for admin approval.';
            $status = 'success';
        }
    }
}

require_once __DIR__ . '/../partials/header.php';
?>

<main class="min-h-screen flex items-center justify-center bg-slate-50 px-6">
    <div class="max-w-xl w-full bg-white border border-slate-200 rounded p-10 text-center shadow-sm">
        <h1 class="font-druk text-3xl text-slate-900 mb-4">Verification <span class="text-slate-400 italic">Console</span></h1>
        <p class="text-[11px] font-semibold uppercase tracking-widest leading-loose <?= $status === 'success' ? 'text-emerald-600' : 'text-red-600' ?>">
            <?= e($message) ?>
        </p>
        <a href="/login.php" class="inline-block mt-8 text-[9px] font-black uppercase tracking-widest border-b border-slate-900">Return to Login</a>
    </div>
</main>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
