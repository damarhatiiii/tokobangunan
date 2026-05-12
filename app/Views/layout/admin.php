<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= csrf_meta() ?>
    <title><?= esc($title ?? 'Admin') ?> — Toko Bangunan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"DM Sans"', 'system-ui', 'sans-serif'],
                        display: ['"Fraunces"', 'Georgia', 'serif'],
                    },
                    colors: {
                        canvas: '#e8e4dc',
                        surface: '#f7f5f0',
                        accent: '#b45309',
                        'accent-hover': '#92400e',
                        ink: '#292524',
                    },
                    boxShadow: {
                        lift: '0 1px 0 rgba(41, 37, 36, 0.06), 0 8px 24px rgba(41, 37, 36, 0.06)',
                    },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;1,9..144,600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        /* Tekstur kertas sangat halus — menghindari flat solid AI-slop */
        body.theme-app {
            background-color: #e8e4dc;
            background-image:
                url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
        }
        .dark body.theme-app,
        body.dark.theme-app {
            background-color: #0c0a09;
            background-image: none;
        }
    </style>
    <?= $this->renderSection('head_extra') ?>
</head>
<body class="theme-app min-h-full font-sans text-ink antialiased dark:bg-stone-950 dark:text-stone-200">
<div class="flex min-h-screen">
    <?= $this->include('layout/partials/sidebar') ?>
    <div class="flex min-w-0 flex-1 flex-col">
        <?= $this->include('layout/partials/topbar') ?>
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <?= $this->include('layout/partials/flash') ?>
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>
<script>
(function () {
    const html = document.documentElement;
    if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        html.classList.add('dark');
    } else {
        html.classList.remove('dark');
    }
    document.body.classList.toggle('dark', html.classList.contains('dark'));
    window.toggleDarkMode = function () {
        html.classList.toggle('dark');
        document.body.classList.toggle('dark', html.classList.contains('dark'));
        localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    };
})();
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
