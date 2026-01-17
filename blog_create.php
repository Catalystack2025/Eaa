<?php
/* =========================================================
   blog_create.php — MEMBER BLOG SUBMISSION TERMINAL
   ✅ Member-only access
   ✅ Inserts blogs with pending status
   ✅ CSRF-protected form
   ========================================================= */

require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/config/db.php';

start_session();

if (!is_logged_in() || ($_SESSION['role'] ?? '') !== 'member') {
    flash_set('error', 'Please sign in with a member account to submit a blog.');
    redirect('login.php');
}

$title = '';
$body = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');

    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $errorMessage = 'Invalid session token. Please refresh and try again.';
    } elseif ($title === '' || $body === '') {
        $errorMessage = 'Title and body are required to submit your blog.';
    } else {
        $stmt = db()->prepare(
            'INSERT INTO blogs (author_id, title, body, status)
             VALUES (:author_id, :title, :body, :status)'
        );
        $stmt->execute([
            'author_id' => (int) $_SESSION['user_id'],
            'title' => $title,
            'body' => $body,
            'status' => 'pending',
        ]);

        flash_set('success', 'Your blog has been submitted for review.');
        redirect('blog_create.php');
    }
}

$pageTitle = 'Submit Blog | EAA Journal';
require_once __DIR__ . '/partials/header.php';
?>

<style>
    :root {
        --eaa-smoke: #475569;
        --eaa-border: #e2e8f0;
        --eaa-radius: 5px;
        --eaa-accent: #1e293b;
    }

    body {
        background-color: #f8fafc;
        color: #1e293b;
        font-family: 'Montserrat', sans-serif;
    }

    .font-druk {
        font-family: 'Montserrat', sans-serif;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: -0.04em;
        line-height: 0.9;
    }

    .eaa-radius { border-radius: var(--eaa-radius) !important; }

    .blueprint-grid {
        background-image: linear-gradient(rgba(71, 85, 105, 0.05) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(71, 85, 105, 0.05) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    .tech-label {
        font-size: 8px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: #94a3b8;
        display: block;
        margin-bottom: 8px;
    }

    .tech-input {
        width: 100%;
        background: #ffffff;
        border: 1px solid var(--eaa-border);
        border-radius: var(--eaa-radius);
        padding: 14px 18px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--eaa-accent);
        outline: none;
        transition: all 0.3s ease;
    }

    .tech-input:focus {
        border-color: var(--eaa-smoke);
        box-shadow: 0 0 0 1px var(--eaa-smoke);
    }

    .tech-textarea {
        min-height: 220px;
        resize: vertical;
        text-transform: none;
        letter-spacing: 0.02em;
        font-weight: 500;
        line-height: 1.7;
    }
</style>

<section class="py-24 bg-white relative overflow-hidden border-b border-slate-100">
    <div class="absolute inset-0 blueprint-grid opacity-30 pointer-events-none"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl">
            <span class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-400 mb-3 block">Member Journal Access</span>
            <h1 class="font-druk text-4xl md:text-6xl text-slate-900">Submit <span class="text-slate-400 italic">Blog</span></h1>
            <p class="mt-6 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">All submissions are reviewed before publishing.</p>
        </div>
    </div>
</section>

<section class="py-16 bg-slate-50">
    <div class="container mx-auto px-6">
        <div class="max-w-3xl bg-white border border-slate-200 eaa-radius shadow-xl p-10">
            <?php if ($errorMessage): ?>
                <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-[9px] font-black uppercase tracking-widest text-red-600 eaa-radius">
                    <?= e($errorMessage) ?>
                </div>
            <?php endif; ?>
            <form method="post" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <div>
                    <label class="tech-label" for="title">Blog Title</label>
                    <input id="title" name="title" type="text" class="tech-input" value="<?= e($title) ?>" placeholder="Enter a project title">
                </div>
                <div>
                    <label class="tech-label" for="body">Blog Body</label>
                    <textarea id="body" name="body" class="tech-input tech-textarea" placeholder="Share the full story..."><?= e($body) ?></textarea>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Status: Pending Review</p>
                    <button type="submit" class="px-8 py-3 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest eaa-radius shadow-lg hover:bg-slate-700 transition-all">Submit Blog</button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
