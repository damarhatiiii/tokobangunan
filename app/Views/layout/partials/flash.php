<?php if (session()->getFlashdata('message')): ?>
    <div class="mb-4 rounded-md border border-amber-800/20 bg-amber-50 px-4 py-3 text-sm text-amber-950 dark:border-amber-700/40 dark:bg-amber-950/40 dark:text-amber-100">
        <?= esc(session()->getFlashdata('message')) ?>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="mb-4 rounded-md border border-red-800/20 bg-red-50 px-4 py-3 text-sm text-red-950 dark:border-red-900 dark:bg-red-950/50 dark:text-red-100">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('errors')): ?>
    <div class="mb-4 rounded-md border border-red-800/20 bg-red-50 px-4 py-3 text-sm text-red-950 dark:border-red-900 dark:bg-red-950/50 dark:text-red-100">
        <ul class="list-inside list-disc space-y-1">
            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <li><?= esc(is_array($err) ? implode(', ', $err) : $err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
