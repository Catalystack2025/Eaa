<?php
/* =========================================================
   vendor/dashboard.php — VENDOR MANAGEMENT HUB
   ========================================================= */

require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../config/db.php';

start_session();

if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'vendor') {
    redirect('login.php');
}

$pageTitle = 'Vendor Dashboard | EAA';
require_once __DIR__ . '/../partials/header.php';

$flashSuccess = flash_get('success');
$flashError = flash_get('error');

$vendorProfile = null;
$stmt = db()->prepare('SELECT * FROM vendor_profile WHERE user_id = :user_id');
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$vendorProfile = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $vendorProfile) {
    $action = $_POST['action'] ?? '';
    $pdo = db();

    if ($action === 'product_create' || $action === 'product_update') {
        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $unit = trim($_POST['unit'] ?? 'SQFT');
        $location = trim($_POST['location'] ?? '');
        $imageUrl = trim($_POST['image_url'] ?? '');
        $status = ($_POST['status'] ?? 'active') === 'inactive' ? 'inactive' : 'active';
        $price = filter_var($_POST['price'] ?? '', FILTER_VALIDATE_FLOAT);

        if ($name === '' || $category === '' || $price === false) {
            flash_set('error', 'Please provide a product name, category, and valid price.');
            redirect('vendor/dashboard.php');
        }

        if ($action === 'product_create') {
            $stmt = $pdo->prepare(
                'INSERT INTO vendor_products (vendor_id, name, category, price, unit, location, image_url, status)
                 VALUES (:vendor_id, :name, :category, :price, :unit, :location, :image_url, :status)'
            );
            $stmt->execute([
                'vendor_id' => $vendorProfile['id'],
                'name' => $name,
                'category' => $category,
                'price' => $price,
                'unit' => $unit,
                'location' => $location !== '' ? $location : null,
                'image_url' => $imageUrl !== '' ? $imageUrl : null,
                'status' => $status,
            ]);
            flash_set('success', 'Product added to your catalog.');
        } else {
            $productId = (int) ($_POST['product_id'] ?? 0);
            $stmt = $pdo->prepare(
                'UPDATE vendor_products
                 SET name = :name,
                     category = :category,
                     price = :price,
                     unit = :unit,
                     location = :location,
                     image_url = :image_url,
                     status = :status
                 WHERE id = :id AND vendor_id = :vendor_id'
            );
            $stmt->execute([
                'name' => $name,
                'category' => $category,
                'price' => $price,
                'unit' => $unit,
                'location' => $location !== '' ? $location : null,
                'image_url' => $imageUrl !== '' ? $imageUrl : null,
                'status' => $status,
                'id' => $productId,
                'vendor_id' => $vendorProfile['id'],
            ]);
            flash_set('success', 'Product updated.');
        }

        redirect('vendor/dashboard.php');
    }

    if ($action === 'product_delete') {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $stmt = $pdo->prepare('DELETE FROM vendor_products WHERE id = :id AND vendor_id = :vendor_id');
        $stmt->execute([
            'id' => $productId,
            'vendor_id' => $vendorProfile['id'],
        ]);
        flash_set('success', 'Product removed.');
        redirect('vendor/dashboard.php');
    }

    if ($action === 'sponsor_submit') {
        $companyName = trim($_POST['company_name'] ?? '');
        $logoPath = trim($_POST['logo_path'] ?? '');
        $shortDesc = trim($_POST['short_desc'] ?? '');
        $website = trim($_POST['website'] ?? '');

        if ($companyName === '' || $logoPath === '') {
            flash_set('error', 'Please provide a company name and logo URL/path.');
            redirect('vendor/dashboard.php');
        }

        $stmt = $pdo->prepare(
            'INSERT INTO sponsors (vendor_id, company_name, logo_path, short_desc, website, status)
             VALUES (:vendor_id, :company_name, :logo_path, :short_desc, :website, :status)'
        );
        $stmt->execute([
            'vendor_id' => $vendorProfile['id'],
            'company_name' => $companyName,
            'logo_path' => $logoPath,
            'short_desc' => $shortDesc !== '' ? $shortDesc : null,
            'website' => $website !== '' ? $website : null,
            'status' => 'pending',
        ]);
        flash_set('success', 'Sponsorship request submitted for admin review.');
        redirect('vendor/dashboard.php');
    }

    if ($action === 'sponsor_delete') {
        $sponsorId = (int) ($_POST['sponsor_id'] ?? 0);
        $stmt = $pdo->prepare(
            "DELETE FROM sponsors WHERE id = :id AND vendor_id = :vendor_id AND status = 'pending'"
        );
        $stmt->execute([
            'id' => $sponsorId,
            'vendor_id' => $vendorProfile['id'],
        ]);
        flash_set('success', 'Pending sponsorship request removed.');
        redirect('vendor/dashboard.php');
    }
}

$products = [];
$sponsors = [];

