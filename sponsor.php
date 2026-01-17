<?php

declare(strict_types=1);

require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/config/db.php';

start_session();

$sponsorId = (int) ($_GET['id'] ?? 0);
$sponsor = null;

if ($sponsorId > 0) {
    $stmt = db()->prepare(
        'SELECT sponsors.company_name, sponsors.logo_path, sponsors.short_desc, sponsors.website,
                vendor_profile.contact_name, vendor_profile.phone, users.email
         FROM sponsors
         JOIN vendor_profile ON sponsors.vendor_id = vendor_profile.id
         JOIN users ON vendor_profile.user_id = users.id
         WHERE sponsors.id = :id AND sponsors.status = :status
         LIMIT 1'
    );
    $stmt->execute([
        'id' => $sponsorId,
        'status' => 'approved',
    ]);
    $sponsor = $stmt->fetch();
}

$canViewContact = can_view_vendor_contact($_SESSION['role'] ?? null);
$pageTitle = 'Sponsor Details | EAA';
require_once __DIR__ . '/partials/header.php';
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

  .blueprint-grid {
    background-image: linear-gradient(rgba(71, 85, 105, 0.05) 1px, transparent 1px),
                      linear-gradient(90deg, rgba(71, 85, 105, 0.05) 1px, transparent 1px);
    background-size: 40px 40px;
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
</style>

<section class="pt-44 pb-12 bg-white relative overflow-hidden border-b border-slate-100">
  <div class="absolute inset-0 blueprint-grid opacity-20 pointer-events-none"></div>
  <div class="container mx-auto px-6 relative z-10">
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-10">
      <div class="reveal">
        <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-[8px] font-black uppercase tracking-[0.3em] text-slate-500 eaa-radius mb-6">
          Sponsor Profile
        </span>
        <h1 class="font-druk text-5xl md:text-7xl lg:text-8xl text-slate-900 leading-none">
          <?= $sponsor ? e($sponsor['company_name']) : 'Sponsor Not Found' ?>
        </h1>
      </div>
      <div class="text-left lg:text-right reveal" style="transition-delay: 100ms;">
        <span class="tech-label">Registry Status</span>
        <span class="font-druk text-2xl lg:text-3xl text-slate-400"><?= $sponsor ? 'Approved' : 'Unavailable' ?></span>
      </div>
    </div>
  </div>
</section>

<main class="py-16">
  <div class="container mx-auto px-6">
    <div class="bg-white border border-slate-200 p-10 eaa-radius shadow-xl shadow-slate-200/70">
      <?php if (!$sponsor): ?>
        <h2 class="font-druk text-3xl text-slate-900 mb-4">Sponsor not found</h2>
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Please check the link and try again.</p>
      <?php else: ?>
        <div class="flex flex-col md:flex-row md:items-center gap-6">
          <div class="w-20 h-20 border border-slate-200 rounded-2xl flex items-center justify-center bg-white">
            <img src="<?= e($sponsor['logo_path']) ?>" alt="<?= e($sponsor['company_name']) ?>" class="h-12 w-auto object-contain" onerror="this.style.display='none'">
          </div>
          <div>
            <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Approved Sponsor</p>
            <h2 class="font-druk text-3xl text-slate-900 mb-2"><?= e($sponsor['company_name']) ?></h2>
            <?php if (!empty($sponsor['short_desc'])): ?>
              <p class="text-sm text-slate-500 uppercase tracking-widest">
                <?= e($sponsor['short_desc']) ?>
              </p>
            <?php endif; ?>
          </div>
        </div>

        <?php if (!empty($sponsor['website'])): ?>
          <div class="mt-8">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Website</p>
            <a href="<?= e($sponsor['website']) ?>" target="_blank" rel="noopener noreferrer" class="text-xs font-bold uppercase tracking-widest text-slate-900 hover:text-slate-500">
              <?= e($sponsor['website']) ?>
            </a>
          </div>
        <?php endif; ?>

        <div class="mt-8">
          <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Contact</p>
          <?php if ($canViewContact): ?>
            <div class="space-y-2 text-xs font-bold uppercase tracking-widest text-slate-900">
              <?php if (!empty($sponsor['contact_name'])): ?>
                <p><?= e($sponsor['contact_name']) ?></p>
              <?php endif; ?>
              <?php if (!empty($sponsor['phone'])): ?>
                <p><?= e($sponsor['phone']) ?></p>
              <?php endif; ?>
              <?php if (!empty($sponsor['email'])): ?>
                <p><?= e($sponsor['email']) ?></p>
              <?php endif; ?>
            </div>
          <?php else: ?>
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Login as a member to view contact details.</p>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</main>
<?php
require_once __DIR__ . '/partials/footer.php';
?>
