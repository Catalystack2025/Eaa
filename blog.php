<?php
/* =========================================================
   post.php — ARCHITECTURAL JOURNAL DETAIL
   ✅ Exclusive Montserrat Typography
   ✅ High-density "Technical Ledger" layout
   ✅ 5px Radius & Smoke Grey palette
   ✅ Blueprint annotations & measurement lines
   ✅ Natural colors (No grayscale)
   ========================================================= */

require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/config/db.php';

start_session();

$pageTitle = 'Vertical Forests | EAA Journal';
require_once __DIR__ . "/partials/header.php";

// Mock Article Data
$article = [
    "title" => "Vertical Forests: Future Urban Centers",
    "category" => "Sustainability",
    "date" => "Jan 15, 2026",
    "author" => "Ar. Suresh Kumar",
    "role" => "Principal Architect",
    "ref" => "EAA-JRNL-2026-004",
    "image" => "https://images.unsplash.com/photo-1486718448742-163732cd1544?w=1600&q=80"
];
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
        font-family: 'Montserrat', sans-serif;
    }

    .font-druk {
        font-family: 'Montserrat', sans-serif;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: -0.05em;
        line-height: 0.85;
    }

    .eaa-radius { border-radius: var(--eaa-radius) !important; }

    /* Blueprint Background Elements */
    .blueprint-grid {
        background-image: linear-gradient(rgba(71, 85, 105, 0.05) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(71, 85, 105, 0.05) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    /* Article Hero */
    .article-hero {
        position: relative;
        height: 60vh;
        min-height: 500px;
        overflow: hidden;
    }

    .article-hero img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Technical Callouts */
    .tech-specs {
        border-left: 1px solid var(--eaa-smoke);
        padding-left: 20px;
        margin-bottom: 30px;
    }

    .spec-label {
        font-size: 8px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: #94a3b8;
        display: block;
        margin-bottom: 4px;
    }

    .spec-value {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        color: #1e293b;
    }

    /* Typography Overrides for Article */
    .article-content p {
        font-size: 14px;
        line-height: 1.8;
        margin-bottom: 2rem;
        color: #475569;
        text-align: justify;
    }

    .article-content h2 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 900;
        font-size: 1.5rem;
        text-transform: uppercase;
        margin-top: 3rem;
        margin-bottom: 1.5rem;
        color: #1e293b;
        letter-spacing: 0.05em;
    }

    /* Pullquote */
    .pullquote {
        border-top: 1px solid var(--eaa-border);
        border-bottom: 1px solid var(--eaa-border);
        padding: 40px 0;
        margin: 60px 0;
        position: relative;
    }

    .pullquote-text {
        font-family: 'Montserrat', sans-serif;
        font-weight: 800;
        font-size: 1.25rem;
        text-transform: uppercase;
        line-height: 1.4;
        color: #1e293b;
        font-style: italic;
    }

    /* Sidebar Sticky */
    .sidebar-sticky {
        position: sticky;
        top: 120px;
    }

    /* Animation Reveal */
    .reveal { opacity: 0; transform: translateY(20px); transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1); }
    .reveal.active { opacity: 1; transform: translateY(0); }

</style>

<!-- ARTICLE TOP HEADER -->
<section class="pt-44 pb-12 bg-white relative overflow-hidden border-b border-slate-100">
    <div class="absolute inset-0 blueprint-grid opacity-20 pointer-events-none"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-5xl">
            <div class="reveal flex flex-wrap items-center gap-6 mb-8">
                <span class="inline-flex items-center px-3 py-1 border border-slate-200 text-[8px] font-black uppercase tracking-[0.3em] text-slate-500 eaa-radius">
                    <?= $article['category'] ?>
                </span>
                <span class="text-[8px] font-black uppercase tracking-[0.3em] text-slate-300"><?= $article['date'] ?></span>
                <span class="text-[8px] font-black uppercase tracking-[0.3em] text-slate-300">REF: <?= $article['ref'] ?></span>
            </div>
            
            <h1 class="font-druk text-4xl md:text-7xl lg:text-8xl text-slate-900 leading-none mb-10 reveal">
                <?= $article['title'] ?>
            </h1>

            <div class="flex items-center gap-6 reveal" style="transition-delay: 200ms;">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&q=80" class="w-12 h-12 eaa-radius object-cover" alt="Author">
                <div>
                    <span class="block text-[10px] font-black uppercase tracking-widest text-slate-900"><?= $article['author'] ?></span>
                    <span class="text-[8px] font-bold uppercase tracking-widest text-slate-400"><?= $article['role'] ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURED IMAGE -->
<section class="container mx-auto px-6 -mt-10 relative z-20 reveal" style="transition-delay: 300ms;">
    <div class="article-hero eaa-radius shadow-2xl border border-white">
        <img src="<?= $article['image'] ?>" alt="Main Visual">
        <div class="absolute bottom-6 right-6">
            <span class="text-[7px] font-black uppercase tracking-widest text-white/50 bg-black/20 backdrop-blur-md px-3 py-2 eaa-radius">Coordinates: 11.3410° N, 77.7172° E</span>
        </div>
    </div>
