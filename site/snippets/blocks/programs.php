<?php
$eyebrow = (string) $block->eyebrow()->value();
$heading = (string) $block->heading()->value();
$description = (string) $block->description()->kirbytext()->value();
$items = $block->items()->toStructure();
?>
<section id="programs" class="bg-ink px-4 py-16 sm:px-6 lg:px-10 lg:py-24">
    <div class="reveal mx-auto max-w-3xl text-center">
        <?php if ($eyebrow !== ''): ?>
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-soft"><?= esc($eyebrow) ?></p>
        <?php endif ?>
        <?php if ($heading !== ''): ?>
            <h2 class="mt-3 text-4xl font-extrabold tracking-tight text-white sm:text-5xl"><?= esc($heading) ?></h2>
        <?php endif ?>
        <?php if ($description !== ''): ?>
            <div class="mx-auto mt-4 max-w-2xl text-base leading-7 text-soft"><?= $description ?></div>
        <?php endif ?>
    </div>

    <?php if ($items->isNotEmpty()): ?>
        <div class="mx-auto mt-14 grid max-w-7xl gap-6 lg:grid-cols-3">
            <?php foreach ($items as $program): ?>
                <?php
                $title = (string) $program->title()->value();
                $audience = trim((string) $program->audience()->value());
                $summary = (string) $program->summary()->kirbytext()->value();
                $points = $program->points()->toStructure();
                $ctaText = (string) $program->ctaText()->or('Book a Free Strategy Call')->value();
                $ctaUrl = (string) ($program->ctaLink()->toUrl() ?? '');
                if ($ctaUrl === '' && $program->ctaLink()->isNotEmpty()) {
                    $ctaUrl = (string) $program->ctaLink()->value();
                }
                ?>
                <article class="reveal flex h-full flex-col rounded-[1.75rem] border border-white/10 bg-panel p-7">
                    <?php if ($audience !== ''): ?>
                        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-lime"><?= esc($audience) ?></p>
                    <?php endif ?>
                    <h3 class="mt-2 text-2xl font-extrabold tracking-tight text-white"><?= esc($title) ?></h3>
                    <?php if ($summary !== ''): ?>
                        <div class="mt-3 text-sm leading-6 text-soft"><?= $summary ?></div>
                    <?php endif ?>
                    <?php if ($points->isNotEmpty()): ?>
                        <p class="mt-6 text-[11px] font-bold uppercase tracking-[0.18em] text-white/50">What's included</p>
                        <ul class="mt-3 space-y-3">
                            <?php foreach ($points as $point): ?>
                                <?php $text = (string) $point->text()->value(); ?>
                                <?php if ($text !== ''): ?>
                                    <li class="flex gap-3 text-sm text-white/90">
                                        <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-lime" aria-hidden="true"></span>
                                        <span><?= esc($text) ?></span>
                                    </li>
                                <?php endif ?>
                            <?php endforeach ?>
                        </ul>
                    <?php endif ?>
                    <?php if ($ctaText !== '' && $ctaUrl !== ''): ?>
                        <a href="<?= esc($ctaUrl) ?>" class="btn-lime mt-8 inline-flex w-fit rounded-full px-6 py-3 text-sm font-bold tracking-wide"><?= esc($ctaText) ?></a>
                    <?php endif ?>
                </article>
            <?php endforeach ?>
        </div>
    <?php endif ?>
</section>
