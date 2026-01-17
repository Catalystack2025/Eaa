<?php
/* =========================================================
   admin/login.php — COUNCIL ADMINISTRATIVE TERMINAL
   ✅ Completely Self-Contained (No Header/Footer includes)
   ✅ Restricted Access for Association Admins
   ✅ Premium Split-Layout Design (High-Security Tone)
   ✅ Exclusive Montserrat Typography
   ✅ Standardized 5px Radius Throughout
   ✅ Technical "Root" Ledger Visual Style
   ========================================================= */

require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../config/db.php';

start_session();

$adminError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $adminError = 'System Alert: Administrative credentials are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $adminError = 'System Alert: Provide a valid admin email address.';
    } else {
        $user = find_user_by_email($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $adminError = 'System Alert: Invalid administrative credentials.';
        } elseif ($user['role'] !== 'admin' || $user['status'] !== 'active') {
            $adminError = 'System Alert: Unauthorized access detected. IP Logged.';
        } else {
            login_user($user);
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Terminal | EAA Council</title>
    
    <!-- Google Fonts: Montserrat Only -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        :root {
            --eaa-smoke: #475569;
            --eaa-border: #e2e8f0;
            --eaa-radius: 5px;
            --eaa-accent: #0f172a; 
        }

        body {
            background-color: #ffffff;
            color: #1e293b;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
        }

        .font-druk {
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -0.04em;
            line-height: 0.85;
        }

        .eaa-radius { border-radius: var(--eaa-radius) !important; }

        /* Admin Split Screen */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Left Panel: Security & Brand */
        .admin-visual {
            flex: 1.1;
            position: relative;
            background-color: #020617;
            overflow: hidden;
            display: none;
        }

        @media (min-width: 1024px) {
            .admin-visual { display: block; }
        }

        .visual-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.25;
            filter: grayscale(100%) contrast(1.5);
        }

        .visual-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, #020617 0%, transparent 100%);
            z-index: 2;
        }

        .visual-content {
            position: relative;
            z-index: 10;
            height: 100%;
            padding: 100px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Right Panel: Admin Terminal */
        .admin-terminal {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background-color: #f8fafc;
            position: relative;
        }

        .blueprint-grid {
            background-image: linear-gradient(rgba(15, 23, 42, 0.04) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(15, 23, 42, 0.04) 1px, transparent 1px);
            background-size: 25px 25px;
        }

        /* Admin Form Styling */
        .terminal-container {
            width: 100%;
            max-width: 400px;
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
            border-color: var(--eaa-accent);
            box-shadow: 0 0 0 1px var(--eaa-accent);
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

        /* Security Decals */
        .security-tag {
            font-size: 7px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.3em;
            color: var(--eaa-smoke);
            opacity: 0.4;
            position: absolute;
        }

        .alert-banner {
            background: #fee2e2;
            color: #b91c1c;
            padding: 15px;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            border-radius: 2px;
            margin-bottom: 30px;
            border-left: 4px solid #b91c1c;
        }

        .btn-admin {
            width: 100%;
            padding: 20px;
            background: #020617;
            color: #ffffff;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            border-radius: var(--eaa-radius);
            transition: all 0.4s;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border: none;
            cursor: pointer;
        }

        .btn-admin:hover {
            background: #1e293b;
            transform: translateY(-2px);
        }

        .reveal { opacity: 0; transform: translateY(20px); transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1); }
        .reveal.active { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body class="antialiased">

<main class="admin-wrapper overflow-hidden">
    
    <!-- LEFT PANEL: SECURITY NARRATIVE -->
    <section class="admin-visual">
        <img src="https://images.unsplash.com/photo-1517581177682-a085bb7ffb15?w=1600&q=80" class="visual-img" alt="Brutalist Architecture">
        <div class="visual-overlay"></div>
        
        <div class="visual-content">
            <div>
                <img src="../public/EAA_logo.png" class="h-16 mb-12 brightness-200 contrast-125" alt="EAA Logo">
                <span class="text-[10px] font-black uppercase tracking-[0.6em] text-white/30 block mb-6">Council Management</span>
                <h2 class="font-druk text-6xl text-white leading-none">
                    EAA Admin <br><span class="text-slate-600">Terminal.</span>
                </h2>
            </div>
            
            <div class="max-w-md">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-2 h-2 bg-red-500 rounded-full animate-ping"></div>
                    <span class="text-[8px] font-black uppercase tracking-[0.4em] text-white/50">Restricted Entry Node</span>
                </div>
                <p class="text-[10px] text-white/40 font-bold uppercase tracking-widest leading-loose text-justify">
                    This portal is strictly for EAA council administrators. Unauthorized attempts to bypass authentication protocols will be logged and reported to the Association Board.
                </p>
            </div>
        </div>
    </section>

    <!-- RIGHT PANEL: AUTHENTICATION TERMINAL -->
    <section class="admin-terminal">
        <div class="absolute inset-0 blueprint-grid opacity-30 pointer-events-none"></div>
        
        <!-- Metadata Decals -->
        <span class="security-tag top-10 right-10">AUTH_LEVEL: ROOT_01</span>
        <span class="security-tag bottom-10 left-10">ENCRYPTION: AES_256_ACTIVE</span>

        <div class="terminal-container relative z-10">
            
            <div class="mb-12 reveal active">
                <span class="text-[8px] font-black uppercase tracking-[0.5em] text-slate-400 block border-l-2 border-slate-900 pl-4 mb-4">Command Identity Verification</span>
                <h3 class="font-druk text-3xl text-slate-900 uppercase">Administrator <span class="text-slate-400 italic">Login</span></h3>
            </div>

            <!-- Error Banner -->
            <?php if ($adminError): ?>
                <div class="alert-banner reveal active">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                    <?= e($adminError) ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-8 reveal active">
                
                <div class="space-y-2">
                    <label class="tech-label">Admin Email</label>
                    <input type="email" name="email" class="tech-input" placeholder="Enter admin email" required autofocus>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label class="tech-label">Security Passcode</label>
                        <a href="#" class="text-[7px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-all">Emergency Reset</a>
                    </div>
                    <input type="password" name="password" class="tech-input" placeholder="••••••••" required>
                </div>

                <div class="pt-6">
                    <button type="submit" class="btn-admin flex items-center justify-center gap-4 group">
                        <span>Initialize Terminal</span>
                        <i class="fa-solid fa-key text-[9px] opacity-40 group-hover:opacity-100 transition-all"></i>
                    </button>
                </div>

                <div class="pt-10 mt-10 border-t border-slate-200 text-center">
                    <a href="../login.php" class="text-[9px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-all">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Return to Member Portal
                    </a>
                </div>
            </form>
        </div>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Visual parallax effect for Admin imagery
        const visual = document.querySelector('.admin-visual');
        const img = document.querySelector('.visual-img');
        
        if(visual && img) {
            visual.addEventListener('mousemove', (e) => {
                const moveX = (e.pageX * -1 / 50);
                const moveY = (e.pageY * -1 / 50);
                img.style.transform = `scale(1.1) translate(${moveX}px, ${moveY}px)`;
            });
        }
        // Trigger reveal animations
        setTimeout(() => {
            document.querySelectorAll('.reveal').forEach(el => el.classList.add('active'));
        }, 100);
    });
</script>

</body>
</html>