if ($vendorProfile) {
    $stmt = db()->prepare('SELECT * FROM vendor_products WHERE vendor_id = :vendor_id ORDER BY created_at DESC');
    $stmt->execute(['vendor_id' => $vendorProfile['id']]);
    $products = $stmt->fetchAll();

$stmt = db()->prepare('SELECT * FROM sponsors WHERE vendor_id = :vendor_id ORDER BY created_at DESC');
$stmt->execute(['vendor_id' => $vendorProfile['id']]);
$sponsors = $stmt->fetchAll();
}
?>

<style>
    :root { --eaa-radius: 5px; }
    .eaa-radius { border-radius: var(--eaa-radius) !important; }
    .tech-label { font-size: 8px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.25em; color: #94a3b8; display: block; margin-bottom: 6px; }
</style>

<section class="py-16">
    <div class="container mx-auto px-6 space-y-10">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
            <div>
                <span class="tech-label">Vendor Console</span>
                <h1 class="font-druk text-4xl text-slate-900"><?= e($vendorProfile['company_name'] ?? 'Vendor Dashboard') ?></h1>
                <p class="text-xs uppercase tracking-widest text-slate-500 mt-3">Manage products and sponsorship submissions.</p>
            </div>
            <div class="text-xs font-bold uppercase tracking-widest text-slate-400">
                Status: <?= e($vendorProfile ? 'Active' : 'Profile Required') ?>
            </div>
        </div>

        <?php if ($flashSuccess): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 text-xs font-bold uppercase tracking-widest px-6 py-4 eaa-radius">
                <?= e($flashSuccess) ?>
            </div>
        <?php endif; ?>

        <?php if ($flashError): ?>
            <div class="bg-red-50 border border-red-200 text-red-600 text-xs font-bold uppercase tracking-widest px-6 py-4 eaa-radius">
                <?= e($flashError) ?>
            </div>
        <?php endif; ?>

        <?php if (!$vendorProfile): ?>
            <div class="bg-white border border-slate-200 eaa-radius p-8 text-sm text-slate-500">
                Your vendor profile is not available yet. Please contact the EAA team for assistance.
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white border border-slate-200 eaa-radius p-8">
                    <h2 class="font-druk text-2xl mb-6">Add Product</h2>
                    <form method="post" class="grid grid-cols-1 gap-4">
                        <input type="hidden" name="action" value="product_create">
                        <div>
                            <label class="tech-label">Product Name</label>
                            <input name="name" required class="w-full bg-slate-50 border border-slate-200 eaa-radius px-4 py-3 text-xs font-bold uppercase tracking-widest" />
                        </div>
                        <div>
                            <label class="tech-label">Category</label>
                            <input name="category" required class="w-full bg-slate-50 border border-slate-200 eaa-radius px-4 py-3 text-xs font-bold uppercase tracking-widest" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="tech-label">Price</label>
                                <input name="price" type="number" step="0.01" required class="w-full bg-slate-50 border border-slate-200 eaa-radius px-4 py-3 text-xs font-bold uppercase tracking-widest" />
                            </div>
                            <div>
                                <label class="tech-label">Unit</label>
                                <input name="unit" value="SQFT" class="w-full bg-slate-50 border border-slate-200 eaa-radius px-4 py-3 text-xs font-bold uppercase tracking-widest" />
                            </div>
                        </div>
                        <div>
                            <label class="tech-label">Location</label>
                            <input name="location" class="w-full bg-slate-50 border border-slate-200 eaa-radius px-4 py-3 text-xs font-bold uppercase tracking-widest" />
                        </div>
                        <div>
                            <label class="tech-label">Image URL</label>
                            <input name="image_url" class="w-full bg-slate-50 border border-slate-200 eaa-radius px-4 py-3 text-xs font-bold uppercase tracking-widest" />
                        </div>
                        <div>
                            <label class="tech-label">Status</label>
                            <select name="status" class="w-full bg-slate-50 border border-slate-200 eaa-radius px-4 py-3 text-xs font-bold uppercase tracking-widest">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <button class="px-6 py-3 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest eaa-radius">Save Product</button>
                    </form>
                </div>

                <div class="bg-white border border-slate-200 eaa-radius p-8">
                    <h2 class="font-druk text-2xl mb-6">Submit Sponsorship</h2>
                    <form method="post" class="grid grid-cols-1 gap-4">
                        <input type="hidden" name="action" value="sponsor_submit">
                        <div>
                            <label class="tech-label">Company Name</label>
                            <input name="company_name" required class="w-full bg-slate-50 border border-slate-200 eaa-radius px-4 py-3 text-xs font-bold uppercase tracking-widest" />
                        </div>
                        <div>
                            <label class="tech-label">Logo URL/Path</label>
                            <input name="logo_path" required class="w-full bg-slate-50 border border-slate-200 eaa-radius px-4 py-3 text-xs font-bold uppercase tracking-widest" />
                        </div>
                        <div>
                            <label class="tech-label">Short Description</label>
                            <input name="short_desc" class="w-full bg-slate-50 border border-slate-200 eaa-radius px-4 py-3 text-xs font-bold uppercase tracking-widest" />
                        </div>
                        <div>
                            <label class="tech-label">Website</label>
                            <input name="website" class="w-full bg-slate-50 border border-slate-200 eaa-radius px-4 py-3 text-xs font-bold uppercase tracking-widest" />
                        </div>
                        <button class="px-6 py-3 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest eaa-radius">Submit For Approval</button>
                    </form>
                </div>
            </div>

            <div class="bg-white border border-slate-200 eaa-radius p-8">
                <h2 class="font-druk text-2xl mb-6">Catalog Products</h2>
                <?php if (empty($products)): ?>
                    <p class="text-xs uppercase tracking-widest text-slate-400">No products yet.</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($products as $product): ?>
                            <div class="border border-slate-100 eaa-radius p-6">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <span class="tech-label"><?= e($product['category']) ?></span>
                                        <h3 class="text-sm font-black uppercase text-slate-900"><?= e($product['name']) ?></h3>
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">₹<?= e(number_format((float) $product['price'], 2)) ?> / <?= e($product['unit']) ?> · <?= e($product['location'] ?? 'Location on request') ?></p>
                                    </div>
                                    <div class="text-[9px] font-black uppercase tracking-widest text-slate-500">Status: <?= e($product['status']) ?></div>
                                </div>

                                <form method="post" class="grid grid-cols-1 lg:grid-cols-7 gap-3 mt-6">
                                    <input type="hidden" name="action" value="product_update">
                                    <input type="hidden" name="product_id" value="<?= e((string) $product['id']) ?>">
                                    <input name="name" value="<?= e($product['name']) ?>" class="lg:col-span-2 bg-slate-50 border border-slate-200 eaa-radius px-3 py-2 text-[10px] font-bold uppercase tracking-widest" />
                                    <input name="category" value="<?= e($product['category']) ?>" class="bg-slate-50 border border-slate-200 eaa-radius px-3 py-2 text-[10px] font-bold uppercase tracking-widest" />
                                    <input name="price" type="number" step="0.01" value="<?= e((string) $product['price']) ?>" class="bg-slate-50 border border-slate-200 eaa-radius px-3 py-2 text-[10px] font-bold uppercase tracking-widest" />
                                    <input name="unit" value="<?= e($product['unit']) ?>" class="bg-slate-50 border border-slate-200 eaa-radius px-3 py-2 text-[10px] font-bold uppercase tracking-widest" />
                                    <input name="location" value="<?= e($product['location'] ?? '') ?>" class="bg-slate-50 border border-slate-200 eaa-radius px-3 py-2 text-[10px] font-bold uppercase tracking-widest" />
                                    <select name="status" class="bg-slate-50 border border-slate-200 eaa-radius px-3 py-2 text-[10px] font-bold uppercase tracking-widest">
                                        <option value="active" <?= $product['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= $product['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    </select>
                                    <div class="lg:col-span-7 flex flex-wrap gap-3">
                                        <input name="image_url" value="<?= e($product['image_url'] ?? '') ?>" placeholder="Image URL" class="flex-1 bg-slate-50 border border-slate-200 eaa-radius px-3 py-2 text-[10px] font-bold uppercase tracking-widest" />
                                        <button class="px-5 py-2 bg-slate-900 text-white text-[8px] font-black uppercase tracking-widest eaa-radius">Update</button>
                                    </div>
                                </form>

                                <form method="post" class="mt-3">
                                    <input type="hidden" name="action" value="product_delete">
                                    <input type="hidden" name="product_id" value="<?= e((string) $product['id']) ?>">
                                    <button class="text-[8px] font-black uppercase tracking-widest text-red-500">Remove Product</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="bg-white border border-slate-200 eaa-radius p-8">
                <h2 class="font-druk text-2xl mb-6">Sponsorship Requests</h2>
                <?php if (empty($sponsors)): ?>
                    <p class="text-xs uppercase tracking-widest text-slate-400">No sponsorship submissions yet.</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($sponsors as $sponsor): ?>
                            <div class="border border-slate-100 eaa-radius p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div>
                                    <span class="tech-label"><?= e($sponsor['status']) ?></span>
                                    <h3 class="text-sm font-black uppercase text-slate-900"><?= e($sponsor['company_name']) ?></h3>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400"><?= e($sponsor['short_desc'] ?? 'No description provided') ?></p>
                                    <?php if (!empty($sponsor['website'])): ?>
                                        <a href="<?= e($sponsor['website']) ?>" class="text-[10px] font-black uppercase tracking-widest text-slate-600" target="_blank" rel="noreferrer">Visit Website</a>
                                    <?php endif; ?>
                                </div>
                                <?php if ($sponsor['status'] === 'pending'): ?>
                                    <form method="post">
                                        <input type="hidden" name="action" value="sponsor_delete">
                                        <input type="hidden" name="sponsor_id" value="<?= e((string) $sponsor['id']) ?>">
                                        <button class="text-[8px] font-black uppercase tracking-widest text-red-500">Withdraw</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
