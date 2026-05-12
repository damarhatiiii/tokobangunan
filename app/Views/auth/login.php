<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= csrf_meta() ?>
    <title>Masuk — Toko Bangunan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-full bg-stone-100 font-sans text-stone-900 antialiased">
<div class="flex min-h-full items-center justify-center px-4 py-12">
    <div class="w-full max-w-sm rounded-lg border border-stone-200 bg-white p-8 shadow-sm">
        <h1 class="text-xl font-semibold text-stone-900">Toko Bangunan</h1>
        <p class="mt-1 text-sm text-stone-600">Masuk dengan username dan kata sandi.</p>

        <?php if (session()->getFlashdata('error')): ?>
            <p class="mt-4 rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-900"><?= esc(session()->getFlashdata('error')) ?></p>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')): ?>
            <ul class="mt-4 list-inside list-disc text-sm text-red-700">
                <?php foreach (session()->getFlashdata('errors') as $e): ?>
                    <li><?= esc(is_array($e) ? implode(', ', $e) : $e) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="<?= site_url('login') ?>" method="post" class="mt-6 space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-sm font-medium text-stone-700" for="username">Username</label>
                <input class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-stone-900 shadow-sm focus:border-amber-700 focus:outline-none focus:ring-1 focus:ring-amber-700" type="text" name="username" id="username" value="<?= esc(old('username')) ?>" required autocomplete="username">
            </div>
            <div>
                <label class="block text-sm font-medium text-stone-700" for="password">Kata sandi</label>
                <input class="mt-1 w-full rounded-md border border-stone-300 px-3 py-2 text-stone-900 shadow-sm focus:border-amber-700 focus:outline-none focus:ring-1 focus:ring-amber-700" type="password" name="password" id="password" required autocomplete="current-password">
            </div>
            <button type="submit" class="w-full rounded-md bg-amber-800 py-2.5 text-sm font-semibold text-white hover:bg-amber-900">Masuk</button>
        </form>
        <div class="mt-6 border-t border-stone-200 pt-4 text-[11px] leading-snug text-stone-500">
            <p>Setelah migrasi/seeder demo: <span class="font-medium text-stone-700">admin</span> / admin123 · <span class="font-medium text-stone-700">petugas</span> / petugas123</p>
            <p class="mt-1">Gunakan kata sandi bcrypt; impor SQL lama bisa perlu reset password pengguna.</p>
        </div>
    </div>
</div>
</body>
</html>
