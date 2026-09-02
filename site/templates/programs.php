<?php snippet('header') ?>

<main class="flex-1 bg-ink">
    <?php snippet('layout') ?>

    <section class="px-4 py-16 sm:px-6 lg:px-10 lg:py-24">
        <div class="reveal mx-auto max-w-3xl text-center">
            <?php if ($page->eyebrow()->isNotEmpty()): ?>
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-soft">
                    <?= esc($page->eyebrow()->value()) ?>
                </p>
            <?php endif ?>

            <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-white sm:text-5xl">
                <?= esc($page->heading()->or($page->title())->value()) ?>
            </h1>

            <?php if ($page->description()->isNotEmpty()): ?>
                <div class="mx-auto mt-4 max-w-2xl text-base leading-7 text-soft">
                    <?= $page->description()->kt() ?>
                </div>
            <?php endif ?>
        </div>

        <?php $programs = $page->programs()->toStructure(); ?>
        <?php if ($programs->isNotEmpty()): ?>
            <div class="mx-auto mt-14 grid max-w-7xl gap-6 lg:grid-cols-3">
                <?php foreach ($programs as $program): ?>
                    <?php
                    $title = (string) $program->title()->value();
                    $summary = (string) $program->summary()->kirbytext()->value();
                    $points = $program->points()->toStructure();
                    $ctaText = (string) $program->ctaText()->value();
                    $ctaUrl = (string) $program->ctaLink()->toUrl();
                    if ($ctaUrl === '' && $program->ctaLink()->isNotEmpty()) {
                        $ctaUrl = (string) $program->ctaLink()->value();
                    }
                    ?>
                    <article class="reveal flex h-full flex-col rounded-[1.75rem] border border-white/10 bg-panel p-7">
                        <h2 class="text-2xl font-extrabold tracking-tight text-white">
                            <?= esc($title) ?>
                        </h2>

                        <?php if ($summary !== ''): ?>
                            <div class="mt-3 text-sm leading-6 text-soft">
                                <?= $summary ?>
                            </div>
                        <?php endif ?>

                        <?php if ($points->isNotEmpty()): ?>
                            <ul class="mt-6 space-y-3">
                                <?php foreach ($points as $point): ?>
                                    <?php $text = (string) $point->text()->value(); ?>
                                    <?php if ($text !== ''): ?>
                                        <li class="flex gap-3 text-sm text-white/90">
                                            <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-lime"></span>
                                            <span><?= esc($text) ?></span>
                                        </li>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </ul>
                        <?php endif ?>

                        <?php if ($ctaText !== '' && $ctaUrl !== ''): ?>
                            <a href="<?= esc($ctaUrl) ?>" class="btn-lime mt-auto inline-flex w-fit rounded-full px-6 py-3 text-sm font-bold tracking-wide">
                                <?= esc($ctaText) ?>
                            </a>
                        <?php endif ?>
                    </article>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </section>
</main>

<?php snippet('footer') ?>
