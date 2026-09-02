<?php snippet('header') ?>

<?php
$primaryText = (string) $page->primaryText()->or('Back to Home')->value();
$primaryUrl = (string) ($page->primaryLink()->toUrl() ?? '');
if ($primaryUrl === '' && $page->primaryLink()->isNotEmpty()) {
    $primaryUrl = (string) $page->primaryLink()->value();
}
if ($primaryUrl === '') {
    $primaryUrl = (string) site()->url();
}

$secondaryText = (string) $page->secondaryText()->value();
$secondaryUrl = (string) ($page->secondaryLink()->toUrl() ?? '');
if ($secondaryUrl === '' && $page->secondaryLink()->isNotEmpty()) {
    $secondaryUrl = (string) $page->secondaryLink()->value();
}
?>

<main class="flex-1 bg-ink">
    <section class="flex min-h-[70vh] items-center px-4 py-20 sm:px-6 lg:px-10">
        <div class="reveal mx-auto max-w-2xl text-center">
            <?php if ($page->eyebrow()->isNotEmpty()): ?>
                <p class="text-xs font-bold uppercase tracking-[0.28em] text-lime">
                    <?= esc($page->eyebrow()->value()) ?>
                </p>
            <?php endif ?>

            <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-white sm:text-6xl">
                <?= esc($page->heading()->or($page->title())->value()) ?>
            </h1>

            <?php if ($page->text()->isNotEmpty()): ?>
                <div class="mx-auto mt-5 max-w-lg text-base leading-7 text-soft">
                    <?= $page->text()->kt() ?>
                </div>
            <?php endif ?>

            <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
                <a href="<?= esc($primaryUrl) ?>" class="btn-lime inline-flex rounded-full px-8 py-3.5 text-sm font-bold tracking-wide">
                    <?= esc($primaryText) ?>
                </a>

                <?php if ($secondaryText !== '' && $secondaryUrl !== ''): ?>
                    <a
                        href="<?= esc($secondaryUrl) ?>"
                        class="inline-flex rounded-full border border-white/20 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-white/10"
                    >
                        <?= esc($secondaryText) ?>
                    </a>
                <?php endif ?>
            </div>
        </div>
    </section>
</main>

<?php snippet('footer') ?>
