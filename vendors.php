<?php
/* =========================================================
   connect.php — ARCHITECTURAL MATERIAL MARKETPLACE
   ✅ Front-facing catalog for EAA members to find vendors
   ✅ Exclusive Montserrat Typography
   ✅ High-density Technical Grid for Materials
   ✅ Unit Pricing (SQFT) and Technical Indexing
   ✅ Standardized 5px Radius & Smoke Grey Palette
   ========================================================= */

require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/config/db.php';

start_session();

$pageTitle = 'Connect / Material Catalog | EAA';
require_once __DIR__ . "/partials/header.php";

// Mock Product Data (Derived from Vendor Dashboard logic)
$products = [
    [
        'id' => 'EAA-MAT-2026-001',
        'name' => '12mm Reflective Toughened Glass',
        'vendor' => 'Erode Glass Works Ltd',
        'category' => 'Glass & Glazing',
        'price' => '450',
        'phone' => '+91 99944 12345',
        'email' => 'sales@erodeglass.com',
        'image' => 'https://images.unsplash.com/photo-1518005020951-eccb494ad742?w=800&q=80'
    ],
    [
        'id' => 'EAA-MAT-2026-002',
        'name' => 'Imported Italian Marble Slabs',
        'vendor' => 'Premium Stones Erode',
        'category' => 'Flooring & Tiles',
        'price' => '1250',
        'phone' => '+91 98427 55667',
        'email' => 'info@premiumstones.com',
        'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80'
    ],
    [
        'id' => 'EAA-MAT-2026-003',
        'name' => 'Weatherproof ACP Cladding',
        'vendor' => 'Alu-Systems India',
        'category' => 'Cladding Systems',
        'price' => '280',
        'phone' => '+91 94433 88990',
        'email' => 'support@alusystems.in',
        'image' => 'https://images.unsplash.com/photo-1486718448742-163732cd1544?w=800&q=80'
    ],
    [
        'id' => 'EAA-MAT-2026-004',
        'name' => 'Structural H-Beams (Grade A)',
        'vendor' => 'Steel India Solutions',
        'category' => 'Structural Steel',
        'price' => '180',
        'phone' => '+91 90033 11223',
        'email' => 'order@steelindia.com',
        'image' => 'https://images.unsplash.com/photo-1513828583688-c52646db42da?w=800&q=80'
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

    /* Product Card Styling */
    .product-card {
        background: #ffffff;
        border: 1px solid var(--eaa-border);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        border-color: var(--eaa-smoke);
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(71, 85, 105, 0.1);
    }

    .product-image {
        aspect-ratio: 4/3;
        overflow: hidden;
        background: #f1f5f9;
        position: relative;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.05);
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

    /* Custom Inlay Labels */
    .inlay-label {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(255, 255, 255, 0.9);
        padding: 4px 10px;
        font-size: 7px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        border-radius: 2px;
        backdrop-filter: blur(4px);
        border: 1px solid var(--eaa-border);
    }

    /* Modal Overlay */
    #contactModal {
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
        max-width: 450px;
        padding: 40px;
        border-radius: var(--eaa-radius);
        position: relative;
    }

    /* Animation Reveal */
    .reveal { opacity: 0; transform: translateY(20px); transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1); }
    .reveal.active { opacity: 1; transform: translateY(0); }

</style>

<!-- CONNECT HEADER -->
<!-- <section class="pt-44 pb-16 relative overflow-hidden bg-white border-b border-slate-100">
    <div class="absolute inset-0 blueprint-grid opacity-20 pointer-events-none"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl">
            <span class="text-[8px] font-black uppercase tracking-[0.5em] text-slate-400 block border-l-2 border-slate-400 pl-4 mb-6">Marketplace / 2026</span>
            <h1 class="font-druk text-5xl md:text-7xl lg:text-8xl text-slate-900 leading-none mb-10">
                Material <br><span class="text-slate-400 italic">Connect</span>
            </h1>
            <p class="max-w-2xl text-slate-500 text-xs md:text-sm font-bold uppercase tracking-widest leading-loose text-justify">
                Direct access to high-performance materials and specialized vendors vetted by the Erode Architect Association. Navigate regional pricing, technical specs, and verified procurement channels.
            </p>
        </div>
    </div>
</section> -->

<!-- FILTERING & SEARCH -->
<section class="py-10 bg-slate-50 border-b border-slate-100 sticky top-[80px] z-40">
    <div class="container mx-auto px-6 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
            <button class="px-6 py-2 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest eaa-radius">All Materials</button>
            <button class="px-6 py-2 text-slate-400 hover:text-slate-900 text-[9px] font-black uppercase tracking-widest transition-all">Glass</button>
            <button class="px-6 py-2 text-slate-400 hover:text-slate-900 text-[9px] font-black uppercase tracking-widest transition-all">Flooring</button>
            <button class="px-6 py-2 text-slate-400 hover:text-slate-900 text-[9px] font-black uppercase tracking-widest transition-all">Structural</button>
        </div>
        
        <div class="relative w-full md:w-72">
            <input type="text" placeholder="Search Materials..." class="w-full bg-white border border-slate-200 eaa-radius px-5 py-3 text-[10px] font-bold uppercase tracking-widest outline-none focus:border-slate-900 transition-all">
            <i class="fa-solid fa-magnifying-glass absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 text-xs"></i>
        </div>
    </div>
