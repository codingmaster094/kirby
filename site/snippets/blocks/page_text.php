<?php
$heading = (string) $block->heading()->or($page->title())->value();
$text = (string) $block->text()->kirbytext()->value();
?>
<section class="relative overflow-hidden">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top,_rgba(37,99,235,0.12),_transparent_50%)]"></div>
    <div class="relative mx-auto max-w-3xl px-5 py-20">
        <p class="text-sm font-semibold tracking-[0.16em] text-blue-600"><?= esc($page->title()->value()) ?></p>
        <?php if ($heading !== ''): ?>
            <h1 class="mt-3 text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl"><?= esc($heading) ?></h1>
        <?php endif ?>
        <?php if ($text !== ''): ?>
            <div class="prose mt-6 max-w-none text-lg leading-8 text-slate-600"><?= $text ?></div>
        <?php endif ?>
    </div>
</section>
