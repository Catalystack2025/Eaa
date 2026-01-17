<?php
/* =========================================================
   admin/manage_vendors.php — VENDOR DIRECTORY LEDGER
   ✅ Role: Administrator / Vendor Management
   ✅ Redesigned Premium Technical Table
   ✅ Exclusive Montserrat Typography
   ✅ High-density Technical Grid for Vendor Data
   ✅ Standardized 5px Radius & Smoke Grey Palette
   ========================================================= */

session_start();

$pageTitle = 'Vendor Directory | EAA Root';

// Mock Vendor Data for the Ledger
$vendors = [
    ['id' => 'EAA-VND-102', 'company' => 'Erode Glass Works Ltd', 'cat' => 'Glass & Glazing', 'contact' => 'sales@erodeglass.com', 'marquee' => 'Active', 'status' => 'Verified', 'joined' => '10 Jan 2024'],
    ['id' => 'EAA-VND-105', 'company' => 'Steel India Solutions', 'cat' => 'Structural Steel', 'contact' => 'info@steelindia.com', 'marquee' => 'Active', 'status' => 'Verified', 'joined' => '15 Mar 2024'],
    ['id' => 'EAA-VND-201', 'company' => 'Premium Stones Erode', 'cat' => 'Flooring & Tiles', 'contact' => 'contact@pstones.in', 'marquee' => 'Inactive', 'status' => 'Pending', 'joined' => '12 Jan 2026'],
    ['id' => 'EAA-VND-088', 'company' => 'Alu-Systems India', 'cat' => 'Cladding Systems', 'contact' => 'ops@alusystems.in', 'marquee' => 'Active', 'status' => 'Verified', 'joined' => '22 Jun 2023'],
    ['id' => 'EAA-VND-312', 'company' => 'Eco-Paint Masters', 'cat' => 'Finishes & Paints', 'contact' => 'support@ecopaints.com', 'marquee' => 'Inactive', 'status' => 'Suspended', 'joined' => '05 Aug 2024'],
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

    .marquee-status {
        background: #f1f5f9;
        padding: 4px 10px;
        border-radius: 3px;
        font-size: 8px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        border: 1px solid #e2e8f0;
    }
    
    .marquee-status.active {
        background: #eff6ff;
        color: #2563eb;
        border-color: #dbeafe;
    }
</style>

<!-- DIRECTORY HEADER & STATS -->
<div class="mb-12 reveal active">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-10">
        <div>
            <span class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-400 mb-2 block">Database Node: REG_VENDOR</span>
            <h2 class="font-druk text-3xl md:text-5xl text-slate-900 uppercase">Vendor <span class="text-slate-400 italic">Directory</span></h2>
        </div>
        <div class="flex gap-4">
            <div class="px-8 py-4 bg-white border border-slate-200 eaa-radius flex flex-col justify-center shadow-sm">
                <span class="text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Active Marquee</span>
                <span class="text-xl font-black text-blue-600">42</span>
            </div>
            <div class="px-8 py-4 bg-white border border-slate-200 eaa-radius flex flex-col justify-center shadow-sm">
                <span class="text-[7px] font-black text-slate-400 uppercase tracking-widest mb-1">Awaiting Audit</span>
                <span class="text-xl font-black text-amber-600">08</span>
            </div>
            <button class="px-10 py-4 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest eaa-radius shadow-2xl hover:bg-slate-700 transition-all">+ Register Vendor</button>
        </div>
    </div>

    <!-- FILTERING BAR -->
    <div class="p-4 bg-white border border-slate-100 eaa-radius flex flex-col lg:flex-row gap-4 justify-between items-center shadow-sm">
        <div class="flex items-center gap-1 overflow-x-auto no-scrollbar w-full lg:w-auto">
            <button class="px-6 py-2.5 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest eaa-radius">All Vendors</button>
            <button class="px-6 py-2.5 text-slate-400 hover:text-slate-900 text-[9px] font-black uppercase tracking-widest transition-all">Glass</button>
            <button class="px-6 py-2.5 text-slate-400 hover:text-slate-900 text-[9px] font-black uppercase tracking-widest transition-all">Steel</button>
            <button class="px-6 py-2.5 text-slate-400 hover:text-slate-900 text-[9px] font-black uppercase tracking-widest transition-all">Flooring</button>
            <div class="w-px h-4 bg-slate-100 mx-2"></div>
            <button class="px-6 py-2.5 text-blue-600 font-black text-[9px] uppercase tracking-widest">Marquee Only</button>
        </div>
        
        <div class="relative w-full lg:w-96">
            <input type="text" placeholder="FILTER BY COMPANY, CATEGORY, OR REF_ID..." class="w-full bg-slate-50 border border-slate-100 eaa-radius px-6 py-3.5 text-[9px] font-bold uppercase tracking-widest outline-none focus:border-slate-400 transition-all">
            <i class="fa-solid fa-magnifying-glass absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 text-[10px]"></i>
        </div>
    </div>
</div>

<!-- REDESIGNED VENDOR LEDGER TABLE -->
<div class="ledger-table-container reveal active" style="transition-delay: 100ms;">
    <div class="overflow-x-auto">
        <table class="eaa-table">
            <thead>
                <tr>
                    <th>Company / Vendor ID</th>
                    <th>Material Category</th>
                    <th>Contact Node</th>
                    <th>Marquee</th>
                    <th>Status</th>
                    <th class="text-right">Operations</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($vendors as $v): ?>
                <tr>
                    <td>
                        <div class="flex flex-col">
                            <span class="text-[12px] font-black text-slate-900 uppercase tracking-tight mb-1"><?= $v['company'] ?></span>
                            <div class="flex items-center gap-2">
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-[0.1em]"><?= $v['id'] ?></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-slate-50 flex items-center justify-center text-slate-300 border border-slate-100">
                                <i class="fa-solid fa-layer-group text-[10px]"></i>
                            </div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest"><?= $v['cat'] ?></span>
                        </div>
                    </td>
                    <td>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-slate-600 lowercase tracking-widest"><?= $v['contact'] ?></span>
                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Official Sync</span>
                        </div>
                    </td>
                    <td>
                        <div class="inline-flex">
                            <span class="marquee-status <?= $v['marquee'] == 'Active' ? 'active' : '' ?>">
                                <?= $v['marquee'] ?>
                            </span>
                        </div>
                    </td>
                    <td>
                        <?php 
                        $statusClass = 'bg-slate-100 text-slate-500';
                        if($v['status'] == 'Verified') $statusClass = 'bg-green-50 text-green-600 border-green-100';
                        if($v['status'] == 'Pending') $statusClass = 'bg-amber-50 text-amber-600 border-amber-100';
                        if($v['status'] == 'Suspended') $statusClass = 'bg-red-50 text-red-600 border-red-100';
                        ?>
                        <span class="px-3 py-1 text-[7px] font-black uppercase tracking-widest rounded border <?= $statusClass ?>">
                            <?= $v['status'] ?>
                        </span>
                    </td>
                    <td>
                        <div class="flex justify-end gap-2">
                            <button class="action-node hover:!text-blue-500" title="Toggle Marquee"><i class="fa-solid fa-bullhorn text-[11px]"></i></button>
                            <button class="action-node" title="Modify Catalog"><i class="fa-solid fa-boxes-stacked text-[11px]"></i></button>
                            <button class="action-node hover:!bg-red-500 hover:!border-red-500" title="Remove Entry"><i class="fa-solid fa-trash-can text-[11px]"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Table Footer / Pagination -->
    <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
        <span class="text-[8px] font-black uppercase tracking-widest text-slate-400 italic">Chronicle Node: Accessing Page 01 // Total 86 Verified Vendors</span>
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