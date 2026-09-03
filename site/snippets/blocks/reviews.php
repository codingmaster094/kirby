<?php

$eyebrow = (string) $block->eyebrow()->value();
$heading = (string) $block->heading()->value();
$description = (string) $block->description()->kirbytext()->value();
$items = $block->items()->toStructure();

if ($heading === '' && $eyebrow === '' && $items->isEmpty()) {
    return;
}
?>

<section id="reviews" class="border-t border-white/10 bg-ink px-4 py-20 sm:px-6 lg:px-10 lg:py-28">
    <div class="reveal mx-auto max-w-7xl">
        <div class="mx-auto max-w-3xl text-center">
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
                <div class="mx-auto mt-4 max-w-2xl text-base leading-7 text-soft">
                    <?= $description ?>
                </div>
            <?php endif ?>
        </div>

        <?php if ($items->isNotEmpty()): ?>
            <div class="mt-12 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                <?php foreach ($items as $item): ?>
                    <?php
                    $quote = trim((string) $item->quote()->value());
                    $name = trim((string) $item->name()->value());
                    $role = trim((string) $item->role()->value());
                    $rating = (int) $item->rating()->or(5)->value();
                    $rating = max(1, min(5, $rating));

                    if ($quote === '' || $name === '') {
                        continue;
                    }
                    ?>
                    <article class="flex h-full flex-col rounded-[1.75rem] border border-white/10 bg-panel p-6 sm:p-7">
                        <div class="flex items-center gap-1 text-lime" aria-label="<?= esc($rating) ?> out of 5 stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <svg class="h-4 w-4 <?= $i <= $rating ? 'opacity-100' : 'opacity-25' ?>" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            <?php endfor ?>
                        </div>

                        <blockquote class="mt-5 flex-1 text-base leading-7 text-white/90">
                            “<?= esc($quote) ?>”
                        </blockquote>

                        <footer class="mt-6 border-t border-white/10 pt-5">
                            <p class="text-sm font-bold text-white"><?= esc($name) ?></p>
                            <?php if ($role !== ''): ?>
                                <p class="mt-1 text-xs font-semibold uppercase tracking-[0.16em] text-soft"><?= esc($role) ?></p>
                            <?php endif ?>
                        </footer>
                    </article>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>
</section>
