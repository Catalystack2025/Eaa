<?php
/* =========================================================
   admin/manage_members.php — ARCHITECT REGISTRY LEDGER
   ✅ Role: Administrator / Member Management
   ✅ Redesigned Premium Technical Table
   ✅ Exclusive Montserrat Typography
   ✅ High-density Technical Grid for Member Data
   ✅ Standardized 5px Radius & Smoke Grey Palette
   ========================================================= */

declare(strict_types=1);

require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../config/db.php';

start_session();

$pageTitle = 'Architect Registry | EAA Root';

$allowedStatuses = ['pending', 'active', 'rejected'];

function column_exists(string $table, string $column): bool
{
    $stmt = db()->prepare(
        'SELECT COUNT(*)
         FROM INFORMATION_SCHEMA.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE()
           AND TABLE_NAME = :table
           AND COLUMN_NAME = :column'
    );
    $stmt->execute([
        'table' => $table,
        'column' => $column,
    ]);

    return (int) $stmt->fetchColumn() > 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $userId = (int) ($_POST['user_id'] ?? 0);
    $statusMap = [
        'approve' => 'active',
        'reject' => 'rejected',
    ];

    if ($userId > 0 && isset($statusMap[$action]) && csrf_verify($_POST['csrf_token'] ?? null)) {
        $stmt = db()->prepare('UPDATE users SET status = :status WHERE id = :id AND role = :role');
        $stmt->execute([
            'status' => $statusMap[$action],
            'id' => $userId,
            'role' => 'member',
        ]);
    }

    $redirect = basename(__FILE__);
    if (!empty($_SERVER['QUERY_STRING'])) {
        $redirect .= '?' . $_SERVER['QUERY_STRING'];
    }

    header('Location: ' . $redirect);
    exit;
}

$status = $_GET['status'] ?? '';
$category = $_GET['category'] ?? '';
$search = trim($_GET['search'] ?? '');
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 10;

$categoryColumn = column_exists('member_profile', 'category') ? 'member_profile.category' : null;
$filters = ['users.role = :role'];
$params = ['role' => 'member'];

if (in_array($status, $allowedStatuses, true)) {
    $filters[] = 'users.status = :status';
    $params['status'] = $status;
}

if ($categoryColumn && $category !== '') {
    $filters[] = $categoryColumn . ' = :category';
    $params['category'] = $category;
}

if ($search !== '') {
    $filters[] = '(users.full_name LIKE :search OR users.email LIKE :search OR member_profile.phone LIKE :search)';
    $params['search'] = '%' . $search . '%';
}

$whereClause = $filters ? ('WHERE ' . implode(' AND ', $filters)) : '';

$pendingFilters = $filters;
$pendingParams = $params;
if (!in_array($status, $allowedStatuses, true)) {
    $pendingFilters[] = 'users.status = :pending_status';
    $pendingParams['pending_status'] = 'pending';
} else {
    $pendingFilters = array_filter($pendingFilters, static fn($filter) => $filter !== 'users.status = :status');
    $pendingParams = array_filter($pendingParams, static fn($key) => $key !== 'status', ARRAY_FILTER_USE_KEY);
    $pendingFilters[] = 'users.status = :pending_status';
    $pendingParams['pending_status'] = 'pending';
}

$pendingWhereClause = $pendingFilters ? ('WHERE ' . implode(' AND ', $pendingFilters)) : '';

$pendingStmt = db()->prepare(
    'SELECT COUNT(*)
     FROM users
     JOIN member_profile ON member_profile.user_id = users.id
     ' . $pendingWhereClause
);
$pendingStmt->execute($pendingParams);
$pendingCount = (int) $pendingStmt->fetchColumn();

