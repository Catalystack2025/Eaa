<?php
/* =========================================================
   admin/approvals_team.php — TEAM PROFILE APPROVALS
   ✅ Council profile moderation & featuring controls
   ✅ Montserrat Typography & 5px Radius
   ========================================================= */

require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../config/db.php';

start_session();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        flash_set('team_admin_error', 'Invalid session token. Please retry.');
        redirect('admin/approvals_team.php');
    }

    $memberId = (int) ($_POST['member_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($memberId > 0) {
        if ($action === 'approve') {
            $stmt = db()->prepare('UPDATE team_members SET approved = 1 WHERE id = ?');
            $stmt->execute([$memberId]);
        } elseif ($action === 'revoke') {
            $stmt = db()->prepare('UPDATE team_members SET approved = 0 WHERE id = ?');
            $stmt->execute([$memberId]);
        } elseif ($action === 'feature') {
            $stmt = db()->prepare('UPDATE team_members SET featured = 1 WHERE id = ?');
            $stmt->execute([$memberId]);
        } elseif ($action === 'unfeature') {
            $stmt = db()->prepare('UPDATE team_members SET featured = 0 WHERE id = ?');
            $stmt->execute([$memberId]);
        } elseif ($action === 'toggle_visible') {
            $stmt = db()->prepare('UPDATE team_members SET visible = IF(visible = 1, 0, 1) WHERE id = ?');
            $stmt->execute([$memberId]);
        }

        flash_set('team_admin_status', 'Team member status updated.');
        redirect('admin/approvals_team.php');
    }

    flash_set('team_admin_error', 'Please select a valid team member record.');
    redirect('admin/approvals_team.php');
}

$teamMembers = db()->query(
    'SELECT team_members.id, team_members.title, team_members.category, team_members.visible,
            team_members.approved, team_members.featured, team_members.photo_path,
            users.full_name, users.email
     FROM team_members
     JOIN users ON users.id = team_members.user_id
     ORDER BY team_members.approved ASC, team_members.featured DESC, team_members.category ASC, users.full_name ASC'
)->fetchAll();

$teamAdminStatus = flash_get('team_admin_status');
$teamAdminError = flash_get('team_admin_error');

$pageTitle = 'Council Team Approvals | EAA Root';
require_once __DIR__ . '/partials/header.php';
?>

