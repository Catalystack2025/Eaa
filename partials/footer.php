<?php
/* =========================================================
   partials/footer.php — IMPROVED TECHNICAL FOOTER
   ✅ High-density "Technical Ledger" Layout
   ✅ Root Logo Integration (logo.svg)
   ✅ Full Page Navigation & Legal Nodes
   ✅ Newsletter Subscription Sync
   ✅ Scoped Scripts for Preloader & Slideshow
   ========================================================= */
?>

<footer class="bg-[#0a0f1d] text-white pt-24 pb-12 relative overflow-hidden border-t border-white/5">
    <!-- Background Blueprint Overlay -->
    <div class="absolute inset-0 opacity-5 pointer-events-none" 
         style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), 
                linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px); 
                background-size: 40px 40px;"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 mb-24 text-left">
            
            <!-- COLUMN 1: ASSOCIATION IDENTITY -->
            <div class="reveal">
                <div class="flex items-center gap-4 mb-8">
                    <img src="/logo.svg" alt="EAA Logo" class="h-12 w-auto brightness-200" onerror="this.src='public/EAA_logo.png';">
                    <div>
                        <span class="font-druk text-xl leading-none block uppercase">Erode</span>
                        <span class="text-[7px] font-black uppercase tracking-widest text-white/30">Architect Association</span>
                    </div>
                </div>
                <p class="text-[10px] font-medium text-white/40 uppercase tracking-widest leading-loose mb-10 text-justify">
                    Established 1985. We represent the unified technical and design voice of the Erode architecture community, fostering regional excellence and structural integrity.
                </p>
                <!-- Social Nodes -->
                <div class="flex gap-3">
                    <a href="#" class="w-9 h-9 border border-white/10 rounded-[5px] flex items-center justify-center hover:bg-white hover:text-[#0a0f1d] transition-all"><i class="fa-brands fa-whatsapp text-xs"></i></a>
                    <a href="#" class="w-9 h-9 border border-white/10 rounded-[5px] flex items-center justify-center hover:bg-white hover:text-[#0a0f1d] transition-all"><i class="fa-brands fa-instagram text-xs"></i></a>
                    <a href="#" class="w-9 h-9 border border-white/10 rounded-[5px] flex items-center justify-center hover:bg-white hover:text-[#0a0f1d] transition-all"><i class="fa-brands fa-linkedin-in text-xs"></i></a>
                </div>
            </div>

            <!-- COLUMN 2: PAGE NAVIGATION -->
            <div class="reveal" style="transition-delay: 100ms;">
                <h4 class="font-druk text-sm mb-10 text-white border-l-2 border-slate-500 pl-4 uppercase">Navigation</h4>
                <nav class="grid grid-cols-2 gap-x-4 gap-y-4">
                    <a href="testing.php" class="footer-link-tech">Home</a>
                    <a href="about.php" class="footer-link-tech">About</a>
                    <a href="teams.php" class="footer-link-tech">Teams</a>
                    <a href="blog.php" class="footer-link-tech">Journal</a>
                    <a href="events.php" class="footer-link-tech">Events</a>
                    <a href="career.php" class="footer-link-tech">Career</a>
                    <a href="vendors.php" class="footer-link-tech">Connect</a>
                    <a href="contact.php" class="footer-link-tech">Contact</a>
                </nav>
            </div>

            <!-- COLUMN 3: LEGAL & PROTOCOLS -->
            <div class="reveal" style="transition-delay: 200ms;">
                <h4 class="font-druk text-sm mb-10 text-white border-l-2 border-slate-500 pl-4 uppercase">Legal Nodes</h4>
                <nav class="space-y-4">
                    <a href="bylaws.php" class="footer-link-tech">Association By-Laws</a>
                    <a href="terms.php" class="footer-link-tech">Terms & Conditions</a>
                    <a href="privacy.php" class="footer-link-tech">Privacy Protocol</a>
                    <a href="join.php" class="footer-link-tech">Registry Application</a>
                </nav>
            </div>

            <!-- COLUMN 4: TERMINAL SYNC (NEWSLETTER) -->
            <div class="reveal" style="transition-delay: 300ms;">
                <h4 class="font-druk text-sm mb-10 text-white border-l-2 border-slate-500 pl-4 uppercase">Terminal Sync</h4>
                <p class="text-[9px] font-black uppercase tracking-widest text-white/30 mb-6">Subscribe to technical updates & agenda</p>
                <form class="flex flex-col gap-3">
                    <input type="email" placeholder="ADMIN@NODE.COM" class="bg-white/5 border border-white/10 rounded-[5px] p-4 text-[10px] uppercase tracking-widest outline-none focus:border-white/30 transition-all text-white">
                    <button class="bg-white text-[#0a0f1d] py-4 rounded-[5px] text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 transition-all shadow-lg">Sync Email</button>
                </form>
                <!-- Coordinate Sticker -->
                <div class="mt-8 opacity-20">
                    <span class="text-[7px] font-black uppercase tracking-[0.4em]">11.34N 77.71E // ROOT_STABLE</span>
                </div>
            </div>
        </div>

        <!-- FOOTER BOTTOM -->
        <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-6">
            <span class="text-[8px] font-black uppercase tracking-widest text-white/20">
                © 2026 Erode Architect Association. Developed for the Regional Guild.
            </span>
            <div class="flex gap-8 text-[8px] font-black uppercase tracking-widest text-white/10">
                <span class="cursor-default">Security: AES_256_ACTIVE</span>
                <span class="cursor-default">Node: TN_ERODE_01</span>
                <span class="cursor-default">V 1.0.4 stable</span>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer-link-tech {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: rgba(255,255,255,0.4);
        transition: all 0.3s ease;
        display: block;
    }
    .footer-link-tech:hover { color: #fff; transform: translateX(5px); }
</style>

<script>
    /**
     * EAA CORE SITE LOGIC (IIFE)
     */
    (function() {
        /* 1. PRELOADER HIDE LOGIC */
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
            setTimeout(openWebsite, 2200); 
        });

        /* 2. HERO SLIDESHOW LOGIC (3s Interval) */
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

        /* 3. CAROUSEL NAVIGATION */
        window.scrollSlider = function(id, direction) {
            const el = document.getElementById(id);
            if (!el) return;
            const scrollAmount = el.offsetWidth * 0.8;
            if (direction === 'left') {
                el.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            } else {
                el.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
        };

        /* 4. REVEAL OBSERVER */
        function initReveal() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('active'); });
            }, { threshold: 0.1 });
            document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
        }
    })();
</script>