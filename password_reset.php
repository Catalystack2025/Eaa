<?php
/* =========================================================
   password_reset.php — PASSWORD RESET CONFIRMATION
   ✅ Token validation & expiry check
   ✅ Password update
   ✅ CSRF protection
   ========================================================= */

declare(strict_types=1);

require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/lib/password_reset.php';

start_session();

$pageTitle = 'Reset Password | EAA Portal';

$token = trim((string) ($_GET['token'] ?? $_POST['token'] ?? ''));
$reset = $token !== '' ? find_valid_password_reset($token) : null;
$formError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $formError = 'Security token mismatch. Please try again.';
    } elseif (!$reset) {
        $formError = 'This reset link is invalid or has expired.';
    } else {
        $password = (string) ($_POST['password'] ?? '');
        $confirm = (string) ($_POST['password_confirm'] ?? '');

        if (strlen($password) < 8) {
            $formError = 'Password must be at least 8 characters.';
        } elseif ($password !== $confirm) {
            $formError = 'Passwords do not match.';
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $pdo = db();
            $pdo->beginTransaction();
            try {
                $updateStmt = $pdo->prepare('UPDATE users SET password_hash = :password_hash WHERE id = :user_id');
                $updateStmt->execute([
                    'password_hash' => $passwordHash,
                    'user_id' => (int) $reset['user_id'],
                ]);

                mark_password_reset_used((int) $reset['id']);
                $pdo->commit();

                flash_set('success', 'Password updated successfully. You can now log in.');
                redirect('login.php');
            } catch (Throwable $e) {
                $pdo->rollBack();
                $formError = 'We could not reset your password. Please try again.';
                error_log('Password reset failed: ' . $e->getMessage());
            }
        }
    }
}

require_once __DIR__ . '/partials/header.php';
?>

<section class="min-h-[70vh] pt-32 pb-20">
    <div class="container mx-auto px-6">
        <div class="max-w-xl mx-auto bg-white/80 border border-slate-200 rounded-2xl shadow-xl p-8">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.35em] text-slate-400">Account Access</p>
                    <h1 class="text-2xl font-black uppercase tracking-widest text-slate-900 mt-2">Reset Password</h1>
                </div>
                <span class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-500">EAA</span>
            </div>

            <?php if (!$reset): ?>
                <div class="mt-6 border border-amber-200 bg-amber-50 text-amber-600 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-widest">
                    This reset link is invalid or has expired. Please request a new one.
                </div>
                <div class="mt-6">
                    <a
                        href="password_reset_request.php"
                        class="inline-flex items-center justify-center bg-slate-900 text-white text-xs font-black uppercase tracking-[0.35em] py-3 px-6 rounded-xl hover:bg-slate-800 transition"
                    >
                        Request New Link
                    </a>
                </div>
            <?php else: ?>
                <p class="mt-4 text-sm text-slate-500 leading-relaxed">
                    Choose a new password for your account. This reset link expires soon, so complete the update now.
                </p>

                <?php if ($formError): ?>
                    <div class="mt-6 border border-red-200 bg-red-50 text-red-500 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-widest">
                        <?= e($formError) ?>
                    </div>
                <?php endif; ?>

                <form method="post" class="mt-8 space-y-6">
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="token" value="<?= e($token) ?>">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">New password</label>
                        <input
                            type="password"
                            name="password"
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 focus:outline-none focus:border-slate-500"
                            placeholder="••••••••"
                            required
                        />
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Confirm password</label>
                        <input
                            type="password"
                            name="password_confirm"
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 focus:outline-none focus:border-slate-500"
                            placeholder="••••••••"
                            required
                        />
                    </div>
                    <button
                        type="submit"
                        class="w-full bg-slate-900 text-white text-xs font-black uppercase tracking-[0.35em] py-3 rounded-xl hover:bg-slate-800 transition"
                    >
                        Update Password
                    </button>
                </form>
            <?php endif; ?>

            <div class="mt-6 flex items-center justify-between text-[10px] uppercase tracking-[0.3em] font-black text-slate-400">
                <a href="login.php" class="hover:text-slate-700 transition">Back to Login</a>
                <span>Secure Node</span>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
