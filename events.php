<?php
/* =========================================================
   events.php — ARCHITECTURAL EVENT CALENDAR (REVERTED LAYOUT)
   ✅ Full-width technical calendar grid (Desktop)
   ✅ Hidden calendar grid on mobile (List view primary)
   ✅ 3:4 Portrait cards inside Calendar Grid & List View
   ✅ ADDED: Event Calendar Title & Status Feedback
   ✅ Montserrat exclusive typography
   ✅ Standardized 5px Radius & Smoke Grey palette
   ========================================================= */

require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/config/db.php';

start_session();

$isLoggedIn = isset($_SESSION['user_id']);
$pageTitle = 'Event Schedule | Erode Architect Association';

// Handle registration feedback logic (Mock for UI)
$registerSuccess = isset($_GET['success']);
$registerError = isset($_GET['error']) ? "Registration failed. Please try again." : "";

require_once __DIR__ . "/partials/header.php";

// Mock Event Data
$events = [
    [
        "id" => 101,
        "title" => "Urban Planning Summit",
        "start" => "2026-01-15T10:00:00",
        "image" => "https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=800",
        "location" => "Convention Center",
        "description" => "Exploring sustainable growth and urban infrastructure for the Erode region.",
        "category" => "Technical"
    ],
    [
        "id" => 102,
        "title" => "Modernism Workshop",
        "start" => "2026-01-22T14:30:00",
        "image" => "https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=800",
        "location" => "Association HQ",
        "description" => "A deep dive into minimalist functional living spaces and material honesty.",
        "category" => "Workshop"
    ],
    [
        "id" => 103,
        "title" => "Interior Design Expo",
        "start" => "2026-01-28T09:00:00",
        "image" => "https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=800",
        "location" => "Expo Hall",
        "description" => "Showcase of luxury interior products and avant-garde architectural materials.",
        "category" => "Exhibition"
    ]
];
?>

<style>
    :root {
        --eaa-smoke: #475569;
        --eaa-border: #e2e8f0;
        --eaa-radius: 5px;
        --eaa-accent: #1e293b;
        
        /* FullCalendar Overrides */
        --fc-border-color: #e2e8f0;
        --fc-button-bg-color: transparent;
        --fc-button-border-color: #e2e8f0;
        --fc-button-text-color: #1e293b;
        --fc-button-hover-bg-color: #f8fafc;
        --fc-button-active-bg-color: #1e293b;
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
        letter-spacing: -0.04em;
        line-height: 0.85;
    }

    .eaa-radius { border-radius: var(--eaa-radius) !important; }

    /* Blueprint Background */
    .blueprint-grid {
        background-image: linear-gradient(rgba(71, 85, 105, 0.05) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(71, 85, 105, 0.05) 1px, transparent 1px);
        background-size: 30px 30px;
    }

    /* CALENDAR GRID STYLES */
    .fc { 
        font-family: 'Montserrat', sans-serif !important; 
        background: #ffffff;
        border-radius: var(--eaa-radius);
    }
    
    .fc-daygrid-day-frame {
        min-height: 160px !important;
        padding: 6px !important;
    }

    .fc-toolbar-title {
        font-size: 1.2rem !important;
        font-weight: 900 !important;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .fc-button {
        font-size: 9px !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
        border-radius: 2px !important;
    }

    /* 3:4 PORTRAIT CALENDAR EVENT CARD */
    .grid-event-card {
        position: relative;
        width: 100%;
        aspect-ratio: 3 / 4;
        margin: 4px auto 0;
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .grid-event-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border-color: var(--eaa-smoke);
    }

    .grid-event-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .grid-event-info {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        padding: 8px;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
        color: white;
    }

    .grid-event-title {
        font-size: 8px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        line-height: 1.2;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Modal Overlay */
    #eventModal {
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
        max-width: 500px;
        border-radius: var(--eaa-radius);
        overflow: hidden;
        position: relative;
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

    /* 3:4 Portrait List Cards (Bottom Section) */
    .portrait-card {
        background: white;
        border: 1px solid var(--eaa-border);
        transition: all 0.4s ease;
        display: flex;
        flex-direction: column;
    }

    .portrait-card:hover {
        border-color: var(--eaa-smoke);
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.05);
    }

    .portrait-image {
        aspect-ratio: 3/4;
        overflow: hidden;
    }

    /* Animation Reveal */
    .reveal { opacity: 0; transform: translateY(20px); transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1); }
    .reveal.active { opacity: 1; transform: translateY(0); }

