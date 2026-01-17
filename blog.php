<?php
/* =========================================================
   blog.php — ARCHITECTURAL JOURNAL (PUBLISHED ONLY)
   ✅ Public list of published blogs
   ✅ Blueprint ledger styling
   ========================================================= */

require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/config/db.php';

start_session();

$stmt = db()->prepare(
    'SELECT b.id, b.title, b.body, b.created_at, u.full_name AS author_name
     FROM blogs b
     INNER JOIN users u ON b.author_id = u.id
     WHERE b.status = :status
     ORDER BY b.created_at DESC'
);
$stmt->execute(['status' => 'published']);
$blogs = $stmt->fetchAll();

$pageTitle = 'EAA Journal | Published Blogs';
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
        font-family: 'Montserrat', sans-serif;
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
</style>

<section class="pt-32 pb-12 bg-white relative overflow-hidden border-b border-slate-100">
    <div class="absolute inset-0 blueprint-grid opacity-20 pointer-events-none"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl">
            <span class="text-[8px] font-black uppercase tracking-[0.4em] text-slate-400 mb-3 block">Journal Archive</span>
            <h1 class="font-druk text-4xl md:text-6xl text-slate-900">Published <span class="text-slate-400 italic">Blogs</span></h1>
            <p class="mt-6 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">Only approved and published stories appear here.</p>
        </div>
    </div>
</section>

<section class="py-16 bg-slate-50">
    <div class="container mx-auto px-6">
        <?php if (!$blogs): ?>
            <div class="bg-white border border-slate-200 eaa-radius p-10 text-center text-[10px] font-black uppercase tracking-widest text-slate-400">
                No published blogs available yet.
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <?php foreach ($blogs as $blog): ?>
                    <?php
                    $excerpt = mb_strimwidth(strip_tags($blog['body']), 0, 220, '...');
                    ?>
                    <article class="bg-white border border-slate-200 eaa-radius shadow-sm p-8 flex flex-col gap-6">
                        <div class="flex items-center justify-between text-[8px] font-black uppercase tracking-widest text-slate-400">
                            <span><?= e(date('M d, Y', strtotime($blog['created_at']))) ?></span>
                            <span><?= e($blog['author_name']) ?></span>
                        </div>
                        <h2 class="font-druk text-2xl text-slate-900 uppercase leading-tight"><?= e($blog['title']) ?></h2>
                        <p class="text-[11px] font-medium text-slate-600 leading-relaxed"><?= e($excerpt) ?></p>
                        <div class="mt-auto">
                            <span class="inline-flex items-center px-3 py-2 border border-slate-200 text-[8px] font-black uppercase tracking-[0.3em] text-slate-500 eaa-radius">Status: Published</span>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
