<?php

declare(strict_types=1);

require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/lib/mailer.php';

start_session();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $name = trim((string) ($_POST['name'] ?? ''));

    if (!$email) {
        flash_set('error', 'Please provide a valid email address.');
        redirect('forgot.php');
    }

    $token = bin2hex(random_bytes(16));
    $resetLink = url('reset.php?token=' . $token);

    try {
        send_template_mail($email, 'password_reset', [
            'name' => $name !== '' ? $name : 'Member',
            'reset_link' => $resetLink,
            'expires_in' => '30 minutes',
        ]);
        flash_set('success', 'Password reset instructions have been sent.');
    } catch (RuntimeException $exception) {
        flash_set('error', $exception->getMessage());
    }

    redirect('forgot.php');
}

$pageTitle = 'Reset Password | EAA Portal';
require_once __DIR__ . '/partials/header.php';
?>

<main class="relative min-h-screen flex items-center justify-center px-6 py-20">
    <div class="absolute inset-0 blueprint-grid opacity-40 pointer-events-none"></div>
    <section class="relative z-10 w-full max-w-lg bg-white border border-slate-200 shadow-2xl rounded-3xl p-10">
        <div class="mb-10">
            <span class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-400">Support Console</span>
            <h1 class="font-druk text-3xl md:text-4xl text-slate-900 mt-4 uppercase">
                Password <span class="text-slate-400 italic">Reset</span>
            </h1>
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-500 mt-4">
                Enter your registered email to receive a reset link.
            </p>
        </div>

        <form method="post" class="space-y-6">
            <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-3">Name (Optional)</label>
                <input type="text" name="name" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-[11px] font-semibold uppercase tracking-widest text-slate-700 focus:outline-none focus:border-slate-500" placeholder="Ar. Priya Sharma">
            </div>
            <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-3">Email Address</label>
                <input type="email" name="email" required class="w-full border border-slate-200 rounded-xl px-4 py-3 text-[11px] font-semibold uppercase tracking-widest text-slate-700 focus:outline-none focus:border-slate-500" placeholder="member@eaa.org">
            </div>
            <button type="submit" class="w-full py-4 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-slate-200 hover:bg-slate-700 transition-all">
                Send Reset Link
            </button>
        </form>
    </section>
</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
