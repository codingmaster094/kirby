<?php
$eyebrow = (string) $block->eyebrow()->value();
$heading = (string) $block->heading()->value();
$text = (string) $block->text()->kirbytext()->value();
?>
<section class="bg-ink px-4 py-16 sm:px-6 lg:px-10 lg:py-24">
    <div class="reveal mx-auto max-w-3xl">
        <?php if ($eyebrow !== ''): ?>
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-soft"><?= esc($eyebrow) ?></p>
        <?php endif ?>
        <?php if ($heading !== ''): ?>
            <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-white sm:text-5xl"><?= esc($heading) ?></h1>
        <?php endif ?>
        <?php if ($text !== ''): ?>
            <div class="prose prose-invert mt-8 max-w-none text-base leading-7 text-soft prose-headings:font-extrabold prose-headings:tracking-tight prose-headings:text-white prose-a:text-lime prose-strong:text-white">
                <?= $text ?>
            </div>
        <?php endif ?>
    </div>
</section>
