<?php

$eyebrow = (string) $block->eyebrow()->value();
$heading = (string) $block->heading()->value();
$description = (string) $block->description()->kirbytext()->value();
$primaryText = (string) $block->primaryText()->value();
$primaryUrl = (string) $block->primaryLink()->toUrl();
$secondaryText = (string) $block->secondaryText()->value();
$secondaryUrl = (string) $block->secondaryLink()->toUrl();

if ($primaryUrl === '' && $block->primaryLink()->isNotEmpty()) {
    $primaryUrl = (string) $block->primaryLink()->value();
}

if ($secondaryUrl === '' && $block->secondaryLink()->isNotEmpty()) {
    $secondaryUrl = (string) $block->secondaryLink()->value();
}

if ($heading === '' && $eyebrow === '' && $description === '') {
    return;
}
?>

<section id="cta" class="bg-ink px-4 py-20 sm:px-6 lg:px-10">
    <div class="reveal mx-auto max-w-7xl overflow-hidden rounded-[2rem] bg-panel px-6 py-12 text-center sm:rounded-[2.5rem] sm:px-10 lg:py-16">
        <?php if ($eyebrow !== ''): ?>
            <p class="text-xs font-bold uppercase tracking-[0.28em] text-soft">
                <?= esc($eyebrow) ?>
            </p>
        <?php endif ?>

        <?php if ($heading !== ''): ?>
            <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-white sm:text-5xl">
                <?= esc($heading) ?>
            </h2>
        <?php endif ?>

        <?php if ($description !== ''): ?>
            <div class="mx-auto mt-4 max-w-2xl text-base leading-7 text-soft sm:text-lg">
                <?= $description ?>
            </div>
        <?php endif ?>

        <?php if (($primaryText !== '' && $primaryUrl !== '') || ($secondaryText !== '' && $secondaryUrl !== '')): ?>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <?php if ($primaryText !== '' && $primaryUrl !== ''): ?>
                    <a
                        href="<?= esc($primaryUrl) ?>"
                        class="btn-lime inline-flex rounded-full px-8 py-3.5 text-sm font-bold tracking-wide"
                    >
                        <?= esc($primaryText) ?>
                    </a>
                <?php endif ?>

                <?php if ($secondaryText !== '' && $secondaryUrl !== ''): ?>
                    <a
                        href="<?= esc($secondaryUrl) ?>"
                        class="inline-flex rounded-full border border-white/20 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-white/10"
                    >
                        <?= esc($secondaryText) ?>
                    </a>
                <?php endif ?>
            </div>
        <?php endif ?>
    </div>
</section>
