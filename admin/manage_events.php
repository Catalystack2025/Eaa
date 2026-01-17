<?php
/* =========================================================
   admin/manage_events.php — EVENT PLANNER LEDGER
   ✅ Role: Administrator / Event Management
   ✅ Redesigned Premium Technical Table
   ✅ Exclusive Montserrat Typography
   ✅ High-density Technical Grid for Event Coordination
   ✅ Standardized 5px Radius & Smoke Grey Palette
   ========================================================= */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/auth.php';

start_session();

if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: /login.php');
    exit;
}

$admin = current_user();

$pageTitle = 'Event Planner | EAA Root';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        flash_set('event_error', 'Security verification failed. Please try again.');
        redirect('admin/manage_events.php');
    }

    $action = $_POST['action'] ?? '';
    $title = trim((string) ($_POST['title'] ?? ''));
    $startDate = (string) ($_POST['start_date'] ?? '');
    $endDate = (string) ($_POST['end_date'] ?? '');
    $location = trim((string) ($_POST['location'] ?? ''));
    $description = trim((string) ($_POST['description'] ?? ''));
    $isPublic = isset($_POST['is_public']) ? 1 : 0;

    if ($action === 'create') {
        if ($title === '' || $startDate === '' || $endDate === '') {
            flash_set('event_error', 'Title, start date, and end date are required.');
            redirect('admin/manage_events.php');
        }

        $stmt = db()->prepare(
            'INSERT INTO events (title, start_date, end_date, location, description, is_public, created_by)
             VALUES (:title, :start_date, :end_date, :location, :description, :is_public, :created_by)'
        );
        $stmt->execute([
            'title' => $title,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location' => $location !== '' ? $location : null,
            'description' => $description !== '' ? $description : null,
            'is_public' => $isPublic,
            'created_by' => (int) $_SESSION['user_id'],
        ]);
        flash_set('event_success', 'Event created successfully.');
        redirect('admin/manage_events.php');
    }

    if ($action === 'update') {
        $eventId = (int) ($_POST['event_id'] ?? 0);
        if ($eventId === 0 || $title === '' || $startDate === '' || $endDate === '') {
            flash_set('event_error', 'Event update failed. Please check the details.');
            redirect('admin/manage_events.php');
        }

        $stmt = db()->prepare(
            'UPDATE events
             SET title = :title,
                 start_date = :start_date,
                 end_date = :end_date,
                 location = :location,
                 description = :description,
                 is_public = :is_public
             WHERE id = :id'
        );
        $stmt->execute([
            'title' => $title,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location' => $location !== '' ? $location : null,
            'description' => $description !== '' ? $description : null,
            'is_public' => $isPublic,
            'id' => $eventId,
        ]);
        flash_set('event_success', 'Event updated successfully.');
        redirect('admin/manage_events.php');
    }

    if ($action === 'delete') {
        $eventId = (int) ($_POST['event_id'] ?? 0);
        if ($eventId === 0) {
            flash_set('event_error', 'Invalid event selection.');
            redirect('admin/manage_events.php');
        }
        $stmt = db()->prepare('DELETE FROM events WHERE id = :id');
        $stmt->execute(['id' => $eventId]);
        flash_set('event_success', 'Event deleted.');
        redirect('admin/manage_events.php');
    }
}

$editEvent = null;
if (!empty($_GET['edit_id'])) {
    $editId = (int) $_GET['edit_id'];
    $stmt = db()->prepare('SELECT * FROM events WHERE id = :id');
    $stmt->execute(['id' => $editId]);
    $editEvent = $stmt->fetch() ?: null;
}

$events = db()->query(
    'SELECT e.*, COUNT(er.id) AS registrants
     FROM events e
     LEFT JOIN event_registrations er ON er.event_id = e.id
     GROUP BY e.id
     ORDER BY e.start_date DESC'
)->fetchAll();

$today = new DateTimeImmutable('today');
$upcomingCount = 0;
$totalRegistrations = 0;
foreach ($events as $event) {
    $totalRegistrations += (int) $event['registrants'];
    if (new DateTimeImmutable($event['start_date']) >= $today && (int) $event['is_public'] === 1) {
        $upcomingCount++;
    }
}

$flashSuccess = flash_get('event_success');
$flashError = flash_get('event_error');

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

    .manifest-counter {
        background: #f1f5f9;
        padding: 4px 10px;
        border-radius: 3px;
        font-size: 10px;
        font-weight: 800;
        color: #1e293b;
        border: 1px solid #e2e8f0;
    }
</style>

