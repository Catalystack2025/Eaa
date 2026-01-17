<?php
/* =========================================================
   password_reset_request.php — PASSWORD RESET REQUEST
   ✅ Token generation & email dispatch
   ✅ Privacy-preserving feedback
   ✅ CSRF protection
   ========================================================= */

declare(strict_types=1);

require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/lib/password_reset.php';

start_session();

$pageTitle = 'Request Password Reset | EAA Portal';

$formError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $formError = 'Security token mismatch. Please try again.';
    } else {
        $email = trim((string) ($_POST['email'] ?? ''));
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $formError = 'Please enter a valid email address.';
        } else {
            $user = find_user_by_email($email);
            if ($user) {
                $reset = create_password_reset((int) $user['id']);
                $resetUrl = url('password_reset.php?token=' . urlencode($reset['token']));
                $subject = 'Reset your EAA Portal password';
                $message = "Hello {$user['full_name']},\n\n" .
                    "We received a request to reset your password. Use the link below to choose a new one:\n\n" .
                    $resetUrl . "\n\n" .
                    "This link will expire in 60 minutes. If you did not request a reset, you can ignore this email.\n\n" .
                    "— EAA Portal";
                $headers = [
                    'From: EAA Portal <no-reply@eaa.local>',
                    'Content-Type: text/plain; charset=UTF-8',
                ];

                $mailSent = @mail($email, $subject, $message, implode("\r\n", $headers));
                if (!$mailSent) {
                    error_log('Password reset email failed to send for ' . $email);
                }
            }

            flash_set('success', 'If an account exists for that email, a reset link has been sent.');
            redirect('password_reset_request.php');
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
                    <h1 class="text-2xl font-black uppercase tracking-widest text-slate-900 mt-2">Request Reset</h1>
                </div>
                <span class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-500">EAA</span>
            </div>

            <p class="mt-4 text-sm text-slate-500 leading-relaxed">
                Enter the email linked to your membership. We will send a secure reset link if it exists in our system.
            </p>

            <?php if ($formError): ?>
                <div class="mt-6 border border-red-200 bg-red-50 text-red-500 px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-widest">
                    <?= e($formError) ?>
                </div>
            <?php endif; ?>

            <form method="post" class="mt-8 space-y-6">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Email address</label>
                    <input
                        type="email"
                        name="email"
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 focus:outline-none focus:border-slate-500"
                        placeholder="you@domain.com"
                        required
                    />
                </div>
                <button
                    type="submit"
                    class="w-full bg-slate-900 text-white text-xs font-black uppercase tracking-[0.35em] py-3 rounded-xl hover:bg-slate-800 transition"
                >
                    Send Reset Link
                </button>
            </form>

            <div class="mt-6 flex items-center justify-between text-[10px] uppercase tracking-[0.3em] font-black text-slate-400">
                <a href="login.php" class="hover:text-slate-700 transition">Back to Login</a>
                <span>Secure Node</span>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
