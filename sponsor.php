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
<section class="max-w-3xl mx-auto space-y-8">
  <div class="bg-card border border-border p-10 rounded-[2.5rem] shadow-2xl shadow-primary/5">
    <?php if (!$sponsor): ?>
      <h1 class="font-druk text-3xl text-foreground mb-4">Sponsor not found</h1>
      <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Please check the link and try again.</p>
    <?php else: ?>
      <div class="flex flex-col md:flex-row md:items-center gap-6">
        <div class="w-20 h-20 border border-border rounded-2xl flex items-center justify-center bg-white">
          <img src="<?= e($sponsor['logo_path']) ?>" alt="<?= e($sponsor['company_name']) ?>" class="h-12 w-auto object-contain" onerror="this.style.display='none'">
        </div>
        <div>
          <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Approved Sponsor</p>
          <h1 class="font-druk text-3xl text-foreground mb-2"><?= e($sponsor['company_name']) ?></h1>
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
          <a href="<?= e($sponsor['website']) ?>" target="_blank" rel="noopener noreferrer" class="text-xs font-bold uppercase tracking-widest text-primary hover:underline">
            <?= e($sponsor['website']) ?>
          </a>
        </div>
      <?php endif; ?>

      <div class="mt-8">
        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Contact</p>
        <?php if ($canViewContact): ?>
          <div class="space-y-2 text-xs font-bold uppercase tracking-widest text-foreground">
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
</section>
<?php
require_once __DIR__ . '/partials/footer.php';
?>
