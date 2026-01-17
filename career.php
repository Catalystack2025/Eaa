<?php
/* =========================================================
   career.php — ARCHITECTURAL CAREER PORTAL
   ✅ Open-access recruitment hub for the community
   ✅ Exclusive Montserrat Typography
   ✅ High-density Technical List for Openings
   ✅ Experience-based metadata & firm identity
   ✅ Standardized 5px Radius & Smoke Grey Palette
   ========================================================= */

require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/config/db.php';

start_session();

$pageTitle = 'Career Portal | Erode Architect Association';
require_once __DIR__ . "/partials/header.php";

// Mock Job Data (Typically posted by firms via their dashboard)
$jobs = [
    [
        'id' => 'EAA-JOB-2026-001',
        'role' => 'Senior Project Architect',
        'firm' => 'Kumar & Associates',
        'location' => 'Erode Central',
        'type' => 'Full-Time',
        'exp' => '5-8 Years',
        'salary' => 'Competitive',
        'posted' => '2 days ago',
        'description' => 'Lead large-scale commercial developments from concept to execution. Proficiency in BIM and local building codes is mandatory.'
    ],
    [
        'id' => 'EAA-JOB-2026-002',
        'role' => 'BIM Coordinator',
        'firm' => 'Design Grid Studio',
        'location' => 'Perundurai Road',
        'type' => 'Full-Time',
        'exp' => '3-5 Years',
        'salary' => 'As per standards',
        'posted' => '5 days ago',
        'description' => 'Manage multi-disciplinary architectural models and ensure technical coordination across structural and MEP teams.'
    ],
    [
        'id' => 'EAA-JOB-2026-003',
        'role' => 'Junior Visualization Artist',
        'firm' => 'Urban Edge Architects',
        'location' => 'Erode South',
        'type' => 'Contract / Project',
        'exp' => '1-2 Years',
        'salary' => 'Project Basis',
        'posted' => '1 week ago',
        'description' => 'Create high-fidelity 3D renders and walk-throughs for high-end residential clients. Expertise in Unreal Engine/Lumion preferred.'
    ],
    [
        'id' => 'EAA-JOB-2026-004',
        'role' => 'Intern Architect',
        'firm' => 'Heritage Lab',
        'location' => 'Bhavani Road',
        'type' => 'Internship',
        'exp' => 'Freshers',
        'salary' => 'Stipend provided',
        'posted' => 'Just now',
        'description' => 'Assist in documentation of heritage sites and preparation of architectural drawings for restoration projects.'
    ]
];
?>