</section>

<!-- PRODUCT CATALOG GRID -->
<main class="py-24 bg-white relative overflow-hidden">
    <div class="container mx-auto px-6 relative z-10">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php foreach($products as $index => $p): ?>
            <div class="product-card eaa-radius reveal" style="transition-delay: <?= ($index % 4) * 100 ?>ms;">
                <div class="product-image">
                    <img src="<?= $p['image'] ?>" alt="<?= $p['name'] ?>">
                    <div class="inlay-label"><?= $p['category'] ?></div>
                </div>
                
                <div class="p-8 flex flex-col flex-1">
                    <span class="tech-label">Ref: <?= $p['id'] ?></span>
                    <h3 class="font-bold text-sm text-slate-900 uppercase tracking-tight mb-2 flex-1"><?= $p['name'] ?></h3>
                    
                    <div class="mt-4 pt-4 border-t border-slate-50">
                        <span class="text-[7px] font-black text-slate-400 uppercase tracking-widest block mb-1">Supplied By</span>
                        <span class="text-[10px] font-black text-slate-900 uppercase italic"><?= $p['vendor'] ?></span>
                    </div>

                    <div class="mt-8 flex items-center justify-between">
                        <div>
                            <span class="text-[7px] font-black text-slate-400 uppercase tracking-widest block">Unit Price</span>
                            <span class="text-base font-black text-slate-900">₹<?= $p['price'] ?> <small class="text-[8px]">/ SQFT</small></span>
                        </div>
                        <button 
                            onclick="openContactModal('<?= addslashes($p['vendor']) ?>', '<?= $p['phone'] ?>', '<?= $p['email'] ?>')"
                            class="px-5 py-3 bg-slate-900 text-white text-[8px] font-black uppercase tracking-widest eaa-radius hover:bg-slate-700 transition-all shadow-lg shadow-slate-200">
                            Get Contact
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</main>

<!-- CONTACT MODAL -->
<div id="contactModal">
    <div class="modal-content shadow-2xl">
        <button onclick="closeContactModal()" class="absolute top-6 right-6 text-slate-300 hover:text-slate-900"><i class="fa-solid fa-xmark"></i></button>
        
        <span class="tech-label">Procurement details</span>
        <h2 id="modalVendorName" class="font-druk text-2xl text-slate-900 mt-4 mb-10">Vendor Name</h2>
        
        <div class="space-y-6">
            <div class="p-5 border border-slate-100 eaa-radius flex items-center gap-5 hover:border-slate-300 transition-all group">
                <div class="w-10 h-10 bg-slate-50 eaa-radius flex items-center justify-center text-slate-900 group-hover:bg-slate-900 group-hover:text-white transition-all"><i class="fa-solid fa-phone text-xs"></i></div>
                <div>
                    <span class="tech-label">Mobile Hotline</span>
                    <a id="modalPhone" href="#" class="text-xs font-black text-slate-900 tracking-widest">+91 00000 00000</a>
                </div>
            </div>

            <div class="p-5 border border-slate-100 eaa-radius flex items-center gap-5 hover:border-slate-300 transition-all group">
                <div class="w-10 h-10 bg-slate-50 eaa-radius flex items-center justify-center text-slate-900 group-hover:bg-slate-900 group-hover:text-white transition-all"><i class="fa-solid fa-envelope text-xs"></i></div>
                <div>
                    <span class="tech-label">Official Correspondence</span>
                    <a id="modalEmail" href="#" class="text-xs font-black text-slate-900 tracking-widest">EMAIL@VENDOR.COM</a>
                </div>
            </div>
        </div>

        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest text-center mt-10">Please mention EAA Membership ID during inquiry.</p>
    </div>
</div>

<?php require_once __DIR__ . "/partials/footer.php"; ?>

<script>
    function openContactModal(name, phone, email) {
        document.getElementById('modalVendorName').innerText = name;
        document.getElementById('modalPhone').innerText = phone;
        document.getElementById('modalPhone').href = 'tel:' + phone.replace(/\s+/g, '');
        document.getElementById('modalEmail').innerText = email;
        document.getElementById('modalEmail').href = 'mailto:' + email;
        
        document.getElementById('contactModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeContactModal() {
        document.getElementById('contactModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('DOMContentLoaded', () => {
        const revealElements = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('active'); });
        }, { threshold: 0.1 });
        revealElements.forEach(el => observer.observe(el));
    });

    // Close on outside click
    window.onclick = function(event) {
        if (event.target == document.getElementById('contactModal')) {
            closeContactModal();
        }
    }
</script>