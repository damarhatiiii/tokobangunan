<?php
$current = uri_string();
$role    = (string) (session()->get('user_role') ?? '');
$canOps  = in_array($role, ['admin', 'petugas'], true);
$isAdmin = $role === 'admin';

$masterActive = $current === 'categories' || str_starts_with($current, 'categories/')
    || $current === 'satuan' || str_starts_with($current, 'satuan/');

$navActive = static fn (string $path, ?bool $forceActive = null): string => ($forceActive ?? ($current === $path || str_starts_with($current, $path . '/')))
    ? 'bg-stone-200/90 text-ink dark:bg-stone-700 dark:text-stone-100'
    : 'text-stone-600 hover:bg-stone-200/70 dark:text-stone-400 dark:hover:bg-stone-800';
?>
<header class="sticky top-0 z-20 border-b border-stone-300/80 bg-surface/95 backdrop-blur dark:border-stone-800 dark:bg-stone-900/95">
    <div class="flex items-center justify-between gap-3 px-4 py-3 sm:px-6">
        <div class="flex items-center gap-3">
            <button type="button" class="inline-flex rounded-md border border-stone-300 bg-white p-2 text-stone-700 lg:hidden dark:border-stone-600 dark:bg-stone-800 dark:text-stone-200" onclick="document.getElementById('mobile-nav').classList.toggle('hidden')" aria-label="Menu">☰</button>
            <div>
                <p class="text-xs text-stone-500 dark:text-stone-500"><?= esc(session()->get('user_name') ?? '') ?></p>
                <p class="font-display text-base font-semibold text-ink dark:text-stone-100"><?= esc($title ?? 'Panel') ?></p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?= base_url('profile') ?>" class="hidden rounded-md border border-stone-300 bg-white px-3 py-2 text-xs font-medium text-stone-700 hover:bg-stone-100 sm:inline dark:border-stone-600 dark:bg-stone-800 dark:text-stone-300 dark:hover:bg-stone-700">Profil</a>
            <button type="button" onclick="toggleDarkMode()" class="rounded-md border border-stone-300 bg-white px-3 py-2 text-xs font-medium text-stone-600 dark:border-stone-600 dark:bg-stone-800 dark:text-stone-300" title="Tema gelap/terang">Mode</button>
            <a href="<?= base_url('logout') ?>" class="rounded-md bg-accent px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-accent-hover">Keluar</a>
        </div>
    </div>
    <div id="mobile-nav" class="hidden border-t border-stone-200 px-3 py-2 lg:hidden dark:border-stone-800">
        <div class="grid grid-cols-2 gap-1">
            <a class="rounded-md px-2 py-2 text-sm <?= $navActive('dashboard') ?>" href="<?= base_url('dashboard') ?>">Dashboard</a>
            <a class="rounded-md px-2 py-2 text-sm <?= $navActive('analysis') ?>" href="<?= base_url('analysis') ?>">Analisis</a>
            <a class="rounded-md px-2 py-2 text-sm <?= $navActive('reports') ?>" href="<?= base_url('reports') ?>">Laporan</a>
            <?php if ($canOps): ?>
                <a class="rounded-md px-2 py-2 text-sm <?= $navActive('categories', $masterActive) ?>" href="<?= base_url('categories') ?>">Kategori &amp; Satuan</a>
                <a class="rounded-md px-2 py-2 text-sm <?= $navActive('suppliers') ?>" href="<?= base_url('suppliers') ?>">Supplier</a>
                <a class="rounded-md px-2 py-2 text-sm <?= $navActive('products') ?>" href="<?= base_url('products') ?>">Barang</a>
                <a class="rounded-md px-2 py-2 text-sm <?= $navActive('barang-masuk') ?>" href="<?= base_url('barang-masuk') ?>">Masuk</a>
                <a class="rounded-md px-2 py-2 text-sm <?= $navActive('sales') ?>" href="<?= base_url('sales') ?>">Keluar</a>
            <?php else: ?>
                <a class="rounded-md px-2 py-2 text-sm <?= $navActive('products') ?>" href="<?= base_url('products') ?>">Barang</a>
                <a class="rounded-md px-2 py-2 text-sm <?= $navActive('sales') ?>" href="<?= base_url('sales') ?>">Keluar</a>
            <?php endif; ?>
            <?php if ($isAdmin): ?>
                <a class="rounded-md px-2 py-2 text-sm <?= $navActive('users') ?>" href="<?= base_url('users') ?>">User</a>
            <?php endif; ?>
            <a class="rounded-md px-2 py-2 text-sm <?= $navActive('profile') ?>" href="<?= base_url('profile') ?>">Profil</a>
        </div>
    </div>
</header>
