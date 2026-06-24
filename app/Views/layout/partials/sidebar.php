<?php
$role    = (string) (session()->get('user_role') ?? '');
$canOps  = in_array($role, ['admin', 'petugas'], true);
$isAdmin = $role === 'admin';
$current = uri_string();

$masterActive = $current === 'categories' || str_starts_with($current, 'categories/')
    || $current === 'satuan' || str_starts_with($current, 'satuan/');

$link = static function (string $path, string $label, ?bool $forceActive = null) use ($current, $masterActive): string {
    $isActive = $forceActive ?? ($current === $path || str_starts_with($current, $path . '/'));
    $active   = $isActive
        ? 'border-l-[3px] border-amber-500 bg-white/5 pl-[13px] text-white'
        : 'border-l-[3px] border-transparent pl-[13px] text-stone-400 hover:bg-white/5 hover:text-stone-200';

    return '<a href="' . esc(base_url($path)) . '" class="flex items-center gap-2 rounded-r-md py-2.5 pr-3 text-sm font-medium transition ' . $active . '"><span>' . esc($label) . '</span></a>';
};
?>
<aside id="desktop-sidebar" class="hidden w-[220px] shrink-0 flex-col border-r border-stone-800 bg-stone-950 lg:flex">
    <div class="border-b border-stone-800 px-4 py-6">
        <p class="font-display text-lg font-semibold tracking-tight text-stone-100">Toko Bangunan</p>
        <p class="mt-0.5 text-[11px] font-medium uppercase tracking-widest text-stone-500">Inventori</p>
        <p class="mt-3 inline-block rounded border border-stone-700 bg-stone-900 px-2 py-0.5 font-mono text-[10px] uppercase tracking-wide text-amber-500/90"><?= esc($role ?: 'user') ?></p>
    </div>
    <nav class="flex flex-1 flex-col gap-0.5 overflow-y-auto px-2 py-3">
        <?= $link('dashboard', 'Dashboard') ?>
        <?= $link('analysis', 'Analisis') ?>
        <?= $link('reports', 'Laporan') ?>
        <?php if ($canOps): ?>
            <?= $link('categories', 'Kategori & Satuan', $masterActive) ?>
            <?= $link('suppliers', 'Supplier') ?>
            <?= $link('products', 'Barang') ?>
            <?= $link('barang-masuk', 'Barang masuk') ?>
            <?= $link('sales', 'Barang keluar') ?>
        <?php else: ?>
            <?= $link('products', 'Barang') ?>
            <?= $link('sales', 'Barang keluar') ?>
        <?php endif; ?>
        <?php if ($isAdmin): ?>
            <?= $link('users', 'Pengguna') ?>
        <?php endif; ?>
        <?= $link('profile', 'Profil') ?>
    </nav>
</aside>
