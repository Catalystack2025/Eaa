<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Erode Architect Association</title>
    
    <!-- Google Fonts: Montserrat Only -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                        body: ['Montserrat', 'sans-serif'],
                    },
                    colors: {
                        background: 'hsl(var(--background))',
                        foreground: 'hsl(var(--foreground))',
                        primary: 'hsl(var(--primary))',
                        card: 'hsl(var(--card))',
                        border: 'hsl(var(--border))',
                        charcoal: 'hsl(var(--charcoal))',
                    }
                }
            }
        }
    </script>

    <style type="text/css">
        :root {
            --background: 210 25% 98%;
            --foreground: 220 20% 18%;
            --primary: 197 13% 43%;
            --card: 210 20% 96%;
            --border: 210 12% 86%;
            --charcoal: 220 20% 12%;
            --blueprint: 198 12% 48%;
            --gold-gradient: linear-gradient(135deg, #d4af37 0%, #f9e29d 50%, #b8860b 100%);
        }

        .dark {
            --background: 220 18% 10%;
            --foreground: 210 20% 92%;
            --primary: 197 14% 55%;
            --card: 220 16% 14%;
            --border: 220 12% 24%;
        }

        body {
            background-color: hsl(var(--background));
            color: hsl(var(--foreground));
            transition: background-color 0.3s ease;
            overflow-x: hidden;
        }

        .font-druk {
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -0.05em;
            line-height: 0.9;
        }

        .blueprint-grid {
            background-image: linear-gradient(hsl(var(--blueprint) / 0.08) 1px, transparent 1px),
                              linear-gradient(90deg, hsl(var(--blueprint) / 0.08) 1px, transparent 1px);
            background-size: 30px 30px;
        }

        /* Fixed Navigation */
        #main-header { transition: all 0.4s ease; background-color: hsl(var(--background) / 0.95); backdrop-filter: blur(12px); border-bottom: 1px solid hsl(var(--border)); }
        #sticky-logo-container { width: 50px; opacity: 1; transform: scale(1); margin: 0 1.5rem; pointer-events: auto; }

        /* MANDATORY 4% RADIUS DESIGN */
        .architect-card {
            border-radius: 4%;
            overflow: hidden;
            background: hsl(var(--card));
            border: 1px solid hsl(var(--border));
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .architect-card:hover {
            border-color: hsl(var(--primary) / 0.4);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        }

        .bg-gradient-gold {
            background: var(--gold-gradient);
            background-size: 200% auto;
            transition: 0.5s;
        }
        .bg-gradient-gold:hover {
            background-position: right center;
        }

        .reveal { opacity: 0; transform: translateY(20px); transition: all 0.6s ease-out; }
        .reveal.active { opacity: 1; transform: translateY(0); }

        .measurement-line {
            width: 1px;
            background: linear-gradient(to bottom, transparent, hsl(var(--primary) / 0.3), transparent);
            height: 60px;
        }

        /* Form Inputs Reset to Match Architectural Style */
        input, textarea {
            border-radius: 8px; /* Slightly rounded for inputs to contrast with 4% cards */
        }
    </style>
</head>
<body class="antialiased font-sans">

    <!-- NAVBAR -->
    <header id="main-header" class="fixed top-0 left-0 right-0 z-50 py-3">
        <nav class="container mx-auto px-6">
            <div class="hidden lg:flex items-center justify-center relative text-foreground">
                <div id="left-nav" class="flex items-center gap-8">
                    <a href="index.php" class="text-[9px] font-bold tracking-widest uppercase text-slate-400 hover:text-primary transition-colors">Home</a>
                    <a href="about.php" class="text-[9px] font-bold tracking-widest uppercase text-slate-400 hover:text-primary transition-colors">About</a>
                    <a href="teams.php" class="text-[9px] font-bold tracking-widest uppercase text-slate-400 hover:text-primary transition-colors">Teams</a>
                </div>
                <div id="sticky-logo-container" class="overflow-hidden flex items-center justify-center transition-all duration-500">
                    <a href="index.php"><img src="public/EAA_logo.png" alt="EAA" class="h-9 w-auto"></a>
                </div>
                <div id="right-nav" class="flex items-center gap-8">
                    <a href="calendar.php" class="text-[9px] font-bold tracking-widest uppercase text-slate-400 hover:text-primary transition-colors">Events</a>
                    <a href="contact.php" class="text-[9px] font-bold tracking-widest uppercase text-primary underline underline-offset-8">Contact</a>
                    <div class="flex items-center gap-3 ml-4 border-l border-border pl-6">
                        <button onclick="toggleTheme()" class="p-1.5 border border-border rounded-lg hover:bg-primary/5 transition-all">
                            <svg id="sun-icon" class="w-3.5 h-3.5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2"/></svg>
                            <svg id="moon-icon" class="w-3.5 h-3.5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" stroke-width="2"/></svg>
                        </button>
                        <a href="/auth/login.php" class="px-4 py-1.5 text-[8px] font-bold border border-border rounded-lg hover:bg-primary hover:text-white transition-all uppercase tracking-widest">Login</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- CONTACT HERO -->
    <section class="pt-40 pb-16 relative overflow-hidden">
        <div class="absolute inset-0 blueprint-grid opacity-30"></div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center max-w-3xl mx-auto reveal">
                <span class="inline-block px-4 py-2 bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-[0.4em] mb-6 border border-primary/10 rounded-full">
                    Get in Touch
                </span>
                <h1 class="font-druk text-4xl md:text-6xl text-foreground mb-6">
                    Contact <span class="text-primary">Us</span>
                </h1>
                <p class="text-slate-500 text-xs md:text-sm font-medium uppercase tracking-widest leading-relaxed">
                    Have questions about membership, events, or collaboration? We’d love to hear from you and build the future of Erode together.
                </p>
            </div>
        </div>
    </section>

    <!-- CONTACT CONTENT -->
    <section class="py-16 relative">
        <div class="container mx-auto px-6 max-w-6xl">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20">
                
                <!-- OFFICE INFO -->
                <div class="reveal">
                    <h2 class="font-druk text-2xl mb-10 text-foreground">
                        Office <span class="text-primary">Information</span>
                    </h2>

                    <div class="space-y-6">
                        <!-- Address -->
                        <div class="flex items-start gap-5 p-6 architect-card">
                            <div class="w-12 h-12 bg-primary/10 flex items-center justify-center shrink-0 border border-primary/10" style="border-radius: 4%;">
                                <svg class="text-primary w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-[10px] uppercase tracking-widest text-foreground mb-2">Address</h3>
                                <p class="text-slate-500 text-[10px] font-medium uppercase tracking-[0.1em] leading-relaxed">
                                    123 Architect Street, Perundurai Road,<br />
                                    Erode - 638011, Tamil Nadu, India
                                </p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex items-start gap-5 p-6 architect-card">
                            <div class="w-12 h-12 bg-primary/10 flex items-center justify-center shrink-0 border border-primary/10" style="border-radius: 4%;">
                                <svg class="text-primary w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-[10px] uppercase tracking-widest text-foreground mb-2">Phone</h3>
                                <p class="text-slate-500 text-[10px] font-medium uppercase tracking-[0.1em] leading-relaxed">
                                    +91 424 212 3456<br />
                                    +91 424 212 7890
                                </p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start gap-5 p-6 architect-card">
                            <div class="w-12 h-12 bg-primary/10 flex items-center justify-center shrink-0 border border-primary/10" style="border-radius: 4%;">
                                <svg class="text-primary w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-[10px] uppercase tracking-widest text-foreground mb-2">Email</h3>
                                <p class="text-slate-500 text-[10px] font-medium uppercase tracking-[0.1em] leading-relaxed">
                                    info@erodearchitects.org<br />
                                    membership@erodearchitects.org
                                </p>
                            </div>
                        </div>

                        <!-- Hours -->
                        <div class="flex items-start gap-5 p-6 architect-card">
                            <div class="w-12 h-12 bg-primary/10 flex items-center justify-center shrink-0 border border-primary/10" style="border-radius: 4%;">
                                <svg class="text-primary w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-[10px] uppercase tracking-widest text-foreground mb-2">Office Hours</h3>
                                <p class="text-slate-500 text-[10px] font-medium uppercase tracking-[0.1em] leading-relaxed">
                                    Monday - Friday: 10:00 AM - 6:00 PM<br />
                                    Saturday: 10:00 AM - 2:00 PM
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CONTACT FORM -->
                <div class="reveal" style="transition-delay: 200ms;">
                    <div class="p-8 lg:p-12 architect-card border-primary/10 bg-card shadow-2xl shadow-primary/5">
                        <h2 class="font-druk text-2xl mb-8 text-foreground uppercase tracking-tight">
                            Send us a <span class="text-primary">Message</span>
                        </h2>

                        <form action="api/contact_process.php" method="POST" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[8px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Full Name</label>
                                    <input
                                        type="text"
                                        name="name"
                                        required
                                        class="w-full px-5 py-4 bg-background border border-border text-[10px] font-bold uppercase tracking-widest focus:border-primary focus:outline-none transition-colors"
                                        placeholder="Ar. Name"
                                    />
                                </div>
                                <div>
                                    <label class="block text-[8px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Email Address</label>
                                    <input
                                        type="email"
                                        name="email"
                                        required
                                        class="w-full px-5 py-4 bg-background border border-border text-[10px] font-bold uppercase tracking-widest focus:border-primary focus:outline-none transition-colors"
                                        placeholder="EMAIL@DOMAIN.COM"
                                    />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[8px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Phone</label>
                                    <input
                                        type="tel"
                                        name="phone"
                                        class="w-full px-5 py-4 bg-background border border-border text-[10px] font-bold uppercase tracking-widest focus:border-primary focus:outline-none transition-colors"
                                        placeholder="+91"
                                    />
                                </div>
                                <div>
                                    <label class="block text-[8px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Subject</label>
                                    <input
                                        type="text"
                                        name="subject"
                                        required
                                        class="w-full px-5 py-4 bg-background border border-border text-[10px] font-bold uppercase tracking-widest focus:border-primary focus:outline-none transition-colors"
                                        placeholder="Inquiry Topic"
                                    />
                                </div>
                            </div>

                            <div>
                                <label class="block text-[8px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Architectural Brief / Message</label>
                                <textarea
                                    name="message"
                                    required
                                    rows="5"
                                    class="w-full px-5 py-4 bg-background border border-border text-[10px] font-bold uppercase tracking-widest focus:border-primary focus:outline-none transition-colors resize-none"
                                    placeholder="Write your query here..."
                                ></textarea>
                            </div>

                            <button type="submit" class="w-full bg-gradient-gold text-white font-black text-[10px] uppercase tracking-[0.2em] px-8 py-5 rounded-lg flex items-center justify-center gap-3 shadow-xl shadow-yellow-600/10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                Dispatch Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-charcoal text-white pt-16 pb-8 text-left border-t border-white/5">
        <div class="container mx-auto px-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <div class="relative w-8 h-8 border-2 border-primary rotate-45"></div>
                    <div><span class="font-bold text-base uppercase leading-none block">Erode</span><span class="text-[6px] font-black uppercase tracking-widest text-white/30 uppercase">Architect Association</span></div>
                </div>
                <p class="text-[8px] font-medium text-white/30 uppercase tracking-widest leading-loose">Advancing architectural excellence since 1985.</p>
            </div>
            <div>
                <h4 class="font-bold text-[9px] tracking-[0.2em] mb-6 uppercase text-primary">Explore</h4>
                <ul class="space-y-2.5 text-[9px] font-bold uppercase tracking-widest text-white/40">
                    <li><a href="index.php" class="hover:text-white transition-colors">Home</a></li>
                    <li><a href="about.php" class="hover:text-white transition-colors">About</a></li>
                    <li><a href="calendar.php" class="hover:text-white transition-colors">Events</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-[9px] tracking-[0.2em] mb-6 uppercase text-primary">Regional Info</h4>
                <p class="text-[8px] font-bold uppercase tracking-widest text-white/30 leading-relaxed">
                    Erode District Headquarters,<br />
                    Tamil Nadu, India.
                </p>
            </div>
            <div class="bg-white/5 p-6 rounded-2xl border border-white/5">
                <h4 class="text-[8px] font-black uppercase tracking-widest text-white/60 mb-3 uppercase">Newsletter</h4>
                <div class="flex p-1 bg-charcoal rounded-lg border border-white/10">
                    <input type="text" placeholder="EMAIL" class="bg-transparent text-[8px] p-2 flex-1 outline-none text-white">
                    <button class="bg-primary px-3 rounded-md text-[8px] font-black uppercase tracking-widest">OK</button>
                </div>
            </div>
        </div>
        <div class="border-t border-white/5 pt-8 text-center text-[8px] font-bold uppercase tracking-widest text-white/10">© 2026 Erode Architect Association. Professionals Collective.</div>
    </footer>

    <script>
        function toggleTheme() {
            const html = document.documentElement; html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        }
        if (localStorage.getItem('theme') === 'dark') document.documentElement.classList.add('dark');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('active'); });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>
</body>
</html>