$countStmt = db()->prepare(
    'SELECT COUNT(*)
     FROM users
     JOIN member_profile ON member_profile.user_id = users.id
     ' . $whereClause
);
$countStmt->execute($params);
$totalMembers = (int) $countStmt->fetchColumn();
$totalPages = max(1, (int) ceil($totalMembers / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$categorySelect = $categoryColumn ? ($categoryColumn . ' AS category') : "'Member' AS category";

$query =
    'SELECT users.id, users.full_name, users.email, users.status, users.created_at,
            member_profile.phone,
            ' . $categorySelect . '
     FROM users
     JOIN member_profile ON member_profile.user_id = users.id
     ' . $whereClause . '
     ORDER BY users.created_at DESC
     LIMIT :limit OFFSET :offset';

$stmt = db()->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue(':' . $key, $value);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$members = $stmt->fetchAll();

function build_query(array $overrides = []): string
{
    return http_build_query(array_merge($_GET, $overrides));
}

require_once 'partials/header.php';
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
        padding: 20px 30px;
        color: #94a3b8;
        font-size: 8px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.2em;
    }

    .eaa-table td {
        padding: 24px 30px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .eaa-table tr:last-child td {
        border-bottom: none;
    }

    .eaa-table tr:hover td {
        background-color: #fcfdfe;
    }

    .action-node {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 3px;
        border: 1px solid #e2e8f0;
        color: #64748b;
        transition: all 0.2s ease;
        background: #ffffff;
    }

    .action-node:hover {
        background: #1e293b;
        color: #ffffff;
        border-color: #1e293b;
        transform: translateY(-1px);
    }

    .category-badge {
        background: #f1f5f9;
        padding: 4px 10px;
        border-radius: 3px;
        font-size: 8px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        border: 1px solid #e2e8f0;
        color: #475569;
    }
</style>

<!-- REGISTRY HEADER & STATS -->
<div class="mb-12 reveal active">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-10">
        <div>
            <span class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-400 mb-2 block">Database Node: REG_MEMBER</span>
            <h2 class="font-druk text-3xl md:text-5xl text-slate-900 uppercase">Architect <span class="text-slate-400 italic">Registry</span></h2>
        </div>
        <div class="flex gap-4">
            <div class="px-8 py-4 bg-white border border-slate-200 eaa-radius flex flex-col justify-center shadow-sm">
                <span class="text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Active Nodes</span>
                <span class="text-xl font-black text-slate-900"><?= $totalMembers ?></span>
            </div>
            <div class="px-8 py-4 bg-white border border-slate-200 eaa-radius flex flex-col justify-center shadow-sm">
                <span class="text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Pending Verification</span>
                <span class="text-xl font-black text-amber-600"><?= $pendingCount ?></span>
            </div>
            <button class="px-10 py-4 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest eaa-radius shadow-2xl hover:bg-slate-700 transition-all">+ Add Member</button>
        </div>
    </div>

    <!-- FILTERING BAR -->
    <form method="get" class="p-4 bg-white border border-slate-100 eaa-radius flex flex-col lg:flex-row gap-4 justify-between items-center shadow-sm">
        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
            <div class="flex items-center gap-2">
                <label class="text-[8px] font-black uppercase tracking-[0.3em] text-slate-400">Status</label>
                <select name="status" class="bg-slate-50 border border-slate-100 eaa-radius px-4 py-2 text-[9px] font-bold uppercase tracking-widest">
                    <option value="">All</option>
                    <?php foreach ($allowedStatuses as $option): ?>
                        <option value="<?= e($option) ?>" <?= $status === $option ? 'selected' : '' ?>><?= e($option) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <label class="text-[8px] font-black uppercase tracking-[0.3em] text-slate-400">Category</label>
                <input type="text" name="category" value="<?= e($category) ?>" placeholder="e.g. Licensed" class="bg-slate-50 border border-slate-100 eaa-radius px-4 py-2 text-[9px] font-bold uppercase tracking-widest">
            </div>
        </div>

        <div class="relative w-full lg:w-96">
            <input type="text" name="search" value="<?= e($search) ?>" placeholder="FILTER BY NAME, EMAIL, OR PHONE..." class="w-full bg-slate-50 border border-slate-100 eaa-radius px-6 py-3.5 text-[9px] font-bold uppercase tracking-widest outline-none focus:border-slate-400 transition-all">
            <i class="fa-solid fa-magnifying-glass absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 text-[10px]"></i>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="px-6 py-3 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest eaa-radius">Apply</button>
            <a href="<?= e(basename(__FILE__)) ?>" class="px-6 py-3 text-slate-500 text-[9px] font-black uppercase tracking-widest">Reset</a>
        </div>
    </form>
</div>

<!-- REDESIGNED MEMBER LEDGER TABLE -->
<div class="ledger-table-container reveal active" style="transition-delay: 100ms;">
    <div class="overflow-x-auto">
        <table class="eaa-table">
            <thead>
                <tr>
                    <th>Architect / Membership ID</th>
                    <th>Professional Class</th>
                    <th>Registration Node</th>
                    <th>Tenure</th>
                    <th>Status</th>
                    <th class="text-right">Operations</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($members)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-[10px] font-bold uppercase tracking-widest text-slate-400 py-10">
                            No members found for the selected filters.
                        </td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($members as $member): ?>
                    <?php
                    $statusClass = 'bg-slate-100 text-slate-500';
                    if ($member['status'] === 'active') {
                        $statusClass = 'bg-green-50 text-green-600 border-green-100';
                    }
                    if ($member['status'] === 'pending') {
                        $statusClass = 'bg-amber-50 text-amber-600 border-amber-100';
                    }
                    if ($member['status'] === 'rejected') {
                        $statusClass = 'bg-red-50 text-red-600 border-red-100';
                    }
                    $joined = $member['created_at'] ? date('d M Y', strtotime($member['created_at'])) : '—';
                    ?>
                    <tr>
                        <td>
                            <div class="flex flex-col">
                                <span class="text-[12px] font-black text-slate-900 uppercase tracking-tight mb-1"><?= e($member['full_name']) ?></span>
                                <div class="flex items-center gap-2">
                                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-[0.1em]">EAA-MEM-<?= e((string) $member['id']) ?></span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded bg-slate-50 flex items-center justify-center text-slate-300 border border-slate-100">
                                    <i class="fa-solid fa-user-tie text-[10px]"></i>
                                </div>
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest"><?= e($member['category']) ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest"><?= e($member['phone']) ?></span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest"><?= e($member['email']) ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest"><?= e($joined) ?></span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Enrollment</span>
                            </div>
                        </td>
                        <td>
                            <span class="px-3 py-1 text-[7px] font-black uppercase tracking-widest rounded border <?= $statusClass ?>">
                                <?= e($member['status']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="flex justify-end gap-2">
                                <?php if ($member['status'] !== 'active'): ?>
                                    <form method="post">
                                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                        <input type="hidden" name="user_id" value="<?= e((string) $member['id']) ?>">
                                        <input type="hidden" name="action" value="approve">
                                        <button class="action-node hover:!text-green-600" title="Approve Member">
                                            <i class="fa-solid fa-check text-[11px]"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <?php if ($member['status'] !== 'rejected'): ?>
                                    <form method="post">
                                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                        <input type="hidden" name="user_id" value="<?= e((string) $member['id']) ?>">
                                        <input type="hidden" name="action" value="reject">
                                        <button class="action-node hover:!bg-red-500 hover:!border-red-500" title="Reject Member">
                                            <i class="fa-solid fa-xmark text-[11px]"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Table Footer / Pagination -->
    <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
        <span class="text-[8px] font-black uppercase tracking-widest text-slate-400 italic">Chronicle Node: Accessing Page <?= e((string) $page) ?> // Total <?= e((string) $totalMembers) ?> Architects</span>
        <div class="flex gap-2">
            <?php
            $prevPage = max(1, $page - 1);
            $nextPage = min($totalPages, $page + 1);
            ?>
            <a href="?<?= e(build_query(['page' => $prevPage])) ?>" class="w-9 h-9 flex items-center justify-center bg-white border border-slate-200 eaa-radius text-slate-300 hover:border-slate-400 transition-all">
                <i class="fa-solid fa-chevron-left text-[10px]"></i>
            </a>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i === $page): ?>
                    <span class="w-9 h-9 flex items-center justify-center bg-slate-900 text-white eaa-radius text-[10px] font-black shadow-lg shadow-slate-200"><?= $i ?></span>
                <?php else: ?>
                    <a href="?<?= e(build_query(['page' => $i])) ?>" class="w-9 h-9 flex items-center justify-center bg-white border border-slate-200 eaa-radius text-[10px] font-black text-slate-400 hover:border-slate-400 transition-all"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            <a href="?<?= e(build_query(['page' => $nextPage])) ?>" class="w-9 h-9 flex items-center justify-center bg-white border border-slate-200 eaa-radius text-slate-300 hover:border-slate-400 transition-all">
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </a>
        </div>
    </div>
</div>

<?php
require_once 'partials/footer.php';
?>