</style>

<!-- MAIN CONTENT WRAPPER -->
<main class="py-32">
    <div class="absolute inset-0 blueprint-grid opacity-10 pointer-events-none h-96"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        
        <!-- HEADER TITLE SECTION -->
        <div class="mb-16 reveal text-left lg:pl-4">
            <span class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-400 mb-3 block border-l-2 border-slate-900 pl-4">Monthly Planner</span>
            <h1 class="font-druk text-4xl md:text-6xl text-slate-900 leading-none">Event <span class="text-slate-400 italic">Calendar</span></h1>
            <p class="mt-6 max-w-lg text-[10px] font-bold uppercase tracking-widest text-slate-400 leading-loose">
                System-integrated schedule for association workshops and regional architect summits.
            </p>

            <?php if ($registerSuccess): ?>
                <div class="mt-10 p-6 bg-green-50 border border-green-100 eaa-radius flex items-center gap-4">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-widest text-green-700">Registration Success</div>
                        <div class="text-[11px] font-bold text-green-900 mt-1 uppercase">
                            <?= $isLoggedIn ? "Your attendance is confirmed." : "Your registration request is submitted." ?>
                        </div>
                    </div>
                </div>
            <?php elseif ($registerError !== ""): ?>
                <div class="mt-10 p-6 bg-red-50 border border-red-100 eaa-radius flex items-center gap-4">
                    <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-widest text-red-700">Action Required</div>
                        <div class="text-[11px] font-bold text-red-900 mt-1 uppercase"><?= htmlspecialchars($registerError) ?></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- TOP: CALENDAR GRID (Full Width, Hidden on Mobile) -->
        <div class="hidden lg:block mb-24 reveal">
            <div class="border border-slate-200 eaa-radius shadow-sm overflow-hidden bg-white p-4">
                <div id="calendar"></div>
            </div>
            <div class="mt-6 flex items-center gap-4 opacity-20">
                <span class="text-[7px] font-black uppercase tracking-widest">Chronicle: FULL_GRID_VIEW</span>
                <div class="h-px flex-1 bg-slate-900"></div>
            </div>
        </div>

        <!-- BOTTOM: UPCOMING LIST (Full Width) -->
        <div class="space-y-12">
            <div class="flex items-end justify-between reveal">
                <div class="border-l-2 border-slate-900 pl-6">
                    <span class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-400 mb-2 block">Archive Records</span>
                    <h3 class="font-druk text-3xl md:text-5xl uppercase text-slate-900">Upcoming <span class="text-slate-400 italic">Events</span></h3>
                </div>
                <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">Total: <?= count($events) ?></span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php foreach($events as $index => $e): ?>
                <div class="portrait-card eaa-radius reveal group cursor-pointer" onclick="openEventModal(<?= $index ?>)" style="transition-delay: <?= $index * 100 ?>ms;">
                    <div class="portrait-image">
                        <img src="<?= $e['image'] ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="<?= $e['title'] ?>">
                    </div>
                    <div class="p-8 flex-1 flex flex-col">
                        <span class="tech-label"><?= date('M d, Y', strtotime($e['start'])) ?></span>
                        <h4 class="font-bold text-sm text-slate-900 uppercase tracking-tight mb-4 group-hover:text-slate-500 transition-colors"><?= $e['title'] ?></h4>
                        
                        <div class="mt-auto pt-6 flex flex-col gap-4">
                            <div class="flex items-center justify-between border-t border-slate-50 pt-4">
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest"><?= $e['location'] ?></span>
                                <div class="w-6 h-6 rounded-full border border-slate-100 flex items-center justify-center group-hover:bg-slate-900 group-hover:text-white transition-all">
                                    <i class="fa-solid fa-arrow-right-long text-[8px]"></i>
                                </div>
                            </div>
                            <?php if ($isLoggedIn): ?>
                                <a href="events.php?success=1" class="block">
                                    <span class="block w-full text-center py-3 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest eaa-radius hover:bg-slate-700 transition-all shadow-lg shadow-slate-200">Register to Attend</span>
                                </a>
                            <?php else: ?>
                                <a href="login.php" class="block">
                                    <span class="block w-full text-center py-3 border border-slate-200 text-slate-700 text-[9px] font-black uppercase tracking-widest eaa-radius hover:bg-slate-50 transition-all">Sign in to Register</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</main>

