<?php
/* =========================================================
   testing.php — INTEGRATED ARCHITECTURAL LANDING PAGE
   ✅ 3D Animated Preloader Added
   ✅ Hero Slideshow synced to 3s Interval
   ✅ Atmospheric Image Blur (10px) & Brightness Adjustment
   ✅ SWIPE Carousels: Events (1-col mobile, 2-col desktop)
   ✅ SWIPE Carousels: Projects (1-col mobile, 3-col desktop)
   ✅ President Section: Added dedicated Title
   ✅ Standardized 5px Radius Throughout
   ========================================================= */

require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/config/db.php';

start_session();

/* ---------- STATIC DATA ---------- */
$leadership = [
  'president' => [
    'name' => 'Ar. Suresh Kumar',
    'title' => 'EAA President 2024-26',
    'photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&q=80',
    'message' => "Our association stands as a beacon of architectural integrity in the Erode region. We are committed to fostering a community where sustainable design meets regional excellence. Through collaboration and continuous learning, we shape the skylines of tomorrow while preserving the heritage of today."
  ],
  'vice_president' => ['name' => 'Ar. Lakshmi N.', 'photo' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=600&q=80'],
  'secretary' => ['name' => 'Ar. Priya Sharma', 'photo' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=600&q=80'],
  'treasurer' => ['name' => 'Ar. Rajesh M.', 'photo' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=600&q=80'],
];

$benefits = [
  ['title' => 'Professional Network', 'desc' => 'Build meaningful partnerships with 500+ architects.', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197'],
  ['title' => 'Recognition', 'desc' => 'Showcase standout projects through annual awards.', 'icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12'],
  ['title' => 'Learning', 'desc' => 'Access certification-led learning year-round.', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253'],
];

$events = [
    ["title" => "Erode Design Biennale 2026", "date" => "12 FEB", "loc" => "Heritage Center", "img" => "https://images.unsplash.com/photo-1511818966892-d7d671e672a2?q=80&w=800"],
    ["title" => "Sustainable Urbanism Workshop", "date" => "24 FEB", "loc" => "Guild Hall", "img" => "https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=800"],
    ["title" => "Architects Keynote", "date" => "05 MAR", "loc" => "EAA Hub", "img" => "https://images.unsplash.com/photo-1431540015161-0bf868a2d407?q=80&w=800"],
    ["title" => "BIM Level 02 Summit", "date" => "18 APR", "loc" => "Tech Park", "img" => "https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=800"],
];

$projects = [
  ['title' => 'Waterfront Resort', 'budget' => '$120K - $560K', 'location' => 'Erode, TN', 'image' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1200&q=80', 'desc' => 'A luxury hospitality project focused on low-impact environmental integration.'],
  ['title' => 'Modern Dwelling', 'budget' => '$100K - $600K', 'location' => 'Erode, TN', 'image' => 'https://images.unsplash.com/photo-1600585154340-be1200&q=80', 'desc' => 'Minimalist urban residence exploring natural light and cross-ventilation.'],
  ['title' => 'Urban Loft', 'budget' => '$150K - $400K', 'location' => 'Chennai, TN', 'image' => 'https://images.unsplash.com/photo-1600607687939-ce1200&q=80', 'desc' => 'Conversion of industrial space into a high-density modern living solution.'],
  ['title' => 'Eco-Centric Villa', 'budget' => '$200K - $800K', 'location' => 'Ooty, TN', 'image' => 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=1200&q=80', 'desc' => 'Sustainable hillside development with passive heating solutions.'],
];

$blogs = [
    ["title" => "Vertical Forests: Future Urban Centers", "date" => "Jan 15, 2026", "img" => "https://images.unsplash.com/photo-1486718448742-163732cd1544?w=800"],
    ["title" => "Minimalist Courtyards & Vaastu", "date" => "Jan 02, 2026", "img" => "https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800"],
];

$sponsors = ['Steel India', 'Cement Corp', 'Glass Works', 'Timber Plus', 'Paint Masters', 'Tiles Pro'];

$pageTitle = 'Erode Architect Association | Designing the Skyline';
require_once __DIR__ . "/partials/header.php";
?>

<style>
    /* 1. Global Branding DNA */
    :root { 
        --eaa-smoke: #475569; 
        --eaa-radius: 5px; 
        --eaa-charcoal: #0a0f1d;
    }
    .eaa-radius { border-radius: var(--eaa-radius) !important; }
    .font-druk { font-family: 'Inter', sans-serif; font-weight: 900; text-transform: uppercase; letter-spacing: -0.05em; line-height: 0.85; }

    body { background-color: #f8fafc; overflow-x: hidden; }

    /* 2. Preloader Styles */
    #main-preloader {
        position: fixed; inset: 0; z-index: 10000;
        display: flex; align-items: center; justify-content: center;
        background: var(--eaa-charcoal);
        color: white;
        transition: transform 1s cubic-bezier(0.85, 0, 0.15, 1), opacity 0.8s ease;
        perspective: 1000px;
    }
    #main-preloader.fade-out { transform: translateY(-100%); opacity: 0; pointer-events: none; }
    
    .loader-container { display: flex; align-items: center; gap: 32px; transform-style: preserve-3d; }
    .eaa-brand-load {
        font-family: 'Inter', sans-serif;
        font-size: 5.2rem;
        font-weight: 900;
        letter-spacing: -2px;
        line-height: 0.85;
        opacity: 0;
        filter: blur(10px);
        transform: rotateX(-90deg) translateZ(50px);
        animation: flipIn 1.15s cubic-bezier(0.19, 1, 0.22, 1) forwards;
    }
    .info-stack-load { display: flex; flex-direction: column; gap: 4px; }
    .load-item {
        text-transform: uppercase;
        white-space: nowrap;
        opacity: 0;
        filter: blur(8px);
        transform: translateY(20px) rotateX(-20deg);
        font-family: 'Montserrat', sans-serif;
    }
    .erode-load { font-size: 2.0rem; font-weight: 800; letter-spacing: .16em; animation: structuralReveal .85s cubic-bezier(0.19, 1, 0.22, 1) 0.55s forwards; }
    .arch-load { font-size: 1.05rem; font-weight: 400; letter-spacing: .28em; color: rgba(255,255,255,.6); animation: structuralReveal .85s cubic-bezier(0.19, 1, 0.22, 1) 0.75s forwards; }
    .assoc-load { font-size: 1.05rem; font-weight: 400; letter-spacing: .28em; color: rgba(255,255,255,.6); animation: structuralReveal .85s cubic-bezier(0.19, 1, 0.22, 1) 0.95s forwards; }

    @keyframes flipIn { to { opacity: 1; filter: blur(0); transform: rotateX(0) translateZ(0); } }
    @keyframes structuralReveal { to { opacity: 1; filter: blur(0); transform: translateY(0) rotateX(0); } }

    /* 3. Hero Customization */
    .hero-container {
        position: relative;
        min-height: 100vh;
        background-color: #000;
        display: flex; align-items: center; justify-content: center; overflow: hidden;
    }
    .hero-overlay {
        position: absolute; inset: 0; z-index: 2;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.1) 50%, rgba(0, 0, 0, 0.6) 100%);
        pointer-events: none;
    }
    .hero-img { 
        filter: brightness(0.8) contrast(1.05) blur(10px); /* Atmospheric Visibility Improvement */
        z-index: 1; transition: opacity 1.2s ease-in-out;
    }

    /* 4. Carousel Components (Native Swipe + Snap) */
    .snap-carousel {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        -ms-overflow-style: none;
        gap: 2rem;
        padding-bottom: 2rem;
    }
    .snap-carousel::-webkit-scrollbar { display: none; }
    .snap-item {
        scroll-snap-align: start;
        flex: 0 0 100%; /* Default Mobile */
    }

    @media (min-width: 768px) {
        .event-item { flex: 0 0 calc(50% - 1rem); } /* 2-column for Events Desktop */
        .project-item { flex: 0 0 calc(33.333% - 1.33rem); } /* 3-column for Projects Desktop */
    }

    /* Arrow Buttons Styling */
    .nav-arrow {
        width: 44px; height: 44px;
        border: 1px solid var(--eaa-border);
        display: flex; items-center; justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
        background: #fff;
    }
    .nav-arrow:hover { background: var(--eaa-charcoal); color: #fff; border-color: var(--eaa-charcoal); }

    /* Cohesion Elements */
    .blueprint-grid { background-image: linear-gradient(rgba(255,255,255,0.05) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 40px 40px; }
    .blueprint-line { border-left: 1px solid rgba(71, 85, 105, 0.1); }

    /* Components */
    .member-card { position: relative; border: 1px solid rgba(0,0,0,0.05); overflow: hidden; border-radius: var(--eaa-radius); }
    .member-card img { width: 100%; height: 100%; object-fit: cover; }
    .card-label { position: absolute; background: #1e293b; color: white; padding: 2px 8px; font-size: 8px; font-weight: 800; text-transform: uppercase; z-index: 10; border-radius: 2px; }
    .label-tl { top: 10px; left: 10px; }
    .label-bl { bottom: 10px; left: 10px; }

    /* Animation */
    .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1); }
    .reveal.active { opacity: 1; transform: translateY(0); }

    @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
    .animate-marquee { display: flex; width: max-content; animation: marquee 30s linear infinite; }
</style>

<!-- PRELOADER -->
<div id="main-preloader">
    <div class="loader-container">
        <div class="eaa-brand-load">EAA</div>
        <div class="info-stack-load">
            <div class="load-item erode-load">Erode</div>
            <div class="load-item arch-load">Architect</div>
            <div class="load-item assoc-load">Association</div>
        </div>
    </div>
</div>

<!-- HERO SECTION -->
<section class="hero-container">
    <div class="absolute inset-0 z-0">
        <div id="hero-slides">
            <img src="https://images.unsplash.com/photo-1486718448742-163732cd1544?w=1920&q=80" class="hero-img absolute inset-0 w-full h-full object-cover opacity-100 slide-img">
            <img src="https://images.unsplash.com/photo-1487958449943-2429e8be8625?w=1920&q=80" class="hero-img absolute inset-0 w-full h-full object-cover opacity-0 slide-img">
            <img src="https://images.unsplash.com/photo-1518005020951-eccb494ad742?w=1920&q=80" class="hero-img absolute inset-0 w-full h-full object-cover opacity-0 slide-img">
        </div>
        <div class="hero-overlay"></div>
    </div>

    <div class="absolute inset-0 blueprint-grid opacity-30 pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10 text-center">
        <div class="max-w-4xl mx-auto py-12">
            <div class="reveal flex flex-col items-center gap-6 mb-10">
                <span class="inline-flex items-center justify-center px-6 py-2 border border-white/20 text-[10px] font-black uppercase tracking-[0.5em] text-white rounded-full bg-white/5 backdrop-blur-md">
                    Established 1985 • Serving Erode
                </span>
            </div>
            <h1 class="font-druk text-6xl md:text-8xl lg:text-9xl mb-8 text-white leading-none reveal" style="transition-delay:200ms;">
                Designing the<br>
                <span class="text-slate-400">Skyline</span> of Erode
            </h1>
            <p class="max-w-2xl mx-auto text-white/90 text-sm md:text-base font-medium tracking-wide mb-12 reveal leading-relaxed" style="transition-delay:400ms;">
                A collaborative network of architects advancing sustainable, context-driven design across Tamil Nadu.
            </p>
            
            <div class="p-10 reveal max-w-4xl mx-auto border border-white/10 bg-white/5 backdrop-blur-sm eaa-radius" style="transition-delay:600ms;">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-10">
                    <?php 
                    $stats = [['n' => '500+', 'l' => 'Members'], ['n' => '1000+', 'l' => 'Projects'], ['n' => '38', 'l' => 'Years'], ['n' => '50+', 'l' => 'Teams']];
                    foreach($stats as $s): ?>
                    <div class="text-white">
                        <div class="text-3xl font-black mb-1"><?= $s['n'] ?></div>
                        <div class="text-[9px] font-black uppercase tracking-widest text-white/50"><?= $s['l'] ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- LEADERSHIP SECTION -->
<section class="py-24 bg-white dark:bg-slate-900">
    <div class="container mx-auto px-6 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
            
            <div class="lg:col-span-7 order-2 lg:order-1 reveal" style="transition-delay: 200ms;">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 lg:col-span-7 order-1">
                        <div class="member-card aspect-[4/5] lg:h-full border-2 border-slate-100">
                            <img src="<?= $leadership['president']['photo'] ?>" alt="President">
                            <div class="card-label label-tl">President</div>
                        </div>
                    </div>
                    <div class="col-span-12 lg:col-span-5 order-2 flex flex-col gap-4">
                        <div class="member-card aspect-square lg:aspect-auto flex-1">
                            <img src="<?= $leadership['vice_president']['photo'] ?>" alt="VP">
                            <div class="card-label label-bl">Vice President</div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="member-card aspect-square"><img src="<?= $leadership['secretary']['photo'] ?>" alt="SEC"><div class="card-label label-bl">Secretary</div></div>
                            <div class="member-card aspect-square"><img src="<?= $leadership['treasurer']['photo'] ?>" alt="TRE"><div class="card-label label-bl">Treasurer</div></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 order-1 lg:order-2 reveal">
                <span class="text-[8px] font-black uppercase tracking-[0.5em] text-slate-500 block border-l-2 border-slate-500 pl-4 mb-6">Leadership Voice</span>
                <!-- Added Title to President Section -->
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">Council Executive Directive</span>
                <h2 class="font-druk text-3xl md:text-5xl text-slate-900 dark:text-white uppercase leading-none mb-8">
                    President's<br><span class="text-slate-500">Message</span>
                </h2>
                <p class="text-slate-500 text-xs font-medium tracking-widest leading-relaxed mb-10 italic">"<?= htmlspecialchars($leadership['president']['message']) ?>"</p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-px bg-slate-400"></div>
                    <div>
                        <p class="font-bold text-xs text-slate-900 dark:text-white uppercase tracking-tighter"><?= $leadership['president']['name'] ?></p>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest"><?= $leadership['president']['title'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- UPCOMING EVENTS (Desktop 2-col, Mobile swipe) -->
<section class="py-24 bg-white dark:bg-slate-900 border-b border-slate-100">
    <div class="container mx-auto px-6">
        <div class="flex items-end justify-between mb-12 reveal">
            <div class="blueprint-line pl-8">
                <span class="text-slate-500 text-[8px] font-black uppercase tracking-[0.4em] mb-3 block">Agenda 2026</span>
                <h2 class="font-druk text-3xl md:text-5xl">Upcoming <span class="italic text-slate-500">Events</span></h2>
            </div>
            <!-- Desktop Controls -->
            <div class="hidden md:flex items-center gap-3">
                <button onclick="scrollSlider('events-slider', 'left')" class="nav-arrow"><i class="fa-solid fa-chevron-left text-xs"></i></button>
                <button onclick="scrollSlider('events-slider', 'right')" class="nav-arrow"><i class="fa-solid fa-chevron-right text-xs"></i></button>
            </div>
        </div>

        <div id="events-slider" class="snap-carousel">
            <?php foreach ($events as $index => $e): ?>
            <div class="snap-item event-item reveal" style="transition-delay: <?= ($index * 100) ?>ms;">
                <div class="relative aspect-[16/9] mb-6 overflow-hidden eaa-radius border border-slate-200 shadow-sm group">
                    <div class="absolute top-4 left-4 z-10 bg-slate-900 text-white p-2 text-center min-w-[45px] eaa-radius">
                        <span class="block text-base font-black leading-none"><?= explode(' ', $e['date'])[0] ?></span>
                        <span class="text-[7px] font-bold opacity-60"><?= explode(' ', $e['date'])[1] ?></span>
                    </div>
                    <img src="<?= $e['img'] ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
                </div>
                <h3 class="font-druk text-lg mb-2"><?= $e['title'] ?></h3>
                <p class="text-[8px] font-black uppercase tracking-widest text-slate-400 mb-6"><?= $e['loc'] ?></p>
                <a href="#" class="inline-block bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest px-6 py-3 eaa-radius hover:bg-slate-700 transition-colors">Register Now</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- FEATURED PROJECTS (Desktop 3-col, Mobile swipe) -->
<section class="py-24 bg-slate-50 dark:bg-slate-950 border-y border-slate-100 dark:border-slate-800">
    <div class="container mx-auto px-6">
        <div class="flex items-end justify-between mb-12 reveal">
            <div class="blueprint-line pl-8">
                <span class="text-slate-500 text-[8px] font-black uppercase tracking-[0.4em] mb-3 block">Portfolio</span>
                <h2 class="font-druk text-3xl md:text-5xl">Featured <span class="italic text-slate-500">Works</span></h2>
            </div>
            <!-- Desktop Controls -->
            <div class="hidden md:flex items-center gap-3">
                <button onclick="scrollSlider('projects-slider', 'left')" class="nav-arrow"><i class="fa-solid fa-chevron-left text-xs"></i></button>
                <button onclick="scrollSlider('projects-slider', 'right')" class="nav-arrow"><i class="fa-solid fa-chevron-right text-xs"></i></button>
            </div>
        </div>
        
        <div id="projects-slider" class="snap-carousel">
            <?php foreach ($projects as $index => $p): ?>
            <div class="snap-item project-item reveal" style="transition-delay: <?= ($index * 100) ?>ms;">
                <div class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 eaa-radius overflow-hidden shadow-sm transition-all flex flex-col h-full">
                    <div class="aspect-[4/3] overflow-hidden">
                        <img src="<?= $p['image'] ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    </div>
                    <div class="p-8 flex-1 flex flex-col">
                        <h3 class="font-druk text-lg mb-2 group-hover:text-slate-600 transition-colors text-slate-900 dark:text-white"><?= $p['title'] ?></h3>
                        <p class="text-[11px] text-slate-500 font-medium leading-relaxed mb-6 line-clamp-2"><?= $p['desc'] ?></p>
                        
                        <div class="mt-auto flex flex-col gap-4">
                            <div class="flex justify-between border-t border-slate-50 dark:border-slate-800 pt-4 text-[8px] font-black uppercase tracking-widest text-slate-400">
                                <span><?= $p['location'] ?></span><span><?= $p['budget'] ?></span>
                            </div>
                            <a href="contact.php" class="text-center py-3 border border-slate-200 text-[9px] font-black uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all eaa-radius">Enquire Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- JOURNAL / BLOGS (Standard layout preserved) -->
<section class="py-24 bg-white dark:bg-slate-900 border-b border-slate-100">
    <div class="container mx-auto px-6">
        <div class="flex items-end justify-between mb-12 reveal">
            <div class="blueprint-line pl-8">
                <span class="text-slate-500 text-[8px] font-black uppercase tracking-[0.4em] mb-3 block">Journal</span>
                <h2 class="font-druk text-3xl md:text-5xl">Architectural <span class="italic text-slate-500">Blogs</span></h2>
            </div>
            <a href="blog.php" class="text-[9px] font-black uppercase tracking-widest border-b border-slate-900 pb-1">All Articles</a>
        </div>
        <div class="grid md:grid-cols-2 gap-10">
            <?php foreach ($blogs as $b): ?>
            <div class="reveal flex flex-col md:flex-row gap-8 items-center group">
                <div class="w-full md:w-1/2 aspect-video overflow-hidden eaa-radius border border-slate-200 shadow-sm">
                    <img src="<?= $b['img'] ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                </div>
                <div class="w-full md:w-1/2">
                    <span class="text-[7px] font-black uppercase tracking-widest text-slate-500 block mb-2"><?= $b['date'] ?></span>
                    <h3 class="font-druk text-xl text-slate-900 dark:text-white group-hover:text-slate-600 transition-colors leading-tight"><?= $b['title'] ?></h3>
                    <a href="#" class="mt-4 text-[8px] font-black uppercase tracking-widest flex items-center gap-2 group-hover:gap-4 transition-all">Read Story <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="2"/></svg></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- OUR SPONSORS SECTION (Standard layout preserved) -->
<section class="py-24 bg-slate-50 dark:bg-slate-950 border-t border-slate-100">
    <div class="container mx-auto px-6 relative z-10">
        <div class="mb-16 reveal blueprint-line pl-8">
            <span class="text-slate-500 text-[8px] font-black uppercase tracking-[0.4em] mb-3 block">Collaboration</span>
            <h2 class="font-druk text-3xl md:text-5xl">Our <span class="italic text-slate-500">Sponsors</span></h2>
        </div>
        
        <div class="py-12 bg-white dark:bg-slate-900 eaa-radius overflow-hidden border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="animate-marquee">
                <?php foreach (array_merge($sponsors, $sponsors, $sponsors) as $s): ?>
                <div class="flex items-center px-12 gap-6">
                    <div class="flex items-center gap-4 group">
                        <div class="w-1.5 h-1.5 bg-slate-300 rounded-full"></div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] hover:text-slate-800 dark:hover:text-white transition-colors cursor-default"><?= $s ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . "/partials/footer.php"; ?>

<script>
    /**
     * EAA DASHBOARD & HERO LOGIC
     */
    (function() {
        /* 1. PRELOADER */
        function openWebsite() {
            const preloader = document.getElementById('main-preloader');
            if (preloader && !preloader.classList.contains('fade-out')) {
                preloader.classList.add('fade-out');
                initReveal();
            }
        }
        const safetyReveal = setTimeout(openWebsite, 4000);
        window.addEventListener('load', () => {
            clearTimeout(safetyReveal);
            setTimeout(openWebsite, 2800); 
        });

        /* 2. HERO SLIDESHOW (3s Interval) */
        let curSlide = 0;
        const slides = document.querySelectorAll('.slide-img');
        function setSlide(index) {
            if (!slides || slides.length === 0) return;
            slides[curSlide].style.opacity = '0';
            curSlide = index;
            slides[curSlide].style.opacity = '1'; 
        }
        if (slides && slides.length > 0) {
            setInterval(() => {
                let next = (curSlide + 1) % slides.length;
                setSlide(next);
            }, 3000);
        }

        /* 3. CAROUSEL CONTROL */
        window.scrollSlider = function(id, direction) {
            const el = document.getElementById(id);
            const scrollAmount = el.offsetWidth * 0.8;
            if (direction === 'left') {
                el.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            } else {
                el.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
        };

        /* 4. REVEAL LOGIC */
        function initReveal() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('active'); });
            }, { threshold: 0.1 });
            document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
        }
    })();
</script>