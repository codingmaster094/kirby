<?php
$primaryText = (string) $block->primaryText()->or('Back to Home')->value();
$primaryUrl = (string) ($block->primaryLink()->toUrl() ?? '');
if ($primaryUrl === '' && $block->primaryLink()->isNotEmpty()) {
    $primaryUrl = (string) $block->primaryLink()->value();
}
if ($primaryUrl === '') {
    $primaryUrl = (string) site()->url();
}

$secondaryText = (string) $block->secondaryText()->value();
$secondaryUrl = (string) ($block->secondaryLink()->toUrl() ?? '');
if ($secondaryUrl === '' && $block->secondaryLink()->isNotEmpty()) {
    $secondaryUrl = (string) $block->secondaryLink()->value();
}
?>
<section class="flex min-h-[70vh] items-center bg-ink px-4 py-20 sm:px-6 lg:px-10">
    <div class="reveal mx-auto max-w-2xl text-center">
        <?php if ($block->eyebrow()->isNotEmpty()): ?>
            <p class="text-xs font-bold uppercase tracking-[0.28em] text-lime"><?= esc($block->eyebrow()->value()) ?></p>
        <?php endif ?>
        <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-white sm:text-6xl">
            <?= esc($block->heading()->or($page->title())->value()) ?>
        </h1>
        <?php if ($block->text()->isNotEmpty()): ?>
            <div class="mx-auto mt-5 max-w-lg text-base leading-7 text-soft"><?= $block->text()->kt() ?></div>
        <?php endif ?>
        <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
            <a href="<?= esc($primaryUrl) ?>" class="btn-lime inline-flex rounded-full px-8 py-3.5 text-sm font-bold tracking-wide"><?= esc($primaryText) ?></a>
            <?php if ($secondaryText !== '' && $secondaryUrl !== ''): ?>
                <a href="<?= esc($secondaryUrl) ?>" class="inline-flex rounded-full border border-white/20 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-white/10"><?= esc($secondaryText) ?></a>
            <?php endif ?>
        </div>
    </div>
</section>