<style>
    .approval-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .approval-table th {
        background: #f8fafc;
        padding: 16px 24px;
        font-size: 8px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: #94a3b8;
        border-bottom: 1px solid #e2e8f0;
    }

    .approval-table td {
        padding: 18px 24px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .status-pill {
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 7px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        border: 1px solid transparent;
    }

    .status-approved { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
    .status-pending { background: #fef3c7; color: #92400e; border-color: #fde68a; }
    .status-hidden { background: #e2e8f0; color: #475569; border-color: #cbd5f5; }
    .status-featured { background: #e0e7ff; color: #3730a3; border-color: #c7d2fe; }
</style>

<div class="mb-10 reveal active">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-6">
        <div>
            <span class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-400 mb-2 block">Approval Queue: TEAM_COUNCIL</span>
            <h2 class="font-druk text-3xl md:text-5xl text-slate-900 uppercase">Council <span class="text-slate-400 italic">Profiles</span></h2>
        </div>
        <div class="flex gap-4">
            <div class="px-8 py-4 bg-white border border-slate-200 eaa-radius flex flex-col justify-center shadow-sm">
                <span class="text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Pending</span>
                <span class="text-xl font-black text-amber-600"><?= count(array_filter($teamMembers, fn($m) => !$m['approved'])) ?></span>
            </div>
            <div class="px-8 py-4 bg-white border border-slate-200 eaa-radius flex flex-col justify-center shadow-sm">
                <span class="text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Featured</span>
                <span class="text-xl font-black text-indigo-600"><?= count(array_filter($teamMembers, fn($m) => (bool) $m['featured'])) ?></span>
            </div>
        </div>
    </div>

    <?php if ($teamAdminStatus): ?>
        <div class="mb-6 px-5 py-4 bg-green-50 border border-green-200 text-green-700 text-[9px] font-black uppercase tracking-widest eaa-radius">
            <?= e($teamAdminStatus) ?>
        </div>
    <?php endif; ?>
    <?php if ($teamAdminError): ?>
        <div class="mb-6 px-5 py-4 bg-amber-50 border border-amber-200 text-amber-700 text-[9px] font-black uppercase tracking-widest eaa-radius">
            <?= e($teamAdminError) ?>
        </div>
    <?php endif; ?>
</div>

<div class="bg-white border border-slate-200 eaa-radius shadow-sm overflow-hidden reveal active" style="transition-delay: 100ms;">
    <div class="overflow-x-auto">
        <table class="approval-table">
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Role</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th class="text-right">Controls</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($teamMembers)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-slate-400 text-[10px] font-black uppercase tracking-widest py-10">No team profile submissions yet.</td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($teamMembers as $member): ?>
                    <tr>
                        <td>
                            <div class="flex items-center gap-4">
                                <?php
                                $photoPath = $member['photo_path'] ? asset($member['photo_path']) : 'https://via.placeholder.com/80x100?text=EAA';
                                ?>
                                <img src="<?= e($photoPath) ?>" alt="<?= e($member['full_name']) ?>" class="w-12 h-16 object-cover eaa-radius border border-slate-200">
                                <div>
                                    <div class="text-[11px] font-black text-slate-900 uppercase tracking-tight"><?= e($member['full_name']) ?></div>
                                    <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest"><?= e($member['email']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-[10px] font-black text-slate-700 uppercase tracking-widest"><?= e($member['title']) ?></span>
                        </td>
                        <td>
                            <span class="text-[9px] font-black uppercase tracking-widest text-slate-500"><?= e($member['category']) ?></span>
                        </td>
                        <td>
                            <div class="flex flex-wrap gap-2">
                                <span class="status-pill <?= $member['approved'] ? 'status-approved' : 'status-pending' ?>">
                                    <?= $member['approved'] ? 'Approved' : 'Pending' ?>
                                </span>
                                <?php if ($member['featured']): ?>
                                    <span class="status-pill status-featured">Featured</span>
                                <?php endif; ?>
                                <?php if (!$member['visible']): ?>
                                    <span class="status-pill status-hidden">Hidden</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-wrap justify-end gap-2">
                                <form method="post" class="inline">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="member_id" value="<?= e((string) $member['id']) ?>">
                                    <input type="hidden" name="action" value="<?= $member['approved'] ? 'revoke' : 'approve' ?>">
                                    <button class="px-4 py-2 text-[8px] font-black uppercase tracking-widest border border-slate-200 eaa-radius hover:bg-slate-900 hover:text-white transition-all">
                                        <?= $member['approved'] ? 'Revoke' : 'Approve' ?>
                                    </button>
                                </form>
                                <form method="post" class="inline">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="member_id" value="<?= e((string) $member['id']) ?>">
                                    <input type="hidden" name="action" value="<?= $member['featured'] ? 'unfeature' : 'feature' ?>">
                                    <button class="px-4 py-2 text-[8px] font-black uppercase tracking-widest border border-slate-200 eaa-radius hover:bg-indigo-600 hover:text-white transition-all">
                                        <?= $member['featured'] ? 'Unfeature' : 'Feature' ?>
                                    </button>
                                </form>
                                <form method="post" class="inline">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="member_id" value="<?= e((string) $member['id']) ?>">
                                    <input type="hidden" name="action" value="toggle_visible">
                                    <button class="px-4 py-2 text-[8px] font-black uppercase tracking-widest border border-slate-200 eaa-radius hover:bg-slate-700 hover:text-white transition-all">
                                        <?= $member['visible'] ? 'Hide' : 'Show' ?>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