<style>
    :root {
        --eaa-smoke: #475569;
        --eaa-border: #e2e8f0;
        --eaa-radius: 5px;
        --eaa-accent: #1e293b;
    }

    body {
        background-color: #f8fafc;
        color: #1e293b;
        font-family: 'Montserrat', sans-serif;
    }

    .font-druk {
        font-family: 'Montserrat', sans-serif !important;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: -0.05em;
        line-height: 0.85;
    }

    .eaa-radius { border-radius: var(--eaa-radius) !important; }

    /* Blueprint Background */
    .blueprint-grid {
        background-image: linear-gradient(rgba(71, 85, 105, 0.05) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(71, 85, 105, 0.05) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    /* Job Card Styling */
    .job-card {
        background: #ffffff;
        border: 1px solid var(--eaa-border);
        padding: 35px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .job-card:hover {
        border-color: var(--eaa-smoke);
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(71, 85, 105, 0.1);
    }

    .tech-label {
        font-size: 8px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: #94a3b8;
        display: block;
        margin-bottom: 6px;
    }

    /* Tag Styling */
    .job-tag {
        font-size: 7px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        padding: 4px 10px;
        background: #f1f5f9;
        color: #64748b;
        border-radius: 2px;
    }

    /* Modal Styling */
    #applyModal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 1000;
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(8px);
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-content {
        background: white;
        width: 100%;
        max-width: 600px;
        padding: 50px;
        border-radius: var(--eaa-radius);
        position: relative;
    }

    .tech-input {
        width: 100%;
        background: #f8fafc;
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

    .tech-input:focus {
        border-color: var(--eaa-smoke);
        background: #ffffff;
    }

    /* Animation Reveal */
    .reveal { opacity: 0; transform: translateY(20px); transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1); }
    .reveal.active { opacity: 1; transform: translateY(0); }

</style>

<!-- CAREER HEADER -->
<section class="pt-44 pb-20 relative overflow-hidden bg-white border-b border-slate-100">
    <div class="absolute inset-0 blueprint-grid opacity-20 pointer-events-none"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl">
            <span class="text-[8px] font-black uppercase tracking-[0.5em] text-slate-400 block border-l-2 border-slate-400 pl-4 mb-6">Opportunities / 2026</span>
            <h1 class="font-druk text-5xl md:text-7xl lg:text-8xl text-slate-900 leading-none mb-10">
                Career <br><span class="text-slate-400 italic">Portal</span>
            </h1>
            <p class="max-w-2xl text-slate-500 text-xs md:text-sm font-bold uppercase tracking-widest leading-loose text-justify">
                Empowering the next generation of Erode’s skyline designers. Discover open positions within our member firms, from internships to senior leadership roles. Join a guild committed to technical excellence and design integrity.
            </p>
        </div>
    </div>
</section>

<!-- JOB FILTERS -->
<section class="py-10 bg-slate-50 border-b border-slate-100 sticky top-[80px] z-40">
    <div class="container mx-auto px-6 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
            <button class="px-6 py-2 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest eaa-radius">All Roles</button>
            <button class="px-6 py-2 text-slate-400 hover:text-slate-900 text-[9px] font-black uppercase tracking-widest transition-all">Architectural</button>
            <button class="px-6 py-2 text-slate-400 hover:text-slate-900 text-[9px] font-black uppercase tracking-widest transition-all">Interior</button>
            <button class="px-6 py-2 text-slate-400 hover:text-slate-900 text-[9px] font-black uppercase tracking-widest transition-all">Planning</button>
        </div>
        
        <div class="hidden lg:flex items-center gap-4 opacity-30">
            <span class="text-[8px] font-black uppercase tracking-widest">Database: LIVE_RECRUITMENT</span>
            <div class="w-24 h-px bg-slate-900"></div>
        </div>
    </div>
</section>

<!-- JOB LISTINGS GRID -->
<main class="py-24 bg-white relative overflow-hidden">
    <div class="container mx-auto px-6 relative z-10">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <?php foreach($jobs as $index => $j): ?>
            <div class="job-card eaa-radius reveal" style="transition-delay: <?= ($index % 2) * 100 ?>ms;">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-8">
                    <div>
                        <span class="tech-label">Ref: <?= $j['id'] ?></span>
                        <h3 class="font-bold text-xl text-slate-900 uppercase tracking-tight mb-2"><?= $j['role'] ?></h3>
                        <div class="flex flex-wrap gap-3 mt-4">
                            <span class="job-tag"><?= $j['type'] ?></span>
                            <span class="job-tag"><?= $j['exp'] ?> Exp</span>
                            <span class="job-tag"><?= $j['location'] ?></span>
                        </div>
                    </div>
                    <div class="text-left md:text-right">
                        <span class="tech-label">Hiring Firm</span>
                        <span class="text-[11px] font-black text-slate-900 uppercase italic"><?= $j['firm'] ?></span>
                        <span class="block text-[8px] font-bold text-slate-400 mt-2 uppercase tracking-widest">Posted <?= $j['posted'] ?></span>
                    </div>
                </div>
                
                <p class="text-xs text-slate-500 font-medium leading-relaxed mb-10 text-justify flex-1">
                    <?= $j['description'] ?>
                </p>

                <div class="pt-8 border-t border-slate-50 flex items-center justify-between">
                    <span class="text-[9px] font-black text-slate-900 uppercase tracking-widest">Salary: <?= $j['salary'] ?></span>
                    <button 
                        onclick="openApplyModal('<?= addslashes($j['role']) ?>', '<?= addslashes($j['firm']) ?>')"
                        class="px-8 py-4 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest eaa-radius hover:bg-slate-700 transition-all shadow-xl shadow-slate-200">
                        Apply Now
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</main>

<!-- APPLICATION MODAL -->
<div id="applyModal">
    <div class="modal-content shadow-2xl">
        <button onclick="closeApplyModal()" class="absolute top-8 right-8 text-slate-300 hover:text-slate-900 transition-colors"><i class="fa-solid fa-xmark"></i></button>
        
        <span class="tech-label">Talent Submission</span>
        <h2 id="modalJobTitle" class="font-druk text-3xl text-slate-900 mt-4 mb-2 uppercase">Position Name</h2>
        <p id="modalFirmName" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-12">Firm Identity</p>
        
        <form class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="tech-label">Full Name</label>
                    <input type="text" class="tech-input" placeholder="Enter name">
                </div>
                <div>
                    <label class="tech-label">Email Address</label>
                    <input type="email" class="tech-input" placeholder="Enter email">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="tech-label">Mobile Number</label>
                    <input type="text" class="tech-input" placeholder="+91">
                </div>
                <div>
                    <label class="tech-label">Portfolio / CV Link</label>
                    <input type="url" class="tech-input" placeholder="https://...">
                </div>
            </div>

            <div>
                <label class="tech-label">Professional Summary (Optional)</label>
                <textarea class="tech-input min-h-[120px] normal-case tracking-normal text-sm" placeholder="Briefly explain your suitability for this architectural role..."></textarea>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full py-5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest eaa-radius shadow-2xl hover:bg-slate-700 transition-all">
                    Submit Application
                </button>
                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest text-center mt-6">Application will be forwarded directly to the firm's hiring board.</p>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . "/partials/footer.php"; ?>

<script>
    function openApplyModal(role, firm) {
        document.getElementById('modalJobTitle').innerText = role;
        document.getElementById('modalFirmName').innerText = firm;
        
        document.getElementById('applyModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeApplyModal() {
        document.getElementById('applyModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('DOMContentLoaded', () => {
        const revealElements = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('active'); });
        }, { threshold: 0.1 });
        revealElements.forEach(el => observer.observe(el));
    });

    window.onclick = function(event) {
        if (event.target == document.getElementById('applyModal')) {
            closeApplyModal();
        }
    }
</script>