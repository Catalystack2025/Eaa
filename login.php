<?php
/* =========================================================
   login.php — UNIFIED AUTHORIZATION TERMINAL
   ✅ Shared login for Member and Vendor
   ✅ Premium Split-Layout Design (Visual + Form)
   ✅ Exclusive Montserrat Typography
   ✅ Standardized 5px Radius Throughout
   ✅ Dynamic Role Switcher (Architect/Vendor)
   ✅ Architectural Ledger Visual Style
   ========================================================= */

require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';

start_session();

$loginError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $loginError = 'Authorization failed: Email and passcode are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $loginError = 'Authorization failed: Enter a valid email address.';
    } else {
        $user = find_user_by_email($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $loginError = 'Authorization failed: Invalid credentials provided.';
        } else {
            login_user($user);

            if ($user['role'] === 'admin') {
                header('Location: admin/dashboard.php');
                exit;
            }

            header('Location: accountpage.php');
            exit;
        }
    }
}

$pageTitle = 'Login Portal | EAA';
require_once __DIR__ . "/partials/header.php";
?>

<style>
    :root {
        --eaa-smoke: #475569;
        --eaa-border: #e2e8f0;
        --eaa-radius: 5px;
        --eaa-accent: #1e293b;
    }

    body {
        background-color: #ffffff;
        color: #1e293b;
        font-family: 'Montserrat', sans-serif;
    }

    .font-druk {
        font-family: 'Montserrat', sans-serif !important;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: -0.04em;
        line-height: 0.85;
    }

    .eaa-radius { border-radius: var(--eaa-radius) !important; }

    /* Split Screen Layout */
    .auth-wrapper {
        display: flex;
        min-height: 100vh;
        width: 100%;
    }

    /* Left Side: Visual Narrative */
    .auth-visual {
        flex: 1.2;
        position: relative;
        background-color: #0f172a;
        overflow: hidden;
        display: none;
    }

    @media (min-width: 1024px) {
        .auth-visual { display: block; }
    }

    .visual-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.4;
        filter: grayscale(100%) contrast(1.2);
        transition: transform 10s linear;
    }

    .auth-visual:hover .visual-img {
        transform: scale(1.1);
    }

    .visual-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(45deg, rgba(15, 23, 42, 0.9) 0%, transparent 100%);
        z-index: 2;
    }

    .visual-content {
        position: relative;
        z-index: 10;
        height: 100%;
        padding: 80px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Right Side: Form Terminal */
    .auth-terminal {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        background-color: #f8fafc;
        position: relative;
    }

    .blueprint-grid {
        background-image: linear-gradient(rgba(71, 85, 105, 0.05) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(71, 85, 105, 0.05) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    /* Portal Switcher */
    .role-switcher {
        display: flex;
        gap: 2px;
        background: #f1f5f9;
        padding: 4px;
        border-radius: var(--eaa-radius);
        margin-bottom: 40px;
        border: 1px solid var(--eaa-border);
    }

    .role-tab {
        flex: 1;
        padding: 14px 10px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #94a3b8;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 2px;
    }

    .role-tab.active {
        background: white;
        color: var(--eaa-accent);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    /* Form Elements */
    .login-container {
        width: 100%;
        max-width: 420px;
    }

    .tech-input {
        width: 100%;
        background: #ffffff;
        border: 1px solid var(--eaa-border);
        border-radius: var(--eaa-radius);
        padding: 16px 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--eaa-accent);
        outline: none;
        transition: all 0.3s ease;
    }

    .tech-input:focus {
        border-color: var(--eaa-smoke);
        box-shadow: 0 0 0 1px var(--eaa-smoke);
    }

    .tech-label {
        font-size: 8px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: #94a3b8;
        display: block;
        margin-bottom: 8px;
    }

    /* Status Annotations */
    .status-alert {
        padding: 15px;
        border-radius: 2px;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 30px;
        border-left: 3px solid;
    }
    .alert-error { background: #fee2e2; color: #b91c1c; border-color: #b91c1c; }
    .alert-success { background: #dcfce7; color: #15803d; border-color: #15803d; }

    /* Technical Decals */
    .tech-decal {
        position: absolute;
        font-size: 7px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.4em;
        color: #cbd5e1;
        pointer-events: none;
    }
</style>

<main class="auth-wrapper overflow-hidden">
    
    <!-- LEFT SIDE: ARCHITECTURAL NARRATIVE -->
    <section class="auth-visual">
        <img src="https://images.unsplash.com/photo-1486718448742-163732cd1544?w=1600&q=80" class="visual-img" alt="Architectural Texture">
        <div class="visual-overlay"></div>
        
        <div class="visual-content">
            <div>
                <img src="public/EAA_logo.png" class="h-16 mb-12 brightness-200" alt="EAA Logo">
                <span class="text-[10px] font-black uppercase tracking-[0.6em] text-white/40 block mb-6">Established 1985</span>
                <h2 class="font-druk text-6xl text-white leading-none">
                    Designing <br><span class="text-slate-500">Integrity.</span>
                </h2>
            </div>
            
            <div class="max-w-md">
                <p class="text-xs text-white/60 font-medium uppercase tracking-widest leading-loose text-justify mb-8">
                    Access the professional terminal of the Erode Architect Association. A collective focused on sustainable regional urbanism and technical design excellence.
                </p>
                <div class="h-px w-24 bg-white/20"></div>
                <span class="text-[8px] font-black uppercase tracking-[0.4em] text-white/40 mt-8 block">Member of the Council of Architects, India.</span>
            </div>
        </div>
    </section>

    <!-- RIGHT SIDE: AUTHORIZATION TERMINAL -->
    <section class="auth-terminal">
        <div class="absolute inset-0 blueprint-grid opacity-20 pointer-events-none"></div>
        
        <!-- Technical Decals -->
        <div class="tech-decal top-10 right-10">COORD_REF: 11.34N 77.71E</div>
        <div class="tech-decal bottom-10 left-10">AUTH_GATE // V 1.0.4</div>

        <div class="login-container relative z-10">
            
            <div class="mb-12 text-center lg:text-left">
                <span class="text-[8px] font-black uppercase tracking-[0.5em] text-slate-400 block border-l-2 border-slate-900 pl-4 mb-4">Secure Terminal Login</span>
                <h3 class="font-druk text-3xl md:text-4xl text-slate-900">Account <span class="text-slate-400 italic">Access</span></h3>
            </div>

            <!-- Dynamic Role Switcher -->
            <div class="role-switcher">
                <div class="role-tab active" onclick="switchRole(this, 'member')">Architect Member</div>
                <div class="role-tab" onclick="switchRole(this, 'vendor')">Material Vendor</div>
            </div>

            <!-- Status Messages -->
            <?php if ($loginError): ?>
                <div class="status-alert alert-error"><?= e($loginError) ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-6">
                <input type="hidden" name="role" id="user_role" value="member">

                <div class="space-y-2">
                    <label class="tech-label" id="identity-label">Email Address</label>
                    <input type="email" name="email" class="tech-input" placeholder="Enter email" required>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label class="tech-label">Passcode</label>
                        <a href="password_reset_request.php" class="text-[8px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors">Forgot Password?</a>
                    </div>
                    <input type="password" name="password" class="tech-input" placeholder="••••••••" required>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest eaa-radius shadow-xl hover:bg-slate-700 transition-all flex items-center justify-center gap-4">
                        <span>Authorize Access</span>
                        <i class="fa-solid fa-arrow-right-long text-[8px]"></i>
                    </button>
                </div>

                <div class="pt-10 mt-10 border-t border-slate-200 text-center">
                    <span class="text-[8px] font-black uppercase tracking-[0.2em] text-slate-400 block mb-4">New to the Association?</span>
                    <a href="join.php" class="text-[10px] font-black uppercase tracking-widest text-slate-900 border-b-2 border-slate-900 pb-1 hover:text-slate-600 hover:border-slate-600 transition-all">Apply for Membership</a>
                </div>
            </form>
        </div>
    </section>
</main>

<?php require_once __DIR__ . "/partials/footer.php"; ?>

<script>
    function switchRole(el, role) {
        // Update Tabs
        document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
        el.classList.add('active');

        // Update Hidden Input
        document.getElementById('user_role').value = role;

        // Visual Context Update
        const label = document.getElementById('identity-label');
        if(role === 'member') {
            label.innerText = 'Member Email';
        } else if(role === 'vendor') {
            label.innerText = 'Vendor Email';
        }

        // Animation Feedback
        const container = document.querySelector('.login-container');
        container.style.opacity = '0';
        setTimeout(() => {
            container.style.opacity = '1';
        }, 200);
    }
</script>