</section>

<!-- ARTICLE MAIN CONTENT -->
<section class="py-24 bg-white">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
            
            <!-- Sidebar Specs -->
            <div class="lg:col-span-3 order-2 lg:order-1">
                <div class="sidebar-sticky">
                    <div class="tech-specs reveal">
                        <span class="spec-label">Project Type</span>
                        <span class="spec-value">Urban Integration</span>
                    </div>
                    <div class="tech-specs reveal" style="transition-delay: 100ms;">
                        <span class="spec-label">Climate Zone</span>
                        <span class="spec-value">Tropical / Humid</span>
                    </div>
                    <div class="tech-specs reveal" style="transition-delay: 200ms;">
                        <span class="spec-label">Sustainability Grade</span>
                        <span class="spec-value">A++ / Certified</span>
                    </div>
                    
                    <div class="mt-16 reveal" style="transition-delay: 300ms;">
                        <span class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 block mb-6">Share Report</span>
                        <div class="flex gap-4">
                            <a href="#" class="w-10 h-10 border border-slate-200 eaa-radius flex items-center justify-center hover:bg-slate-900 hover:text-white transition-all"><i class="fa-brands fa-whatsapp text-xs"></i></a>
                            <a href="#" class="w-10 h-10 border border-slate-200 eaa-radius flex items-center justify-center hover:bg-slate-900 hover:text-white transition-all"><i class="fa-brands fa-linkedin-in text-xs"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Body -->
            <div class="lg:col-span-7 order-1 lg:order-2 article-content reveal" style="transition-delay: 100ms;">
                <p class="font-bold text-slate-900 italic text-lg leading-relaxed">
                    In the heart of Erode, the urban heat island effect has become a critical concern for planners and residents alike. Traditional concrete structures absorb vast amounts of solar radiation, radiating heat long after the sun has set.
                </p>

                <p>
                    Our latest architectural initiative focuses on 'Vertical Forests'—high-density residential and commercial towers wrapped in over 20,000 square feet of native vegetation. This isn't merely an aesthetic choice; it is a thermal management strategy that can reduce internal building temperatures by up to 8 degrees Celsius. 
                </p>

                <div class="my-16 grid grid-cols-2 gap-4">
                    <div class="aspect-square eaa-radius overflow-hidden border border-slate-100">
                        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=600&q=80" class="w-full h-full object-cover" alt="Detail 1">
                    </div>
                    <div class="aspect-square eaa-radius overflow-hidden border border-slate-100">
                        <img src="https://images.unsplash.com/photo-1503387762-592dea58ea2e?w=600&q=80" class="w-full h-full object-cover" alt="Detail 2">
                    </div>
                </div>

                <h2>Structural Integrity</h2>
                <p>
                    Beyond the immediate environmental impacts, vertical forests represent a structural challenge that forces us to rethink load-bearing calculations and moisture barrier technologies. Every terrace is engineered to hold specific soil depths tailored to indigenous plant root systems.
                </p>

                <div class="pullquote reveal">
                    <p class="pullquote-text">
                        "The goal is not to decorate the building with plants, but to allow the building to function as a living, breathing ecosystem within the city grid."
                    </p>
                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400 mt-4 block">— EAA Technical Review Board</span>
                </div>

                <p>
                    By utilizing greywater recycling systems, these living facades provide natural insulation, air filtration, and noise reduction. Furthermore, the psychological impact of biophilic design on Erode's workspace productivity cannot be overstated. 
                </p>

                <div class="mt-20 pt-10 border-t border-slate-100 flex items-center justify-between">
                    <div class="flex gap-2">
                        <span class="px-3 py-1 bg-slate-50 text-[8px] font-black uppercase tracking-widest text-slate-500 eaa-radius">Eco-Design</span>
                        <span class="px-3 py-1 bg-slate-50 text-[8px] font-black uppercase tracking-widest text-slate-500 eaa-radius">Urban Planning</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- RELATED POSTS -->
<section class="py-24 bg-slate-50 border-t border-slate-100">
    <div class="container mx-auto px-6">
        <div class="flex items-end justify-between mb-16 reveal">
            <div>
                <span class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-400 mb-4 block">Archive Record</span>
                <h2 class="font-druk text-3xl md:text-5xl">Related <span class="text-slate-400 italic">Insights</span></h2>
            </div>
            <a href="blog.php" class="text-[9px] font-black uppercase tracking-widest border-b border-slate-900 pb-1">View Full Journal</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php for($i=0; $i<3; $i++): ?>
            <a href="#" class="group reveal" style="transition-delay: <?= $i*100 ?>ms;">
                <div class="aspect-[16/10] overflow-hidden eaa-radius mb-6 shadow-sm border border-slate-200">
                    <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=600&q=80" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                </div>
                <span class="text-[8px] font-black uppercase tracking-widest text-slate-400 block mb-2">Dec 2025</span>
                <h3 class="font-druk text-lg text-slate-900 group-hover:text-slate-500 transition-colors leading-tight">Adapting Brutalist Aesthetics for Erode</h3>
            </a>
            <?php endfor; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . "/partials/footer.php"; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('active'); });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    });
</script>