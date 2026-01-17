<?php
/* =========================================================
   admin/partials/sidebar.php — COMMAND SIDEBAR
   ✅ Restricted Council Access Style
   ✅ Montserrat & 5px Radius
   ========================================================= */
?>
<aside class="admin-sidebar">
    <div class="mb-12">
        <div class="flex items-center gap-3 mb-2">
            <img src="../public/EAA_logo.png" class="h-8 brightness-200" alt="EAA" onerror="this.src='https://via.placeholder.com/32?text=E'">
            <span class="font-druk text-lg text-white">Council</span>
        </div>
        <span class="text-[7px] font-black uppercase tracking-[0.4em] text-white/30">Root Access Terminal // 1.0</span>
    </div>

    <nav class="flex-1">
        <span class="tech-label text-white/20 mb-4 px-4">Core Management</span>
        <a href="dashboard.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-gauge-high w-5 text-center"></i> Console
        </a>
        <a href="manage_members.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'manage_members.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-users w-5 text-center"></i> Architect Registry
        </a>
        <a href="manage_vendors.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'manage_vendors.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-truck-ramp-box w-5 text-center"></i> Vendor Directory
        </a>
        <a href="manage_sponsors.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'manage_sponsors.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-handshake-angle w-5 text-center"></i> Sponsor Approvals
        </a>
        <a href="manage_events.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'manage_events.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-calendar-days w-5 text-center"></i> Event Planner
        </a>

        <div class="mt-10 pt-10 border-t border-white/5">
            <span class="tech-label text-white/20 mb-4 px-4">Approvals</span>
            <a href="approvals_journal.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'approvals_journal.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-file-signature w-5 text-center"></i> Manuscripts
            </a>
            <a href="approvals_jobs.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'approvals_jobs.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-briefcase w-5 text-center"></i> Recruitment
            </a>
        </div>

        <div class="mt-10 pt-10 border-t border-white/5">
            <span class="tech-label text-white/20 mb-4 px-4">System</span>
            <a href="settings.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-gears w-5 text-center"></i> Terminal Config
            </a>
        </div>
    </nav>

    <div class="mt-auto">
        <a href="../auth/logout.php" class="text-[9px] font-black uppercase tracking-widest text-red-400 hover:text-red-300 transition-colors flex items-center gap-3 px-4">
            <i class="fa-solid fa-power-off"></i> Terminate Admin Session
        </a>
    </div>
</aside>
