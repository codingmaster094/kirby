<?php

$eyebrow = (string) $block->eyebrow()->value();
$heading = (string) $block->heading()->value();
$description = (string) $block->description()->kirbytext()->value();
$items = $block->items()->toStructure();

if ($heading === '' && $eyebrow === '' && $items->isEmpty()) {
    return;
}
?>

<section id="faq" class="bg-ink px-4 py-20 sm:px-6 lg:px-10">
    <div class="reveal mx-auto max-w-3xl">
        <?php if ($eyebrow !== ''): ?>
            <p class="text-center text-xs font-bold uppercase tracking-[0.28em] text-soft">
                <?= esc($eyebrow) ?>
            </p>
        <?php endif ?>

        <?php if ($heading !== ''): ?>
            <h2 class="mt-4 text-center text-3xl font-extrabold tracking-tight text-white sm:text-5xl">
                <?= esc($heading) ?>
            </h2>
        <?php endif ?>

        <?php if ($description !== ''): ?>
            <div class="mx-auto mt-4 max-w-2xl text-center text-base leading-7 text-soft">
                <?= $description ?>
            </div>
        <?php endif ?>

        <?php if ($items->isNotEmpty()): ?>
            <div class="mt-10 divide-y divide-white/10 overflow-hidden rounded-[1.75rem] border border-white/10 bg-panel">
                <?php foreach ($items as $item): ?>
                    <?php
                    $question = trim((string) $item->question()->value());
                    $answer = (string) $item->answer()->kirbytext()->value();
                    if ($question === '') {
                        continue;
                    }
                    ?>
                    <details class="group">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-6 py-5 text-left text-base font-bold text-white transition hover:bg-white/5 sm:px-7">
                            <span><?= esc($question) ?></span>
                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full border border-white/15 text-lime transition group-open:rotate-45">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M12 5v14M5 12h14" stroke-linecap="round"/>
                                </svg>
                            </span>
                        </summary>
                        <?php if ($answer !== ''): ?>
                            <div class="px-6 pb-6 text-sm leading-7 text-soft sm:px-7">
                                <?= $answer ?>
                            </div>
                        <?php endif ?>
                    </details>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>
</section>
