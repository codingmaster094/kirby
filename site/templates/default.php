<?php snippet('header') ?>

<main class="flex-1">
    <section class="relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top,_rgba(37,99,235,0.12),_transparent_50%)]"></div>
        <div class="relative mx-auto max-w-3xl px-5 py-20">
            <p class="text-sm font-semibold tracking-[0.16em] text-blue-600">
                <?= esc($page->title()->value()) ?>
            </p>
            <h1 class="mt-3 text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl">
                <?= esc($page->title()->value()) ?>
            </h1>
            <div class="prose mt-6 max-w-none text-lg leading-8 text-slate-600">
                <?= $page->text()->kt() ?>
            </div>
        </div>
    </section>
   
</main>

<?php snippet('footer') ?>
