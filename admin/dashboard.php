<?php
/* =========================================================
   admin/dashboard.php — COUNCIL ADMINISTRATIVE CONTROL CENTER
   ✅ Updated to use common partials
   ========================================================= */

session_start();

// Mock Admin Data
$admin = [
    'name' => 'Admin Node 01',
    'id' => 'EAA-ROOT-X1',
    'access_level' => 'Full Administrative',
    'uptime' => '14 Days, 6 Hours'
];

// Mock Analytics for Admin
$stats = [
    'members' => 524,
    'vendors' => 86,
    'events' => 12,
    'registrations' => 142,
    'pending_approvals' => 18
];

$pageTitle = 'Admin Console | EAA Root';

// LOAD HEADER & SIDEBAR
require_once 'partials/header.php';
?>

<!-- ADMIN METRICS GRID (Total Overview) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
    <div class="metric-card reveal active">
        <span class="tech-label">Architect Members</span>
        <div class="flex items-end justify-between mt-4">
            <span class="metric-value"><?= $stats['members'] ?></span>
            <span class="px-2 py-1 bg-slate-100 text-[7px] font-black uppercase tracking-widest text-slate-500 rounded-sm">+5 This Week</span>
        </div>
    </div>
    <div class="metric-card reveal active" style="transition-delay: 100ms;">
        <span class="tech-label">Materials Vendors</span>
        <div class="flex items-end justify-between mt-4">
            <span class="metric-value"><?= $stats['vendors'] ?></span>
            <span class="px-2 py-1 bg-blue-50 text-[7px] font-black uppercase tracking-widest text-blue-500 rounded-sm">8 Verified</span>
        </div>
    </div>
    <div class="metric-card reveal active" style="transition-delay: 200ms;">
        <span class="tech-label">Regional Events</span>
        <div class="flex items-end justify-between mt-4">
            <span class="metric-value"><?= $stats['events'] ?></span>
            <span class="px-2 py-1 bg-slate-100 text-[7px] font-black uppercase tracking-widest text-slate-500 rounded-sm">Active Year</span>
        </div>
    </div>
    <div class="metric-card reveal active" style="transition-delay: 300ms;">
        <span class="tech-label">Total Registrations</span>
        <div class="flex items-end justify-between mt-4">
            <span class="metric-value"><?= $stats['registrations'] ?></span>
            <span class="px-2 py-1 bg-green-50 text-[7px] font-black uppercase tracking-widest text-green-500 rounded-sm">Live Hub</span>
        </div>
    </div>
</div>

<!-- SECONDARY COMMAND GRID -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 relative z-10">
    
    <!-- Pending Validations (Main Action Area) -->
    <div class="lg:col-span-8 space-y-8">
        <div class="metric-card reveal active" style="transition-delay: 400ms;">
            <div class="flex items-center justify-between mb-8">
                <h3 class="font-druk text-xl uppercase">Validation <span class="text-slate-400 italic">Queue</span></h3>
                <span class="text-[8px] font-black uppercase tracking-widest px-4 py-1 border border-slate-100 eaa-radius">Action Required: <?= $stats['pending_approvals'] ?></span>
            </div>
            
            <div class="space-y-4">
                <?php for($i=1; $i<=3; $i++): ?>
                <div class="flex items-center justify-between p-6 bg-slate-50 border border-slate-100 eaa-radius group hover:border-slate-300 transition-all">
                    <div class="flex items-center gap-6">
                        <div class="w-10 h-10 bg-white eaa-radius flex items-center justify-center text-slate-300 border border-slate-100"><i class="fa-solid fa-user-check text-sm"></i></div>
                        <div>
                            <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-tight">Ar. Vijay Prasath // CA/2023/1029</h4>
                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Type: Licensed Architect • Submitted 4h ago</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="px-4 py-2 border border-slate-200 text-[8px] font-black uppercase tracking-widest eaa-radius hover:bg-white transition-all">Review</button>
                        <button class="px-4 py-2 bg-slate-900 text-white text-[8px] font-black uppercase tracking-widest eaa-radius shadow-lg transition-all">Verify</button>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <!-- System Utilities (Right Sidebar) -->
    <div class="lg:col-span-4 space-y-8">
        
        <!-- System Health Card -->
        <div class="metric-card bg-slate-900 text-white reveal active" style="transition-delay: 500ms;">
            <span class="tech-label text-white/30">System Integrity</span>
            <div class="mt-8 space-y-6">
                <div class="flex justify-between items-center">
                    <span class="text-[9px] font-black uppercase tracking-widest text-white/60">Server Uptime</span>
                    <span class="text-[9px] font-bold"><?= $admin['uptime'] ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[9px] font-black uppercase tracking-widest text-white/60">Node Version</span>
                    <span class="text-[9px] font-bold">V 1.0.4 stable</span>
                </div>
                <div class="h-px bg-white/10 w-full my-4"></div>
                <button class="w-full py-4 bg-white/5 border border-white/10 text-[8px] font-black uppercase tracking-[0.2em] eaa-radius hover:bg-white/10 transition-all">Initialize Backup</button>
            </div>
        </div>

        <!-- Recent Logs -->
        <div class="metric-card reveal active" style="transition-delay: 600ms;">
            <h3 class="font-druk text-lg mb-8 uppercase">Recent <span class="text-slate-400 italic">Logs</span></h3>
            <div class="space-y-6">
                <div class="flex gap-4 border-l-2 border-slate-900 pl-4">
                    <p class="text-[9px] font-bold text-slate-500 leading-relaxed uppercase tracking-widest">
                        <span class="text-slate-900">09:12 AM:</span> Vendor "Steel India" updated marquee logo.
                    </p>
                </div>
                <div class="flex gap-4 border-l-2 border-slate-100 pl-4">
                    <p class="text-[9px] font-bold text-slate-500 leading-relaxed uppercase tracking-widest">
                        <span class="text-slate-900">08:45 AM:</span> New Architect Registration from Node_Erode_South.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

<?php 
// LOAD FOOTER
require_once 'partials/footer.php'; 
?>