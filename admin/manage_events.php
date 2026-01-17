<?php
/* =========================================================
   admin/manage_events.php — EVENT PLANNER LEDGER
   ✅ Role: Administrator / Event Management
   ✅ Redesigned Premium Technical Table
   ✅ Exclusive Montserrat Typography
   ✅ High-density Technical Grid for Event Coordination
   ✅ Standardized 5px Radius & Smoke Grey Palette
   ========================================================= */

session_start();

$pageTitle = 'Event Planner | EAA Root';

// Mock Event Data for the Ledger
$events = [
    ['id' => 'EAA-EVT-2026-101', 'title' => 'Erode Design Biennale 2026', 'date' => '12 Feb 2026', 'time' => '10:00 AM', 'loc' => 'Heritage Center', 'cat' => 'Summit', 'reg' => '124', 'status' => 'Upcoming'],
    ['id' => 'EAA-EVT-2026-102', 'title' => 'Sustainable Urbanism Workshop', 'date' => '24 Feb 2026', 'time' => '02:30 PM', 'loc' => 'Guild Hall', 'cat' => 'Workshop', 'reg' => '45', 'status' => 'Upcoming'],
    ['id' => 'EAA-EVT-2026-103', 'title' => 'Architects Keynote Node', 'date' => '05 Mar 2026', 'time' => '06:00 PM', 'loc' => 'EAA Hub', 'cat' => 'Keynote', 'reg' => '18', 'status' => 'Draft'],
    ['id' => 'EAA-EVT-2025-098', 'title' => 'Interior Design Expo 2025', 'date' => '15 Dec 2025', 'time' => '09:00 AM', 'loc' => 'Expo Hall', 'cat' => 'Exhibition', 'reg' => '850', 'status' => 'Completed'],
    ['id' => 'EAA-EVT-2025-095', 'title' => 'BIM Coordination Level 02', 'date' => '10 Nov 2025', 'time' => '11:00 AM', 'loc' => 'Training Wing', 'cat' => 'Training', 'reg' => '32', 'status' => 'Completed'],
];

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
                <span class="text-xl font-black text-slate-900">08</span>
            </div>
            <div class="px-8 py-4 bg-white border border-slate-200 eaa-radius flex flex-col justify-center shadow-sm">
                <span class="text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Manifest</span>
                <span class="text-xl font-black text-green-600">1.4K</span>
            </div>
            <button class="px-10 py-4 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest eaa-radius shadow-2xl hover:bg-slate-700 transition-all">+ New Event</button>
        </div>
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
                <tr>
                    <td>
                        <div class="flex flex-col">
                            <span class="text-[12px] font-black text-slate-900 uppercase tracking-tight mb-1"><?= $e['title'] ?></span>
                            <div class="flex items-center gap-2">
                                <span class="text-[7px] font-black text-white bg-slate-400 px-1.5 py-0.5 rounded-sm uppercase tracking-widest"><?= $e['cat'] ?></span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-[0.1em]"><?= $e['id'] ?></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest"><?= $e['date'] ?></span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest"><?= $e['time'] ?></span>
                        </div>
                    </td>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-slate-50 flex items-center justify-center text-slate-300 border border-slate-100">
                                <i class="fa-solid fa-location-dot text-[10px]"></i>
                            </div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest"><?= $e['loc'] ?></span>
                        </div>
                    </td>
                    <td>
                        <div class="inline-flex items-center gap-3">
                            <span class="manifest-counter"><?= $e['reg'] ?></span>
                            <span class="text-[7px] font-black uppercase text-slate-300 tracking-widest">Logged</span>
                        </div>
                    </td>
                    <td>
                        <?php 
                        $statusClass = 'bg-slate-100 text-slate-500';
                        if($e['status'] == 'Upcoming') $statusClass = 'bg-blue-50 text-blue-600 border-blue-100';
                        if($e['status'] == 'Completed') $statusClass = 'bg-green-50 text-green-600 border-green-100';
                        if($e['status'] == 'Draft') $statusClass = 'bg-amber-50 text-amber-600 border-amber-100';
                        ?>
                        <span class="px-3 py-1 text-[7px] font-black uppercase tracking-widest rounded border <?= $statusClass ?>">
                            <?= $e['status'] ?>
                        </span>
                    </td>
                    <td>
                        <div class="flex justify-end gap-2">
                            <button class="action-node" title="Attendee List"><i class="fa-solid fa-list-check text-[11px]"></i></button>
                            <button class="action-node" title="Modify Node"><i class="fa-solid fa-pen-nib text-[11px]"></i></button>
                            <button class="action-node hover:!bg-red-500 hover:!border-red-500" title="Delete Entry"><i class="fa-solid fa-trash-can text-[11px]"></i></button>
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