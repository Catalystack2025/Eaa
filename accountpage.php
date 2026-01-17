<?php
/* =========================================================
   account.php — MEMBER PROFESSIONAL CONSOLE
   ✅ Exclusive Montserrat Typography
   ✅ High-density Technical Ledger Form for Blogs
   ✅ ADDED: Text Formatting Toolbar (Bold, Align) for Blogs
   ✅ RESTORED: Attended Events History Table
   ✅ Job Posting & Recruitment Portal
   ✅ Standardized 5px Radius Throughout
   ✅ Real-time Tabbed Navigation
   ========================================================= */

require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/config/db.php';

start_session();

$pageTitle = 'Member Console | Erode Architect Association';
require_once __DIR__ . "/partials/header.php";

// Mock Member Data
$member = [
    'name' => 'Ar. Suresh Kumar',
    'id' => 'EAA-2024-892',
    'status' => 'Active Member',
    'expiry' => 'Jan 11, 2026'
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

    /* Technical Input Styling */
    .tech-input {
        width: 100%;
        background: #ffffff;
        border: 1px solid var(--eaa-border);
        border-radius: var(--eaa-radius);
        padding: 12px 16px;
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

    /* Formatting Toolbar */
    .editor-toolbar {
        display: flex;
        gap: 2px;
        background: #f1f5f9;
        padding: 4px;
        border: 1px solid var(--eaa-border);
        border-bottom: none;
        border-top-left-radius: var(--eaa-radius);
        border-top-right-radius: var(--eaa-radius);
    }

    .toolbar-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        color: var(--eaa-smoke);
        font-size: 12px;
        border-radius: 2px;
        transition: all 0.2s ease;
    }

    .toolbar-btn:hover {
        background: var(--eaa-accent);
        color: white;
    }

    .textarea-editor {
        border-top-left-radius: 0 !important;
        border-top-right-radius: 0 !important;
    }

    /* Tab Navigation */
    .console-tab {
        font-size: 9px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #94a3b8;
        padding: 15px 25px;
        border-bottom: 2px solid transparent;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .console-tab.active {
        color: var(--eaa-accent);
        border-bottom-color: var(--eaa-accent);
    }

    /* Dashboard Cards */
    .console-card {
        background: #ffffff;
        border: 1px solid var(--eaa-border);
        border-radius: var(--eaa-radius);
        padding: 30px;
        height: 100%;
    }

    .status-badge {
        font-size: 7px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        padding: 4px 10px;
        border-radius: 2px;
    }

    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-published { background: #dcfce7; color: #166534; }
    .badge-missed { background: #fee2e2; color: #b91c1c; }

    /* Animation Reveal */
    .reveal { opacity: 0; transform: translateY(20px); transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1); }
    .reveal.active { opacity: 1; transform: translateY(0); }

    .tab-content { display: none; }
    .tab-content.active { display: block; }
</style>

<!-- MEMBER TOP HEADER -->
<section class="pt-44 pb-12 bg-white relative overflow-hidden border-b border-slate-100">
    <div class="absolute inset-0 blueprint-grid opacity-20 pointer-events-none"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-10">
            <div class="reveal">
                <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-[8px] font-black uppercase tracking-[0.3em] text-slate-500 eaa-radius mb-6">
                    <?= $member['status'] ?>
                </span>
                <h1 class="font-druk text-5xl md:text-7xl lg:text-8xl text-slate-900 leading-none">
                    <?= $member['name'] ?>
                </h1>
            </div>
            <div class="text-left lg:text-right reveal" style="transition-delay: 100ms;">
                <span class="tech-label">Membership Record</span>
                <span class="font-druk text-2xl lg:text-3xl text-slate-400"><?= $member['id'] ?></span>
            </div>
        </div>
    </div>
</section>

<!-- CONSOLE NAVIGATION -->
<section class="bg-white border-b border-slate-100 sticky top-[80px] z-40">
    <div class="container mx-auto px-6">
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
            <div class="console-tab active" onclick="switchTab(event, 'membership')">Overview</div>
            <div class="console-tab" onclick="switchTab(event, 'journal')">Journal Submissions</div>
            <div class="console-tab" onclick="switchTab(event, 'careers')">Firm Careers</div>
        </div>
    </div>
</section>

<!-- MAIN CONSOLE CONTENT -->
<main class="py-16">
    <div class="container mx-auto px-6 max-w-7xl">
        
        <!-- TAB: OVERVIEW (Includes Attended History) -->
        <div id="membership" class="tab-content active reveal">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Tenure Card -->
                <div class="lg:col-span-1">
                    <div class="console-card">
                        <h3 class="font-druk text-xl mb-8">Tenure / Foundation</h3>
                        <div class="space-y-6">
                            <div class="flex justify-between border-b border-slate-50 pb-4">
                                <span class="tech-label">Valid Until</span>
                                <span class="text-[11px] font-bold text-slate-900"><?= $member['expiry'] ?></span>
                            </div>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="tech-label">Quanity of Renewal</span>
                                    <span class="text-[9px] font-black">364 DAYS REMAINING</span>
                                </div>
                                <div class="h-1 w-full bg-slate-100 overflow-hidden eaa-radius">
                                    <div class="h-full bg-slate-900 w-1/2"></div>
                                </div>
                            </div>
                            <button class="w-full py-4 border border-slate-200 text-[9px] font-black uppercase tracking-widest eaa-radius hover:bg-slate-900 hover:text-white transition-all">Extend Membership</button>
                        </div>
                    </div>
                </div>

                <!-- Recent Registrations -->
                <div class="lg:col-span-2">
                    <div class="console-card">
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="font-druk text-xl">Event Agenda</h3>
                            <a href="calendar.php" class="text-[8px] font-black uppercase tracking-widest border-b border-slate-900 pb-1">Full Calendar</a>
                        </div>
                        <div class="space-y-4">
                            <?php for($i=0; $i<2; $i++): ?>
                            <div class="flex items-center gap-6 p-4 border border-slate-100 eaa-radius hover:border-slate-300 transition-all">
                                <div class="w-16 h-16 bg-slate-50 eaa-radius flex flex-col items-center justify-center border border-slate-100">
                                    <span class="text-lg font-black leading-none"><?= $i==0 ? '15' : '05' ?></span>
                                    <span class="text-[7px] font-bold text-slate-400"><?= $i==0 ? 'JAN' : 'FEB' ?></span>
                                </div>
                                <div class="flex-1">
                                    <span class="tech-label">Summit / Workshop</span>
                                    <h4 class="text-[11px] font-bold text-slate-900 uppercase tracking-tight"><?= $i==0 ? 'Urban Planning Biennale 2026' : 'BIM Architecture Workshop' ?></h4>
                                </div>
                                <span class="status-badge badge-published">Registered</span>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <!-- ATTENDED HISTORY (Full Width) -->
                <div class="lg:col-span-3 mt-4">
                    <div class="console-card">
                        <h3 class="font-druk text-xl mb-8 uppercase">History & <span class="text-slate-400 italic">Attended</span></h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="border-b border-slate-100">
                                    <tr>
                                        <th class="pb-5 tech-label">Event Title</th>
                                        <th class="pb-5 tech-label">Date</th>
                                        <th class="pb-5 tech-label text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <?php
                                    $history = [
                                        ['title' => 'Residential Design Meetup', 'date' => 'Oct 20, 2025', 'status' => 'Attended'],
                                        ['title' => 'Sustainable Materials Expo', 'date' => 'Aug 12, 2025', 'status' => 'Attended'],
                                        ['title' => 'Heritage Restoration Talk', 'date' => 'Jun 05, 2025', 'status' => 'Missed']
                                    ];
                                    foreach($history as $row):
                                    ?>
                                    <tr class="group hover:bg-slate-50 transition-colors">
                                        <td class="py-6 text-[10px] font-bold uppercase tracking-widest text-slate-900"><?= $row['title'] ?></td>
                                        <td class="py-6 text-[9px] font-bold uppercase tracking-widest text-slate-400"><?= $row['date'] ?></td>
                                        <td class="py-6 text-right">
                                            <span class="status-badge <?= $row['status'] == 'Attended' ? 'badge-published' : 'badge-missed' ?>">
                                                <?= $row['status'] ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: JOURNAL SUBMISSION (Writing Portal) -->
        <div id="journal" class="tab-content reveal">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Submission History -->
                <div class="lg:col-span-4 space-y-6">
                    <h3 class="font-druk text-xl mb-8">Drafts & <span class="text-slate-400 italic">Archive</span></h3>
                    <?php for($i=1; $i<=2; $i++): ?>
                    <div class="p-6 bg-white border border-slate-100 eaa-radius">
                        <div class="flex justify-between items-start mb-4">
                            <span class="tech-label">REF: EAA-SUB-0<?= $i ?></span>
                            <span class="status-badge <?= $i==1 ? 'badge-published' : 'badge-pending' ?>"><?= $i==1 ? 'Published' : 'Under Review' ?></span>
                        </div>
                        <h4 class="text-xs font-bold text-slate-900 uppercase leading-snug mb-2">Passive Cooling Strategies in Erode Districts</h4>
                        <p class="text-[9px] text-slate-400 font-medium">Last modified: 12 Jan 2026</p>
                    </div>
                    <?php endfor; ?>
                </div>

                <!-- Write Blog Form -->
                <div class="lg:col-span-8">
                    <div class="console-card">
                        <h3 class="font-druk text-xl mb-8">Submit <span class="text-slate-400 italic">Manuscript</span></h3>
                        <form class="space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="tech-label">Journal Title (Tile)</label>
                                    <input type="text" class="tech-input" placeholder="Enter post title">
                                </div>
                                <div>
                                    <label class="tech-label">Category</label>
                                    <select class="tech-input">
                                        <option>Urban Sustainability</option>
                                        <option>Residential Detail</option>
                                        <option>Heritage Preservation</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="tech-label">Climate Zone</label>
                                    <input type="text" class="tech-input" placeholder="e.g. Tropical Humid">
                                </div>
                                <div>
                                    <label class="tech-label">Project Type</label>
                                    <input type="text" class="tech-input" placeholder="e.g. Mixed-Use">
                                </div>
                                <div>
                                    <label class="tech-label">Featured Image</label>
                                    <input type="file" class="tech-input pt-2">
                                </div>
                            </div>

                            <div>
                                <label class="tech-label">Article Narrative (Content)</label>
                                <!-- Formatting Toolbar Mockup -->
                                <div class="editor-toolbar">
                                    <button type="button" class="toolbar-btn" title="Bold"><i class="fa-solid fa-bold"></i></button>
                                    <button type="button" class="toolbar-btn" title="Italic"><i class="fa-solid fa-italic"></i></button>
                                    <div class="w-px h-4 bg-slate-200 mx-2 self-center"></div>
                                    <button type="button" class="toolbar-btn" title="Align Left"><i class="fa-solid fa-align-left"></i></button>
                                    <button type="button" class="toolbar-btn" title="Align Center"><i class="fa-solid fa-align-center"></i></button>
                                    <button type="button" class="toolbar-btn" title="Align Right"><i class="fa-solid fa-align-right"></i></button>
                                    <button type="button" class="toolbar-btn" title="Justify"><i class="fa-solid fa-align-justify"></i></button>
                                </div>
                                <textarea class="tech-input textarea-editor min-h-[350px] normal-case tracking-normal text-sm" placeholder="Draft your professional insights here..."></textarea>
                                <span class="text-[7px] font-bold text-slate-400 mt-2 block uppercase tracking-widest">Markdown & HTML formatting supported for technical diagrams.</span>
                            </div>

                            <div class="pt-6 border-t border-slate-50 flex justify-end gap-4">
                                <button type="button" class="px-8 py-4 text-[9px] font-black uppercase tracking-widest text-slate-400">Save Draft</button>
                                <button type="submit" class="px-10 py-4 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest eaa-radius shadow-xl hover:bg-slate-700 transition-all">Submit for Review</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: CAREERS (Job Posting) -->
        <div id="careers" class="tab-content reveal">
            <div class="max-w-4xl mx-auto">
                <div class="console-card">
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                        <div>
                            <h3 class="font-druk text-2xl lg:text-3xl">Firm <span class="text-slate-400 italic">Recruitment</span></h3>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mt-2">Manage job openings for your architectural practice</p>
                        </div>
                        <button class="bg-slate-900 text-white px-8 py-4 text-[9px] font-black uppercase tracking-widest eaa-radius shadow-lg">+ Post New Position</button>
                    </div>

                    <div class="space-y-6">
                        <span class="tech-label">Active Postings</span>
                        <!-- Job Item -->
                        <?php 
                        $jobs = [
                            ['title' => 'Senior Project Architect', 'type' => 'Full-Time', 'apps' => 12, 'deadline' => '20 Feb 2026'],
                            ['title' => 'Junior Visualization Artist', 'type' => 'Contract', 'apps' => 45, 'deadline' => '15 Feb 2026']
                        ];
                        foreach($jobs as $job): 
                        ?>
                        <div class="flex flex-col md:flex-row items-center gap-6 p-8 border border-slate-100 eaa-radius hover:border-slate-300 transition-all group">
                            <div class="flex-1">
                                <h4 class="text-sm font-black text-slate-900 uppercase tracking-tight mb-2"><?= $job['title'] ?></h4>
                                <div class="flex gap-4">
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest"><?= $job['type'] ?></span>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">•</span>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Deadline: <?= $job['deadline'] ?></span>
                                </div>
                            </div>
                            <div class="text-center md:text-right">
                                <span class="block text-xl font-black text-slate-900"><?= $job['apps'] ?></span>
                                <span class="text-[7px] font-black text-slate-400 uppercase tracking-[0.2em]">Applicants</span>
                            </div>
                            <div class="flex gap-2">
                                <button class="w-10 h-10 border border-slate-100 eaa-radius flex items-center justify-center hover:bg-slate-50 transition-all"><i class="fa-regular fa-pen-to-square text-xs"></i></button>
                                <button class="px-6 py-2 bg-slate-100 text-slate-900 text-[8px] font-black uppercase tracking-widest eaa-radius hover:bg-slate-200 transition-all">View Applications</button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<?php require_once __DIR__ . "/partials/footer.php"; ?>

<script>
    function switchTab(event, tabId) {
        // Remove active class from all tabs and contents
        document.querySelectorAll('.console-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

        // Add active class to selected tab and content
        event.currentTarget.classList.add('active');
        document.getElementById(tabId).classList.add('active');
        
        // Re-trigger scroll reveals
        const revealElements = document.getElementById(tabId).querySelectorAll('.reveal');
        revealElements.forEach(el => el.classList.add('active'));
    }

    document.addEventListener('DOMContentLoaded', () => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('active'); });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    });
</script>