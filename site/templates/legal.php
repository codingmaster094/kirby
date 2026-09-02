<?php snippet('header') ?>

<main class="flex-1 bg-ink">
    <?php snippet('layout') ?>

    <section class="px-4 py-16 sm:px-6 lg:px-10 lg:py-24">
        <div class="reveal mx-auto max-w-3xl">
            <?php if ($page->eyebrow()->isNotEmpty()): ?>
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-soft">
                    <?= esc($page->eyebrow()->value()) ?>
                </p>
            <?php endif ?>

            <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-white sm:text-5xl">
                <?= esc($page->heading()->or($page->title())->value()) ?>
            </h1>

            <?php if ($page->text()->isNotEmpty()): ?>
                <div class="prose prose-invert mt-8 max-w-none text-base leading-7 text-soft prose-headings:font-extrabold prose-headings:tracking-tight prose-headings:text-white prose-a:text-lime prose-strong:text-white">
                    <?= $page->text()->kt() ?>
                </div>
            <?php endif ?>
        </div>
    </section>
</main>

<?php snippet('footer') ?>