<!-- EVENT DETAIL MODAL -->
<div id="eventModal">
    <div class="modal-content shadow-2xl">
        <div class="relative h-56 overflow-hidden">
            <img id="modalImage" src="" class="w-full h-full object-cover" alt="">
            <button onclick="closeEventModal()" class="absolute top-4 right-4 w-8 h-8 bg-black/20 backdrop-blur-md text-white rounded-full flex items-center justify-center hover:bg-black/40 transition-all"><i class="fa-solid fa-xmark text-xs"></i></button>
        </div>
        <div class="p-12">
            <span id="modalDate" class="tech-label">Date Placeholder</span>
            <h2 id="modalTitle" class="font-druk text-3xl text-slate-900 mb-6 uppercase">Event Title</h2>
            <p id="modalDesc" class="text-xs text-slate-500 leading-loose mb-10 text-justify uppercase tracking-widest font-semibold"></p>
            
            <div class="grid grid-cols-2 gap-4 pt-10 border-t border-slate-50">
                <?php if ($isLoggedIn): ?>
                    <a href="events.php?success=1" class="px-6 py-5 bg-slate-900 text-white text-center text-[9px] font-black uppercase tracking-widest eaa-radius hover:bg-slate-700 transition-all shadow-xl">Register to Attend</a>
                <?php else: ?>
                    <a href="login.php" class="px-6 py-5 border border-slate-200 text-center text-[9px] font-black uppercase tracking-widest eaa-radius hover:bg-slate-50 transition-all">Sign in to Register</a>
                <?php endif; ?>
                <button class="px-6 py-5 border border-slate-200 text-[9px] font-black uppercase tracking-widest eaa-radius hover:bg-slate-50 transition-all">Download Info</button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/partials/footer.php"; ?>

<!-- FullCalendar Dependencies -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

<script>
    const EVENT_DATA = <?= json_encode($events) ?>;

    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        if (calendarEl && window.innerWidth >= 1024) {
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                height: 700,
                events: EVENT_DATA.map((e, idx) => ({
                    title: e.title,
                    start: e.start,
                    extendedProps: { index: idx, image: e.image }
                })),
                eventContent: function(arg) {
                    const props = arg.event.extendedProps;
                    let wrapper = document.createElement('div');
                    wrapper.classList.add('grid-event-card');
                    wrapper.innerHTML = `
                        <img src="${props.image}" alt="Event">
                        <div class="grid-event-info">
                            <h3 class="grid-event-title">${arg.event.title}</h3>
                        </div>
                    `;
                    return { domNodes: [wrapper] };
                },
                eventClick: function(info) {
                    openEventModal(info.event.extendedProps.index);
                }
            });
            calendar.render();
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('active'); });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    });

    function openEventModal(index) {
        const e = EVENT_DATA[index];
        document.getElementById('modalTitle').innerText = e.title;
        document.getElementById('modalDate').innerText = new Date(e.start).toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        document.getElementById('modalDesc').innerText = e.description;
        document.getElementById('modalImage').src = e.image;
        
        document.getElementById('eventModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeEventModal() {
        document.getElementById('eventModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('eventModal')) closeEventModal();
    }
</script>
