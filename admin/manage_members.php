<?php
/* =========================================================
   admin/manage_members.php — ARCHITECT REGISTRY LEDGER
   ✅ Role: Administrator / Member Management
   ✅ Redesigned Premium Technical Table
   ✅ Exclusive Montserrat Typography
   ✅ High-density Technical Grid for Member Data
   ✅ Standardized 5px Radius & Smoke Grey Palette
   ========================================================= */

session_start();

$pageTitle = 'Architect Registry | EAA Root';

// Mock Member Data for the Ledger
$members = [
    ['id' => 'EAA-2024-892', 'name' => 'Ar. Suresh Kumar', 'cat' => 'Licensed', 'coa' => 'CA/2012/55432', 'status' => 'Active', 'joined' => '12 Jan 2024'],
    ['id' => 'EAA-2025-104', 'name' => 'Ar. Priya Sharma', 'cat' => 'Licensed', 'coa' => 'CA/2018/89021', 'status' => 'Active', 'joined' => '05 Feb 2025'],
    ['id' => 'EAA-2026-012', 'name' => 'Vijay Prasath', 'cat' => 'Student', 'coa' => 'S.A.P Erode', 'status' => 'Pending', 'joined' => '14 Jan 2026'],
    ['id' => 'EAA-2024-771', 'name' => 'Ar. Rajesh M.', 'cat' => 'Professional', 'coa' => 'Registry Verified', 'status' => 'Expired', 'joined' => '22 Jun 2024'],
    ['id' => 'EAA-2025-442', 'name' => 'Ar. Lakshmi N.', 'cat' => 'Licensed', 'coa' => 'CA/2015/66712', 'status' => 'Active', 'joined' => '18 Aug 2025'],
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
                <span class="text-xl font-black text-slate-900">524</span>
            </div>
            <div class="px-8 py-4 bg-white border border-slate-200 eaa-radius flex flex-col justify-center shadow-sm">
                <span class="text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Pending Verification</span>
                <span class="text-xl font-black text-amber-600">18</span>
            </div>
            <button class="px-10 py-4 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest eaa-radius shadow-2xl hover:bg-slate-700 transition-all">+ Add Member</button>
        </div>
    </div>

    <!-- FILTERING BAR -->
    <div class="p-4 bg-white border border-slate-100 eaa-radius flex flex-col lg:flex-row gap-4 justify-between items-center shadow-sm">
        <div class="flex items-center gap-1 overflow-x-auto no-scrollbar w-full lg:w-auto">
            <button class="px-6 py-2.5 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest eaa-radius">All Registry</button>
            <button class="px-6 py-2.5 text-slate-400 hover:text-slate-900 text-[9px] font-black uppercase tracking-widest transition-all">Licensed</button>
            <button class="px-6 py-2.5 text-slate-400 hover:text-slate-900 text-[9px] font-black uppercase tracking-widest transition-all">Student</button>
            <button class="px-6 py-2.5 text-slate-400 hover:text-slate-900 text-[9px] font-black uppercase tracking-widest transition-all">Professional</button>
            <div class="w-px h-4 bg-slate-100 mx-2"></div>
            <button class="px-6 py-2.5 text-amber-600 font-black text-[9px] uppercase tracking-widest">Awaiting (18)</button>
        </div>
        
        <div class="relative w-full lg:w-96">
            <input type="text" placeholder="FILTER BY NAME, ID, OR COA_REF..." class="w-full bg-slate-50 border border-slate-100 eaa-radius px-6 py-3.5 text-[9px] font-bold uppercase tracking-widest outline-none focus:border-slate-400 transition-all">
            <i class="fa-solid fa-magnifying-glass absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 text-[10px]"></i>
        </div>
    </div>
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
                <?php foreach($members as $m): ?>
                <tr>
                    <td>
                        <div class="flex flex-col">
                            <span class="text-[12px] font-black text-slate-900 uppercase tracking-tight mb-1"><?= $m['name'] ?></span>
                            <div class="flex items-center gap-2">
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-[0.1em]"><?= $m['id'] ?></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-slate-50 flex items-center justify-center text-slate-300 border border-slate-100">
                                <?php if($m['cat'] == 'Licensed'): ?>
                                    <i class="fa-solid fa-certificate text-[10px]"></i>
                                <?php elseif($m['cat'] == 'Student'): ?>
                                    <i class="fa-solid fa-graduation-cap text-[10px]"></i>
                                <?php else: ?>
                                    <i class="fa-solid fa-user-tie text-[10px]"></i>
                                <?php endif; ?>
                            </div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest"><?= $m['cat'] ?></span>
                        </div>
                    </td>
                    <td>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest"><?= $m['coa'] ?></span>
                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Official Registry</span>
                        </div>
                    </td>
                    <td>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest"><?= $m['joined'] ?></span>
                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Enrollment</span>
                        </div>
                    </td>
                    <td>
                        <?php 
                        $statusClass = 'bg-slate-100 text-slate-500';
                        if($m['status'] == 'Active') $statusClass = 'bg-green-50 text-green-600 border-green-100';
                        if($m['status'] == 'Pending') $statusClass = 'bg-amber-50 text-amber-600 border-amber-100';
                        if($m['status'] == 'Expired') $statusClass = 'bg-red-50 text-red-600 border-red-100';
                        ?>
                        <span class="px-3 py-1 text-[7px] font-black uppercase tracking-widest rounded border <?= $statusClass ?>">
                            <?= $m['status'] ?>
                        </span>
                    </td>
                    <td>
                        <div class="flex justify-end gap-2">
                            <button class="action-node" title="View Profile"><i class="fa-solid fa-id-card-clip text-[11px]"></i></button>
                            <button class="action-node" title="Edit Credentials"><i class="fa-solid fa-pen-to-square text-[11px]"></i></button>
                            <button class="action-node hover:!bg-red-500 hover:!border-red-500" title="Suspend Node"><i class="fa-solid fa-ban text-[11px]"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Table Footer / Pagination -->
    <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
        <span class="text-[8px] font-black uppercase tracking-widest text-slate-400 italic">Chronicle Node: Accessing Page 01 // Total 524 Architects Loaded</span>
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