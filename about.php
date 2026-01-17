<?php
/* =========================================================
   about.php — ARCHITECTURAL LEDGER LAYOUT
   ✅ Reverted Values Section to 2-Column Asymmetric Layout
   ✅ Added Hover Highlight Effect to Values
   ✅ Optimized "Ledger" Timeline for Density
   ✅ FIXED: Stacking "Minimize" effect with smooth height transitions
   ✅ Natural colors only (No grayscale)
   ✅ Standardized 5px Radius Throughout
   ✅ TYPOGRAPHY: Exclusive use of Montserrat font
   ========================================================= */

require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/config/db.php';

start_session();

$pageTitle = 'About Our Guild | Erode Architect Association';
require_once __DIR__ . "/partials/header.php";
?>

<style>
    :root {
        --eaa-smoke: #475569;
        --eaa-border: #e2e8f0;
        --eaa-radius: 5px;
    }

    body {
        background-color: #f8fafc;
        color: #1e293b;
        transition: background-color 0.3s ease;
    }

    .font-druk {
        font-family: 'Montserrat', sans-serif;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: -0.05em;
        line-height: 0.85;
    }

    /* Standardized Radius */
    .eaa-radius { border-radius: var(--eaa-radius) !important; }

    /* Blueprint Background Elements */
    .blueprint-grid {
        background-image: linear-gradient(rgba(71, 85, 105, 0.05) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(71, 85, 105, 0.05) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    /* Values Hover Effect */
    .value-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid var(--eaa-border);
        background: rgba(255, 255, 255, 0.5);
    }
    .value-card:hover {
        border-color: #1e293b;
        background: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    .value-card:hover .value-line {
        width: 100%;
        background: #1e293b;
    }

    /* Ledger Timeline Styling */
    .ledger-container {
        max-width: 1100px;
        margin: 0 auto;
        position: relative;
    }

    .ledger-line {
        position: absolute;
        left: 120px;
        top: 0;
        bottom: 0;
        width: 1px;
        background: var(--eaa-border);
    }

    .ledger-row {
        display: grid;
        grid-template-columns: 100px 40px 1fr;
        gap: 0;
        margin-bottom: 40px; /* Reduced base margin to avoid gaps */
        align-items: start;
        position: relative;
        transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        min-height: 100px; /* Prevents jumping */
    }

    /* Content visibility states */
    .ledger-row.active {
        margin-bottom: 100px;
    }

    .ledger-year {
        font-family: 'Montserrat', sans-serif;
        font-weight: 900;
        font-size: 1.8rem;
        color: #cbd5e1;
        text-align: right;
        padding-right: 25px;
        line-height: 1;
        position: sticky;
        top: 150px;
        transition: all 0.5s ease;
    }

    .ledger-row.active .ledger-year {
        color: #1e293b;
        transform: scale(1.1);
    }
    
    .ledger-row.passed .ledger-year {
        color: #94a3b8;
        font-size: 1.2rem;
    }

    .ledger-dot-box {
        display: flex;
        justify-content: center;
        position: relative;
        z-index: 10;
        height: 100%;
    }

    .ledger-dot {
        width: 14px;
        height: 14px;
        background: #cbd5e1;
        border-radius: 2px;
        border: 3px solid #f8fafc;
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        position: sticky;
        top: 158px;
    }

    .ledger-row.active .ledger-dot {
        background: #1e293b;
        transform: scale(1.3) rotate(45deg);
    }

    .ledger-content {
        padding-left: 50px;
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 50px;
        transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .ledger-card-img {
        aspect-ratio: 3 / 4;
        width: 100%;
        overflow: hidden;
        border: 1px solid var(--eaa-border);
        border-radius: var(--eaa-radius);
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        transform: translateY(20px);
        opacity: 0;
        max-height: 0;
    }

    .ledger-row.active .ledger-card-img {
        transform: translateY(0);
        opacity: 1;
        max-height: 500px;
        margin-bottom: 20px;
    }

    .ledger-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ledger-text {
        padding-top: 10px;
        transition: all 0.5s ease;
    }

    .ledger-title {
        font-family: 'Montserrat', sans-serif;
        font-weight: 800;
        font-size: 1.2rem; /* Start small */
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 5px;
        color: #94a3b8;
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .ledger-row.active .ledger-title {
        font-size: 1.8rem;
        color: #1e293b;
        margin-bottom: 20px;
    }

    .ledger-row.passed .ledger-title {
        font-size: 1.1rem;
        color: #64748b;
    }

    .ledger-desc {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        line-height: 1.9;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        max-width: 480px;
        text-align: justify;
        opacity: 0;
        max-height: 0;
        overflow: hidden;
        transition: all 0.5s ease;
    }

    .ledger-row.active .ledger-desc {
        opacity: 1;
        max-height: 200px;
        margin-bottom: 30px;
    }

    .ledger-meta {
        opacity: 0;
        transition: all 0.5s ease;
    }

    .ledger-row.active .ledger-meta {
        opacity: 0.3;
    }

    /* Core Reveal System */
    .reveal { 
        opacity: 0; 
        transform: translateY(20px); 
        transition: opacity 0.8s ease, transform 0.8s cubic-bezier(0.2, 0.8, 0.2, 1); 
    }
    .reveal.active { 
        opacity: 1; 
        transform: translateY(0); 
    }

    @media (max-width: 1024px) {
        .ledger-content { grid-template-columns: 1fr; gap: 10px; padding-left: 20px; }
        .ledger-row { grid-template-columns: 70px 30px 1fr; }
        .ledger-year { font-size: 1.2rem; padding-right: 15px; top: 120px; }
        .ledger-line { left: 85px; }
        .ledger-card-img { max-width: 280px; }
        .ledger-dot { top: 128px; }
    }
</style>

<!-- HEADER SECTION -->
<section class="pt-44 pb-20 relative overflow-hidden bg-white border-b border-slate-100">
    <div class="absolute inset-0 blueprint-grid opacity-20 pointer-events-none"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl">
            <span class="text-[8px] font-black uppercase tracking-[0.5em] text-slate-400 block border-l-2 border-slate-400 pl-4 mb-6">Masterplan / 01</span>
            <h1 class="font-druk text-5xl md:text-8xl text-slate-900 leading-none mb-10">
                Architectural <br><span class="text-slate-400">Integrity</span>
            </h1>
            <p class="max-w-2xl text-slate-500 text-xs md:text-sm font-bold uppercase tracking-widest leading-loose text-justify">
                The Erode Architect Association serves as a critical junction where regional wisdom meets modern urban evolution. We are the collective voice of design professionals committed to creating a sustainable, context-driven future for Tamil Nadu.
            </p>
        </div>
    </div>
</section>

<!-- VALUES SECTION -->
<section class="py-32 bg-slate-50 border-b border-slate-100 relative">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-[1.2fr_1fr] gap-20 items-start">
            
            <div class="reveal">
                <span class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-400 mb-4 block">Core Principles</span>
                <h2 class="font-druk text-5xl md:text-7xl leading-none mb-8 text-slate-900">Our<br><span class="text-slate-400 italic">Values</span></h2>
                <p class="text-xs font-bold uppercase tracking-widest text-slate-500 leading-loose max-w-sm text-justify">
                    Our values guide every technical workshop, design critique, and community initiative we undertake. We prioritize precision over speed and integrity over trends.
                </p>

                <div class="flex items-center gap-4 mt-12">
                    <div class="w-12 h-12 rounded-full border border-slate-200 flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 14l-7 7-7-7" stroke-width="2"/></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Scroll to Explore Archive</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <?php
                $values = [
                    ['t' => 'Community', 'd' => 'Putting regional needs at the heart of every blueprint.'],
                    ['t' => 'Integrity', 'd' => 'Upholding strict ethical construction standards.'],
                    ['t' => 'Learning', 'd' => 'Continuous evolution through technical exchange.'],
                    ['t' => 'Heritage', 'd' => 'Modernizing the soul of traditional spatial design.']
                ];
                foreach($values as $v):
                ?>
                <div class="value-card eaa-radius p-8 reveal">
                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 leading-relaxed mb-6"><?= $v['d'] ?></p>
                    <div class="value-line h-px w-8 bg-slate-200 mb-4 transition-all duration-500"></div>
                    <p class="text-xs font-black uppercase tracking-widest text-slate-900"><?= $v['t'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- THE ARCHITECTURAL LEDGER (Sticky Pivot Timeline with Stacking Animation) -->
<section class="py-32 bg-white relative overflow-hidden">
    <div class="absolute left-10 top-0 bottom-0 w-px bg-slate-100 hidden xl:block"></div>
    <div class="absolute right-10 top-0 bottom-0 w-px bg-slate-100 hidden xl:block"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="mb-24 reveal text-center lg:text-left">
            <span class="text-[8px] font-black uppercase tracking-[0.5em] text-slate-400 mb-4 block">Chronicle Record</span>
            <h2 class="font-druk text-4xl md:text-6xl text-slate-900">EAA <span class="text-slate-400 italic">Timeline</span></h2>
        </div>

        <div class="ledger-container">
            <div class="ledger-line"></div>

            <?php
            $timeline = [
                ['y' => '1985', 't' => 'The Foundation', 'd' => 'EAA established by visionary architects to standardize professional design practices in the Erode region.', 'img' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=800'],
                ['y' => '1992', 't' => 'Guild Hall Opening', 'd' => 'The inauguration of our permanent headquarters, serving as a landmark of regional cooperation.', 'img' => 'https://images.unsplash.com/photo-1449247709967-d4461a6a6103?q=80&w=800'],
                ['y' => '2005', 't' => 'Digital Transition', 'd' => 'Full-scale adoption of BIM and CAD modeling standards across all member practices.', 'img' => 'https://images.unsplash.com/photo-1529421308418-eab98863cee1?q=80&w=800'],
                ['y' => '2015', 't' => 'Eco-Centric Mandate', 'd' => 'Launch of regional green initiatives focused on passive cooling and resource efficiency.', 'img' => 'https://images.unsplash.com/photo-1503387762-592dea58ea2e?q=80&w=800'],
                ['y' => '2023', 't' => 'Modern Milestone', 'd' => 'Crossed 500 members, cementing our position as the primary authority on regional urban design.', 'img' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=800']
            ];

            foreach($timeline as $item):
            ?>
            <div class="ledger-row reveal">
                <div class="ledger-year"><?= $item['y'] ?></div>
                <div class="ledger-dot-box"><div class="ledger-dot"></div></div>
                <div class="ledger-content">
                    <div class="ledger-card-img">
                        <img src="<?= $item['img'] ?>" alt="Timeline Event">
                    </div>
                    <div class="ledger-text">
                        <h3 class="ledger-title"><?= $item['t'] ?></h3>
                        <p class="ledger-desc"><?= $item['d'] ?></p>
                        <div class="ledger-meta mt-12 flex items-center gap-6">
                            <span class="text-[7px] font-black uppercase tracking-widest text-slate-400">Arch-Ref: <?= $item['y'] ?></span>
                            <div class="h-px flex-1 bg-slate-300"></div>
                            <span class="text-[7px] font-black uppercase tracking-widest text-slate-400">Seq_0<?= round($item['y']/1000, 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- SPONSORS MARQUEE -->
<!-- <section class="py-16 bg-slate-900 overflow-hidden border-y border-white/5">
    <div class="animate-marquee">
        <?php
        $sponsors = ['Steel India', 'Cement Corp', 'Glass Works', 'Timber Plus', 'Paint Masters', 'Tiles Pro'];
        foreach (array_merge($sponsors, $sponsors, $sponsors) as $s): 
        ?>
        <div class="flex items-center px-16 gap-6">
            <span class="text-[10px] font-black text-white/30 uppercase tracking-[0.3em] hover:text-white transition-colors cursor-default"><?= $s ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</section> -->

<?php require_once __DIR__ . "/partials/footer.php"; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const revealElements = document.querySelectorAll('.reveal');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const rect = entry.boundingClientRect;
                const windowHeight = window.innerHeight;
                const isTimelineRow = entry.target.classList.contains('ledger-row');

                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    if (isTimelineRow) {
                        entry.target.classList.remove('passed');
                    }
                } else {
                    if (isTimelineRow && rect.top < 100) {
                        entry.target.classList.add('passed');
                        entry.target.classList.remove('active');
                    }
                    if (rect.top > windowHeight) {
                        entry.target.classList.remove('active');
                        entry.target.classList.remove('passed');
                    }
                }
            });
        }, {
            threshold: [0, 0.2, 0.5, 0.8, 1],
            rootMargin: "0px 0px -10% 0px"
        });

        revealElements.forEach(el => observer.observe(el));
    });
</script>