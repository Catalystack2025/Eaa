<?php

declare(strict_types=1);

require_once __DIR__ . '/lib/helpers.php';

start_session();

http_response_code(403);

$pageTitle = 'Access Denied | EAA';
require_once __DIR__ . '/partials/header.php';
?>
<section class="max-w-3xl mx-auto">
  <div class="bg-card border border-border p-10 rounded-[2.5rem] shadow-2xl shadow-primary/5 text-center">
    <h1 class="font-druk text-4xl text-foreground mb-4">403</h1>
    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-6">Access denied</p>
    <p class="text-sm text-slate-500 uppercase tracking-widest">You do not have permission to view this page.</p>
  </div>
</section>
<?php
require_once __DIR__ . '/partials/footer.php';
?>
