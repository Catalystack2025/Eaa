<?php

declare(strict_types=1);

$pageTitle = 'Admin Settings';
ob_start();
?>
<div class="bg-card border border-border p-8 rounded-[2.5rem]">
  <h1 class="font-druk text-3xl text-foreground mb-4">Settings</h1>
  <p class="text-[11px] font-semibold text-slate-500">Optional site configuration panel.</p>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/_layout.php';
?>