<!-- PLANNER HEADER & STATS -->
<div class="mb-12 reveal active">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-10">
        <div>
            <span class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-400 mb-2 block">Database Node: OPS_EVENT</span>
            <h2 class="font-druk text-3xl md:text-5xl text-slate-900 uppercase">Event <span class="text-slate-400 italic">Planner</span></h2>
        </div>
        <div class="flex gap-4">
            <div class="px-8 py-4 bg-white border border-slate-200 eaa-radius flex flex-col justify-center shadow-sm">
                <span class="text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Upcoming</span>
                <span class="text-xl font-black text-slate-900"><?= $upcomingCount ?></span>
            </div>
            <div class="px-8 py-4 bg-white border border-slate-200 eaa-radius flex flex-col justify-center shadow-sm">
                <span class="text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Manifest</span>
                <span class="text-xl font-black text-green-600"><?= number_format($totalRegistrations) ?></span>
            </div>
            <a href="#eventForm" class="px-10 py-4 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest eaa-radius shadow-2xl hover:bg-slate-700 transition-all">+ New Event</a>
        </div>
    </div>

    <?php if ($flashSuccess): ?>
        <div class="mb-8 p-6 bg-green-50 border border-green-100 eaa-radius flex items-center gap-4">
            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
            <div class="text-[10px] font-black uppercase tracking-widest text-green-700"><?= e($flashSuccess) ?></div>
        </div>
    <?php elseif ($flashError): ?>
        <div class="mb-8 p-6 bg-red-50 border border-red-100 eaa-radius flex items-center gap-4">
            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
            <div class="text-[10px] font-black uppercase tracking-widest text-red-700"><?= e($flashError) ?></div>
        </div>
    <?php endif; ?>

    <div id="eventForm" class="p-8 bg-white border border-slate-200 eaa-radius shadow-sm mb-10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <span class="text-[8px] font-black uppercase tracking-[0.3em] text-slate-400">Control Node</span>
                <h3 class="font-druk text-2xl uppercase text-slate-900"><?= $editEvent ? 'Edit Event' : 'Create Event' ?></h3>
            </div>
            <?php if ($editEvent): ?>
                <a href="manage_events.php" class="text-[9px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900">Cancel Edit</a>
            <?php endif; ?>
        </div>
        <form method="post" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="action" value="<?= $editEvent ? 'update' : 'create' ?>">
            <?php if ($editEvent): ?>
                <input type="hidden" name="event_id" value="<?= (int) $editEvent['id'] ?>">
            <?php endif; ?>
            <div class="md:col-span-2">
                <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Event Title</label>
                <input type="text" name="title" required value="<?= e($editEvent['title'] ?? '') ?>" class="mt-2 w-full bg-slate-50 border border-slate-200 eaa-radius px-4 py-3 text-[10px] font-bold uppercase tracking-widest">
            </div>
            <div>
                <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Start Date</label>
                <input type="date" name="start_date" required value="<?= e($editEvent['start_date'] ?? '') ?>" class="mt-2 w-full bg-slate-50 border border-slate-200 eaa-radius px-4 py-3 text-[10px] font-bold uppercase tracking-widest">
            </div>
            <div>
                <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">End Date</label>
                <input type="date" name="end_date" required value="<?= e($editEvent['end_date'] ?? '') ?>" class="mt-2 w-full bg-slate-50 border border-slate-200 eaa-radius px-4 py-3 text-[10px] font-bold uppercase tracking-widest">
            </div>
            <div class="md:col-span-2">
                <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Location</label>
                <input type="text" name="location" value="<?= e($editEvent['location'] ?? '') ?>" class="mt-2 w-full bg-slate-50 border border-slate-200 eaa-radius px-4 py-3 text-[10px] font-bold uppercase tracking-widest">
            </div>
            <div class="md:col-span-2">
                <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Description</label>
                <textarea name="description" rows="4" class="mt-2 w-full bg-slate-50 border border-slate-200 eaa-radius px-4 py-3 text-[10px] font-bold uppercase tracking-widest"><?= e($editEvent['description'] ?? '') ?></textarea>
            </div>
            <div class="md:col-span-2 flex items-center gap-3">
                <input id="is_public" type="checkbox" name="is_public" value="1" class="w-4 h-4" <?= $editEvent === null || (int) ($editEvent['is_public'] ?? 1) === 1 ? 'checked' : '' ?>>
                <label for="is_public" class="text-[9px] font-black uppercase tracking-widest text-slate-500">Visible to members</label>
            </div>
            <div class="md:col-span-2 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest eaa-radius shadow-lg hover:bg-slate-700 transition-all">
                    <?= $editEvent ? 'Update Event' : 'Create Event' ?>
                </button>
            </div>
        </form>
    </div>

    <!-- FILTERING BAR -->
    <div class="p-4 bg-white border border-slate-100 eaa-radius flex flex-col lg:flex-row gap-4 justify-between items-center shadow-sm">
        <div class="flex items-center gap-1 overflow-x-auto no-scrollbar w-full lg:w-auto">
            <button class="px-6 py-2.5 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest eaa-radius">All Schedule</button>
            <button class="px-6 py-2.5 text-slate-400 hover:text-slate-900 text-[9px] font-black uppercase tracking-widest transition-all">Summits</button>
            <button class="px-6 py-2.5 text-slate-400 hover:text-slate-900 text-[9px] font-black uppercase tracking-widest transition-all">Workshops</button>
            <button class="px-6 py-2.5 text-slate-400 hover:text-slate-900 text-[9px] font-black uppercase tracking-widest transition-all">Expos</button>
            <div class="w-px h-4 bg-slate-100 mx-2"></div>
            <button class="px-6 py-2.5 text-amber-600 font-black text-[9px] uppercase tracking-widest">Drafts (3)</button>
        </div>
        
        <div class="relative w-full lg:w-96">
            <input type="text" placeholder="FILTER BY TITLE, VENUE, OR REF_ID..." class="w-full bg-slate-50 border border-slate-100 eaa-radius px-6 py-3.5 text-[9px] font-bold uppercase tracking-widest outline-none focus:border-slate-400 transition-all">
            <i class="fa-solid fa-magnifying-glass absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 text-[10px]"></i>
        </div>
    </div>
