<?php
/**
 * EAA Modular Header - Optimized Version
 * ✅ Standardized 5px radius.
 * ✅ Integrated Mobile Navigation Drawer (Light Theme).
 * ✅ Fixed Toggle Logic (Hamburger Button).
 * ✅ Reduced Logo & Text Scaling.
 * ✅ Added Mobile-Visible Sign In Button.
 * ✅ Forced solid background for Home (testing.php).
 */

$current_page = basename($_SERVER['PHP_SELF']);
$home_pages = ['testing.php', 'index.php'];
$is_home = in_array($current_page, $home_pages);
$is_logged_in = function_exists('is_logged_in') && is_logged_in();

if (!function_exists('is_active')) {
    function is_active($page) {
        global $current_page;
        return ($current_page == $page) ? 'active' : '';
    }
}
?>

<style>
    :root {
        --app-radius: 5px;
        --background: 210 25% 98%;
        --foreground: 220 20% 18%;
        --primary: 197 13% 43%;
        --border: 210 12% 86%;
    }

    .dark {
        --background: 220 18% 10%;
        --foreground: 210 20% 92%;
        --primary: 197 14% 55%;
        --border: 220 12% 24%;
    }

    .eaa-radius { border-radius: var(--app-radius) !important; }

    #main-header { 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        padding: 0.6rem 0;
        width: 100%;
        z-index: 1000;
    }

    /* Forced Solid State for Home/Scrolled */
    #main-header.scrolled, 
    #main-header.is-home-header { 
        background-color: white !important; 
        backdrop-filter: blur(12px); 
        box-shadow: 0 4px 20px rgba(0,0,0,0.06); 
        padding: 0.4rem 0; 
        border-bottom: 1px solid #e2e8f0;
    }
    
    .dark #main-header.scrolled,
    .dark #main-header.is-home-header {
        background-color: #0f172a !important;
        border-bottom: 1px solid #1e293b;
    }

    .nav-link {
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #64748b;
        transition: all 0.2s ease;
        position: relative;
        padding: 0.4rem 0;
    }

    .nav-link:hover, .nav-link.active { color: #1e293b; }
    .nav-link::after {
        content: ''; position: absolute; bottom: -2px; left: 0; width: 0; height: 2px;
        background: #1e293b; transition: width 0.3s ease;
    }
    .nav-link:hover::after, .nav-link.active::after { width: 100%; }

    .auth-button {
        transition: all 0.2s ease;
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--app-radius);
    }

    /* Mobile Menu Styling - LIGHT THEME */
    #mobile-drawer {
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(15px);
        z-index: 2000;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 1.5rem;
        transform: translateY(-100%);
        transition: transform 0.5s cubic-bezier(0.85, 0, 0.15, 1);
        pointer-events: none;
    }

    #mobile-drawer.active {
        transform: translateY(0);
        pointer-events: auto;
    }

    .mobile-link {
        font-family: 'Inter', sans-serif;
        font-size: 1.2rem;
        font-weight: 800;
        color: #0f172a;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: all 0.3s ease;
    }

    .mobile-link:hover { color: #64748b; transform: scale(1.05); }

    .content-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
</style>

<!-- MOBILE MENU OVERLAY -->
<div id="mobile-drawer">
    <button id="close-drawer" class="absolute top-8 right-8 text-slate-900 p-4">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2.5"/></svg>
    </button>
    
    <a href="testing.php" class="mobile-link <?= is_active('testing.php') ?>">Home</a>
    <a href="about.php" class="mobile-link <?= is_active('about.php') ?>">About</a>
    <a href="teams.php" class="mobile-link <?= is_active('teams.php') ?>">Teams</a>
    <a href="blog.php" class="mobile-link <?= is_active('blog.php') ?>">Journal</a>
    <a href="events.php" class="mobile-link <?= is_active('events.php') ?>">Events</a>
    <a href="career.php" class="mobile-link <?= is_active('career.php') ?>">Career</a>
    <a href="vendors.php" class="mobile-link <?= is_active('vendors.php') ?>">Connect</a>
    
    <div class="mt-8 flex flex-col gap-3 w-full px-12">
        <?php if ($is_logged_in): ?>
            <a href="accountpage.php" class="w-full py-4 bg-slate-900 text-white text-center text-[10px] font-black uppercase tracking-widest eaa-radius shadow-lg">My Account</a>
        <?php else: ?>
            <a href="login.php" class="w-full py-4 border border-slate-200 text-slate-900 text-center text-[10px] font-black uppercase tracking-widest eaa-radius">Sign In</a>
            <a href="join.php" class="w-full py-4 bg-slate-900 text-white text-center text-[10px] font-black uppercase tracking-widest eaa-radius shadow-lg">Join the Guild</a>
        <?php endif; ?>
    </div>
</div>

<header id="main-header" class="fixed top-0 left-0 right-0 <?= $is_home ? 'scrolled is-home-header' : 'bg-transparent' ?>">
    <nav class="content-container flex items-center justify-between">
        
        <!-- LOGO -->
        <a href="testing.php" class="flex items-center group shrink-0">
            <img src="/logo.svg" alt="EAA" class="w-auto relative z-10" style="height:60px" onerror="this.src='public/EAA_logo.png';">
        </a>

        <!-- DESKTOP MENU -->
        <div class="hidden lg:flex items-center gap-6 xl:gap-8">
            <a href="testing.php" class="nav-link <?= is_active('testing.php') ?>">Home</a>
            <a href="about.php" class="nav-link <?= is_active('about.php') ?>">About</a>
            <a href="teams.php" class="nav-link <?= is_active('teams.php') ?>">Teams</a>
            <a href="blog.php" class="nav-link <?= is_active('blog.php') ?>">Blogs</a>
            <a href="events.php" class="nav-link <?= is_active('events.php') ?>">Event</a>
            <a href="career.php" class="nav-link <?= is_active('career.php') ?>">Career</a>
            <a href="vendors.php" class="nav-link <?= is_active('vendors.php') ?>">Connect</a>
            <?php if ($is_logged_in): ?>
                <a href="accountpage.php" class="nav-link <?= is_active('accountpage.php') ?>">My Account</a>
            <?php endif; ?>
        </div>

        <!-- AUTH & MOBILE TOGGLE -->
        <div class="flex items-center gap-3 lg:gap-4">
            <!-- Desktop Auth -->
            <div class="hidden lg:flex items-center gap-2 border-l border-slate-200 pl-4">
                <?php if ($is_logged_in): ?>
                    <a href="accountpage.php" class="auth-button bg-slate-900 text-white px-5 py-2 text-[9px] font-black uppercase tracking-widest hover:bg-slate-900 shadow-sm transition-all">My Account</a>
                <?php else: ?>
                    <a href="login.php" class="auth-button border border-slate-200 px-4 py-2 text-[9px] font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50">Sign In</a>
                    <a href="join.php" class="auth-button bg-slate-800 text-white px-5 py-2 text-[9px] font-black uppercase tracking-widest hover:bg-slate-900 shadow-sm transition-all">Join Us</a>
                <?php endif; ?>
            </div>

            <!-- Mobile Sign In -->
            <?php if ($is_logged_in): ?>
                <a href="accountpage.php" class="lg:hidden px-3 py-1.5 bg-slate-900 text-white text-[8px] font-black uppercase tracking-widest eaa-radius">My Account</a>
            <?php else: ?>
                <a href="login.php" class="lg:hidden px-3 py-1.5 border border-slate-200 text-[8px] font-black uppercase tracking-widest text-slate-600 eaa-radius">Sign In</a>
            <?php endif; ?>

            <!-- MOBILE HAMBURGER BUTTON -->
            <button id="open-drawer" class="lg:hidden p-2 text-slate-800" aria-label="Open Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16m-7 6h7" stroke-width="2.2" stroke-linecap="round"/></svg>
            </button>
        </div>
    </nav>
</header>

<script>
    (function() {
        const header = document.getElementById('main-header');
        const openBtn = document.getElementById('open-drawer');
        const closeBtn = document.getElementById('close-drawer');
        const drawer = document.getElementById('mobile-drawer');

        // Scroll Logic for Header
        window.addEventListener('scroll', () => {
            const isHome = <?= json_encode($is_home) ?>;
            if (header && !isHome) {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                    header.classList.remove('bg-transparent');
                } else {
                    header.classList.remove('scrolled');
                    header.classList.add('bg-transparent');
                }
            }
        });

        // Mobile Drawer Logic
        if (openBtn && closeBtn && drawer) {
            openBtn.addEventListener('click', () => {
                drawer.classList.add('active');
                document.body.style.overflow = 'hidden';
            });

            closeBtn.addEventListener('click', () => {
                drawer.classList.remove('active');
                document.body.style.overflow = '';
            });

            // Close on link click
            drawer.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    drawer.classList.remove('active');
                    document.body.style.overflow = '';
                });
            });
        }
    })();
</script>
