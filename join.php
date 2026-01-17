<?php
/* =========================================================
   join.php — PROFESSIONAL ONBOARDING TERMINAL
   ✅ Shared registration for Architects and Vendors
   ✅ Integrated Email OTP Verification for Members
   ✅ Support for Students, Licensed, and Junior Professionals
   ✅ Role-based feedback protocols
   ✅ Exclusive Montserrat Typography & 5px Radius
   ✅ Premium Split-Layout Design
   ========================================================= */

require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/config/db.php';

start_session();

$pageTitle = 'Join the Association | EAA';
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

    /* Left Side: Brand Narrative */
    .auth-visual {
        flex: 1;
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
        opacity: 0.3;
        filter: grayscale(100%);
    }

    .visual-content {
        position: relative;
        z-index: 10;
        height: 100%;
        padding: 80px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* Right Side: Form Terminal */
    .auth-terminal {
        flex: 1.2;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 100px 40px;
        background-color: #f8fafc;
        position: relative;
        overflow-y: auto;
    }

    .blueprint-grid {
        background-image: linear-gradient(rgba(71, 85, 105, 0.05) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(71, 85, 105, 0.05) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    /* Role Switcher */
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

    /* Form Design */
    .tech-input {
        width: 100%;
        background: #ffffff;
        border: 1px solid var(--eaa-border);
        border-radius: var(--eaa-radius);
        padding: 14px 18px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--eaa-accent);
        outline: none;
        transition: all 0.3s ease;
    }

    .tech-input:disabled {
        background: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
    }

    .tech-input:focus:not(:disabled) {
        border-color: var(--eaa-smoke);
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

    /* Verification Terminal */
    .otp-terminal {
        background: #f1f5f9;
        padding: 20px;
        border-radius: var(--eaa-radius);
        border: 1px solid var(--eaa-border);
        margin-top: 10px;
    }

    .btn-verify {
        background: #1e293b;
        color: white;
        padding: 10px 20px;
        font-size: 9px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        border-radius: 2px;
        transition: all 0.3s;
    }

    /* Success Overlays */
    .completion-screen {
        display: none;
        text-align: center;
        padding: 40px 0;
    }

    .reveal { opacity: 0; transform: translateY(20px); transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1); }
    .reveal.active { opacity: 1; transform: translateY(0); }
</style>

<main class="auth-wrapper overflow-hidden">
    
    <!-- LEFT SIDE -->
    <section class="auth-visual">
        <img src="https://images.unsplash.com/photo-1487958449943-2429e8be8625?w=1600&q=80" class="visual-img" alt="Architectural Plan">
        <div class="visual-content">
            <span class="text-[10px] font-black uppercase tracking-[0.6em] text-white/40 block mb-6">Guild Onboarding</span>
            <h2 class="font-druk text-6xl text-white leading-none mb-12">
                Build your <br><span class="text-slate-500">Legacy.</span>
            </h2>
            <div class="max-w-md">
                <p class="text-xs text-white/60 font-medium uppercase tracking-widest leading-loose text-justify">
                    Join the most influential network of design professionals in the Erode region. Gain access to exclusive summits, technical journals, and regional project leads.
                </p>
            </div>
        </div>
    </section>

    <!-- RIGHT SIDE -->
    <section class="auth-terminal">
        <div class="absolute inset-0 blueprint-grid opacity-20 pointer-events-none"></div>

        <div id="main-form-container" class="w-full max-w-lg relative z-10 reveal active">
            
            <div class="mb-12">
                <span class="text-[8px] font-black uppercase tracking-[0.5em] text-slate-400 block border-l-2 border-slate-900 pl-4 mb-4">Registration Terminal</span>
                <h3 class="font-druk text-3xl md:text-4xl text-slate-900">Join the <span class="text-slate-400 italic">Council</span></h3>
            </div>

            <!-- Role Switcher -->
            <div class="role-switcher">
                <div class="role-tab active" onclick="switchForm('member')">Architect / Student</div>
                <div class="role-tab" onclick="switchForm('vendor')">Material Vendor</div>
            </div>

            <!-- ARCHITECT / STUDENT FORM -->
            <form id="member-form" onsubmit="handleFinalSubmit(event, 'member')" class="space-y-8">
                
                <div>
                    <label class="tech-label">Membership Category</label>
                    <select id="membership-cat" class="tech-input" onchange="toggleCategoryFields()">
                        <option value="licensed">Licensed Architect (COA Holder)</option>
                        <option value="professional">Architectural Professional</option>
                        <option value="student">Architecture Student</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="tech-label">Full Legal Name</label>
                        <input type="text" class="tech-input" placeholder="Name" required>
                    </div>
                    <div>
                        <label class="tech-label" id="id-label">COA Registration No.</label>
                        <input type="text" id="coa-field" class="tech-input" placeholder="CA/YYYY/XXXXX" required>
                    </div>
                </div>

                <div>
                    <label class="tech-label">Primary Email Address</label>
                    <div class="flex gap-2">
                        <input type="email" id="member-email" class="tech-input" placeholder="email@domain.com" required>
                        <button type="button" onclick="sendOTP()" id="otp-btn" class="btn-verify whitespace-nowrap">Send OTP</button>
                    </div>
                </div>

                <!-- OTP VERIFICATION UI -->
                <div id="otp-section" class="otp-terminal hidden">
                    <label class="tech-label">Enter 6-Digit Code</label>
                    <div class="flex gap-4">
                        <input type="text" id="otp-input" class="tech-input text-center tracking-[1em]" maxlength="6" placeholder="000000">
                        <button type="button" onclick="verifyOTP()" class="btn-verify">Verify</button>
                    </div>
                    <span id="otp-status" class="text-[8px] font-bold uppercase tracking-widest text-slate-400 mt-3 block italic">Waiting for verification...</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="tech-label" id="org-label">Firm / Studio Name</label>
                        <input type="text" class="tech-input" placeholder="Identity" required>
                    </div>
                    <div>
                        <label class="tech-label">Mobile Hotline</label>
                        <input type="text" class="tech-input" placeholder="+91" required>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" id="member-submit" disabled class="w-full py-5 bg-slate-200 text-slate-400 text-[10px] font-black uppercase tracking-widest eaa-radius cursor-not-allowed transition-all">
                        Complete Identity Verification First
                    </button>
                </div>
            </form>

            <!-- VENDOR FORM -->
            <form id="vendor-form" onsubmit="handleFinalSubmit(event, 'vendor')" class="space-y-8 hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="tech-label">Company Name</label>
                        <input type="text" class="tech-input" placeholder="Brand Name" required>
                    </div>
                    <div>
                        <label class="tech-label">Material Category</label>
                        <select class="tech-input">
                            <option>Glass & Glazing</option>
                            <option>Structural Steel</option>
                            <option>Flooring & Tiles</option>
                            <option>Cladding Systems</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="tech-label">Primary Contact Email</label>
                        <input type="email" class="tech-input" placeholder="sales@brand.com" required>
                    </div>
                    <div>
                        <label class="tech-label">Office Hotline</label>
                        <input type="text" class="tech-input" placeholder="+91" required>
                    </div>
                </div>

                <div>
                    <label class="tech-label">Brief Portfolio / Description</label>
                    <textarea class="tech-input min-h-[100px] normal-case" placeholder="Tell us about your products..."></textarea>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest eaa-radius shadow-xl hover:bg-slate-700 transition-all">
                        Submit Interest Application
                    </button>
                </div>
            </form>

            <div class="pt-10 mt-10 border-t border-slate-200 text-center">
                <span class="text-[8px] font-black uppercase tracking-[0.2em] text-slate-400 block mb-4">Already registered?</span>
                <a href="login.php" class="text-[10px] font-black uppercase tracking-widest text-slate-900 border-b-2 border-slate-900 pb-1">Login to Terminal</a>
            </div>
        </div>

        <!-- COMPLETION SCREENS -->
        <div id="member-success" class="completion-screen w-full max-w-sm">
            <div class="w-20 h-20 bg-slate-900 rounded-full flex items-center justify-center text-white mx-auto mb-8">
                <i class="fa-solid fa-envelope-circle-check text-2xl"></i>
            </div>
            <h3 class="font-druk text-3xl mb-4">Account <span class="text-slate-400 italic">Provisioned</span></h3>
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-widest leading-loose">
                Registration successful. Your credentials are being validated by the council. You will receive a confirmation email shortly.
            </p>
            <a href="index.php" class="inline-block mt-10 text-[9px] font-black uppercase tracking-widest border-b border-slate-900">Return to Homepage</a>
        </div>

        <div id="vendor-success" class="completion-screen w-full max-w-sm">
            <div class="w-20 h-20 bg-slate-900 rounded-full flex items-center justify-center text-white mx-auto mb-8">
                <i class="fa-solid fa-headset text-2xl"></i>
            </div>
            <h3 class="font-druk text-3xl mb-4">Protocol <span class="text-slate-400 italic">Initiated</span></h3>
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-widest leading-loose">
                Your partnership request has been logged. The EAA Technical Team will contact you for brand verification and marquee placement.
            </p>
            <a href="index.php" class="inline-block mt-10 text-[9px] font-black uppercase tracking-widest border-b border-slate-900">Return to Homepage</a>
        </div>

    </section>
</main>

<?php require_once __DIR__ . "/partials/footer.php"; ?>

<script>
    function switchForm(role) {
        const mTab = document.querySelectorAll('.role-tab')[0];
        const vTab = document.querySelectorAll('.role-tab')[1];
        const mForm = document.getElementById('member-form');
        const vForm = document.getElementById('vendor-form');

        if(role === 'member') {
            mTab.classList.add('active'); vTab.classList.remove('active');
            mForm.classList.remove('hidden'); vForm.classList.add('hidden');
        } else {
            vTab.classList.add('active'); mTab.classList.remove('active');
            vForm.classList.remove('hidden'); mForm.classList.add('hidden');
        }
    }

    function toggleCategoryFields() {
        const cat = document.getElementById('membership-cat').value;
        const coaField = document.getElementById('coa-field');
        const coaLabel = document.getElementById('id-label');
        const orgLabel = document.getElementById('org-label');

        if(cat === 'student') {
            coaField.disabled = true;
            coaField.value = "NOT APPLICABLE";
            coaField.required = false;
            coaLabel.innerText = "COA Registration No. (N/A)";
            orgLabel.innerText = "Educational Institution";
        } else if (cat === 'professional') {
            coaField.disabled = false;
            coaField.value = "";
            coaField.required = false;
            coaField.placeholder = "Optional for Professionals";
            coaLabel.innerText = "COA Registration No. (Optional)";
            orgLabel.innerText = "Firm / Studio Name";
        } else {
            coaField.disabled = false;
            coaField.value = "";
            coaField.required = true;
            coaField.placeholder = "CA/YYYY/XXXXX";
            coaLabel.innerText = "COA Registration No.";
            orgLabel.innerText = "Firm / Studio Name";
        }
    }

    // OTP SIMULATION LOGIC
    let isVerified = false;

    function sendOTP() {
        const email = document.getElementById('member-email').value;
        if(!email) return alert("Please enter a valid email address.");
        
        document.getElementById('otp-section').classList.remove('hidden');
        document.getElementById('otp-btn').innerText = "Resend OTP";
        document.getElementById('otp-status').innerText = "OTP sent to " + email;
    }

    function verifyOTP() {
        const otp = document.getElementById('otp-input').value;
        const status = document.getElementById('otp-status');
        const submitBtn = document.getElementById('member-submit');

        if(otp.length === 6) {
            isVerified = true;
            status.innerText = "Identity Verified Successfully";
            status.style.color = "#15803d";
            
            submitBtn.disabled = false;
            submitBtn.innerText = "Finalize Registration";
            submitBtn.classList.remove('bg-slate-200', 'text-slate-400', 'cursor-not-allowed');
            submitBtn.classList.add('bg-slate-900', 'text-white', 'hover:bg-slate-700');
        } else {
            status.innerText = "Invalid Code Terminal. Try Again.";
            status.style.color = "#b91c1c";
        }
    }

    function handleFinalSubmit(e, role) {
        e.preventDefault();
        document.getElementById('main-form-container').style.display = 'none';
        
        if(role === 'member') {
            document.getElementById('member-success').style.display = 'block';
        } else {
            document.getElementById('vendor-success').style.display = 'block';
        }
    }
</script>