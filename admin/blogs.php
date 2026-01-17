<?php
/* =========================================================
   admin/blogs.php — BLOG MODERATION CONSOLE
   ✅ Approve / reject / publish workflow
   ✅ Status-driven technical ledger
   ========================================================= */

require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../config/db.php';

start_session();

if (($_SESSION['role'] ?? '') !== 'admin') {
    flash_set('error', 'Administrator access required.');
    redirect('login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        flash_set('error', 'Invalid session token. Please try again.');
        redirect('admin/blogs.php');
    }

    $blogId = (int) ($_POST['blog_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    $statusMap = [
        'approve' => 'approved',
        'reject' => 'rejected',
        'publish' => 'published',
    ];

    if ($blogId > 0 && isset($statusMap[$action])) {
        $stmt = db()->prepare('UPDATE blogs SET status = :status WHERE id = :id');
        $stmt->execute([
            'status' => $statusMap[$action],
            'id' => $blogId,
        ]);
        flash_set('success', 'Blog status updated.');
    } else {
        flash_set('error', 'Invalid action requested.');
    }

    redirect('admin/blogs.php');
}

$stmt = db()->query(
    'SELECT b.id, b.title, b.status, b.created_at, u.full_name AS author_name, u.email
     FROM blogs b
     INNER JOIN users u ON b.author_id = u.id
     ORDER BY b.created_at DESC'
);
$blogs = $stmt->fetchAll();

$flashSuccess = flash_get('success');
$flashError = flash_get('error');

$pageTitle = 'Blog Moderation | EAA Root';
require_once __DIR__ . '/partials/header.php';
?>

<style>
    .ledger-table-container {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 5px;
        box-shadow: 0 10px 30px -10px rgba(71, 85, 105, 0.05);
    }

    .eaa-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .eaa-table th {
        background: #f8fafc;
        border-bottom: 1px solid #eef2f6;
        padding: 18px 24px;
        color: #94a3b8;
        font-size: 8px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        text-align: left;
    }

    .eaa-table td {
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .eaa-table tr:last-child td {
        border-bottom: none;
    }

    .status-pill {
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 7px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        border: 1px solid transparent;
        display: inline-flex;
    }

    .status-pending { background: #fef9c3; color: #b45309; border-color: #fde68a; }
    .status-approved { background: #dbeafe; color: #1d4ed8; border-color: #bfdbfe; }
    .status-published { background: #dcfce7; color: #15803d; border-color: #bbf7d0; }
    .status-rejected { background: #fee2e2; color: #b91c1c; border-color: #fecaca; }

    .action-button {
        padding: 8px 12px;
        border-radius: 3px;
        font-size: 8px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #0f172a;
        transition: all 0.2s ease;
    }

    .action-button:hover { background: #0f172a; color: #ffffff; border-color: #0f172a; }
</style>

<div class="mb-10 reveal active">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <span class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-400 mb-2 block">Review Queue</span>
            <h2 class="font-druk text-3xl md:text-5xl text-slate-900 uppercase">Blog <span class="text-slate-400 italic">Moderation</span></h2>
        </div>
        <div class="px-6 py-3 bg-white border border-slate-200 eaa-radius text-[9px] font-black uppercase tracking-widest text-slate-500">
            Total Entries: <?= count($blogs) ?>
        </div>
    </div>
</div>

<?php if ($flashSuccess): ?>
    <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-200 text-[9px] font-black uppercase tracking-widest text-emerald-600 eaa-radius">
        <?= e($flashSuccess) ?>
    </div>
<?php endif; ?>
<?php if ($flashError): ?>
    <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-[9px] font-black uppercase tracking-widest text-red-600 eaa-radius">
        <?= e($flashError) ?>
    </div>
<?php endif; ?>

<div class="ledger-table-container reveal active" style="transition-delay: 100ms;">
    <div class="overflow-x-auto">
        <table class="eaa-table">
            <thead>
                <tr>
                    <th>Blog Title</th>
                    <th>Author</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$blogs): ?>
                    <tr>
                        <td colspan="5" class="text-center text-[10px] font-bold uppercase tracking-widest text-slate-400 py-10">No blog submissions found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($blogs as $blog): ?>
                        <?php
                        $statusClass = match ($blog['status']) {
                            'approved' => 'status-approved',
                            'published' => 'status-published',
                            'rejected' => 'status-rejected',
                            default => 'status-pending',
                        };
                        ?>
                        <tr>
                            <td>
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-black text-slate-900 uppercase tracking-tight mb-1"><?= e($blog['title']) ?></span>
                                    <span class="text-[8px] font-bold uppercase tracking-widest text-slate-400">Blog ID: <?= (int) $blog['id'] ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-slate-700 uppercase tracking-widest"><?= e($blog['author_name']) ?></span>
                                    <span class="text-[8px] font-bold uppercase tracking-widest text-slate-400"><?= e($blog['email']) ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500"><?= e(date('M d, Y', strtotime($blog['created_at']))) ?></span>
                            </td>
                            <td>
                                <span class="status-pill <?= $statusClass ?>"><?= e($blog['status']) ?></span>
                            </td>
                            <td class="text-right">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <?php foreach (['approve' => 'Approve', 'publish' => 'Publish', 'reject' => 'Reject'] as $action => $label): ?>
                                        <form method="post">
                                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                            <input type="hidden" name="blog_id" value="<?= (int) $blog['id'] ?>">
                                            <input type="hidden" name="action" value="<?= e($action) ?>">
                                            <button type="submit" class="action-button"><?= e($label) ?></button>
                                        </form>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