</div>

<!-- REDESIGNED EVENT LEDGER TABLE -->
<div class="ledger-table-container reveal active" style="transition-delay: 100ms;">
    <div class="overflow-x-auto">
        <table class="eaa-table">
            <thead>
                <tr>
                    <th>Event Detail / Ref_ID</th>
                    <th>Chronological Node</th>
                    <th>Site Location</th>
                    <th>Manifest</th>
                    <th>Status</th>
                    <th class="text-right">Operations</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($events as $e): ?>
                <?php
                    $startDate = new DateTimeImmutable($e['start_date']);
                    $endDate = new DateTimeImmutable($e['end_date']);
                    $status = 'Draft';
                    if ((int) $e['is_public'] === 1) {
                        $status = $endDate < $today ? 'Completed' : 'Upcoming';
                    }
                    $statusClass = 'bg-slate-100 text-slate-500';
                    if ($status === 'Upcoming') $statusClass = 'bg-blue-50 text-blue-600 border-blue-100';
                    if ($status === 'Completed') $statusClass = 'bg-green-50 text-green-600 border-green-100';
                    if ($status === 'Draft') $statusClass = 'bg-amber-50 text-amber-600 border-amber-100';
                ?>
                <tr>
                    <td>
                        <div class="flex flex-col">
                            <span class="text-[12px] font-black text-slate-900 uppercase tracking-tight mb-1"><?= e($e['title']) ?></span>
                            <div class="flex items-center gap-2">
                                <span class="text-[7px] font-black text-white bg-slate-400 px-1.5 py-0.5 rounded-sm uppercase tracking-widest"><?= (int) $e['is_public'] === 1 ? 'Public' : 'Draft' ?></span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-[0.1em]">EAA-EVT-<?= str_pad((string) $e['id'], 4, '0', STR_PAD_LEFT) ?></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest"><?= $startDate->format('d M Y') ?></span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest"><?= $endDate->format('d M Y') ?></span>
                        </div>
                    </td>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-slate-50 flex items-center justify-center text-slate-300 border border-slate-100">
                                <i class="fa-solid fa-location-dot text-[10px]"></i>
                            </div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest"><?= e($e['location'] ?? 'TBD') ?></span>
                        </div>
                    </td>
                    <td>
                        <div class="inline-flex items-center gap-3">
                            <span class="manifest-counter"><?= (int) $e['registrants'] ?></span>
                            <span class="text-[7px] font-black uppercase text-slate-300 tracking-widest">Logged</span>
                        </div>
                    </td>
                    <td>
                        <span class="px-3 py-1 text-[7px] font-black uppercase tracking-widest rounded border <?= $statusClass ?>">
                            <?= $status ?>
                        </span>
                    </td>
                    <td>
                        <div class="flex justify-end gap-2">
                            <button class="action-node" title="Attendee List" disabled><i class="fa-solid fa-list-check text-[11px]"></i></button>
                            <a class="action-node" href="manage_events.php?edit_id=<?= (int) $e['id'] ?>" title="Modify Node"><i class="fa-solid fa-pen-nib text-[11px]"></i></a>
                            <form method="post" onsubmit="return confirm('Delete this event?');">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="event_id" value="<?= (int) $e['id'] ?>">
                                <button class="action-node hover:!bg-red-500 hover:!border-red-500" title="Delete Entry"><i class="fa-solid fa-trash-can text-[11px]"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Table Footer / Pagination -->
    <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
        <span class="text-[8px] font-black uppercase tracking-widest text-slate-400 italic">Chronicle Node: Accessing Page 01 // Total 05 Records Loaded</span>
        <div class="flex gap-2">
            <button class="w-9 h-9 flex items-center justify-center bg-white border border-slate-200 eaa-radius text-slate-300 hover:border-slate-400 transition-all"><i class="fa-solid fa-chevron-left text-[10px]"></i></button>
            <button class="w-9 h-9 flex items-center justify-center bg-slate-900 text-white eaa-radius text-[10px] font-black shadow-lg shadow-slate-200">1</button>
            <button class="w-9 h-9 flex items-center justify-center bg-white border border-slate-200 eaa-radius text-[10px] font-black text-slate-400 hover:border-slate-400 transition-all">2</button>
            <button class="w-9 h-9 flex items-center justify-center bg-white border border-slate-200 eaa-radius text-slate-400 hover:border-slate-400 transition-all"><i class="fa-solid fa-chevron-right text-[10px]"></i></button>
        </div>
    </div>
</div>

<?php 
require_once 'partials/footer.php'; 
?>
