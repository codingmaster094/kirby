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

        <?php $plans = $page->plans()->toStructure(); ?>
        <?php if ($plans->isNotEmpty()): ?>
            <div class="mx-auto mt-14 grid max-w-7xl gap-6 lg:grid-cols-3">
                <?php foreach ($plans as $plan): ?>
                    <?php
                    $name = (string) $plan->name()->value();
                    $price = (string) $plan->price()->value();
                    $blurb = (string) $plan->blurb()->value();
                    $featured = $plan->featured()->toBool();
                    $features = $plan->features()->toStructure();
                    $ctaText = (string) $plan->ctaText()->value();
                    $ctaUrl = (string) $plan->ctaLink()->toUrl();
                    if ($ctaUrl === '' && $plan->ctaLink()->isNotEmpty()) {
                        $ctaUrl = (string) $plan->ctaLink()->value();
                    }
                    ?>
                    <article class="reveal relative flex h-full flex-col rounded-[1.75rem] border p-7 <?= $featured ? 'border-lime bg-panel shadow-[0_0_0_1px_rgba(216,255,62,0.25)]' : 'border-white/10 bg-panel' ?>">
                        <?php if ($featured): ?>
                            <span class="absolute -top-3 left-7 rounded-full bg-lime px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-ink">
                                Most popular
                            </span>
                        <?php endif ?>

                        <h2 class="text-xl font-extrabold text-white"><?= esc($name) ?></h2>
                        <p class="mt-4 text-4xl font-extrabold tracking-tight text-lime"><?= esc($price) ?></p>

                        <?php if ($blurb !== ''): ?>
                            <p class="mt-3 text-sm text-soft"><?= esc($blurb) ?></p>
                        <?php endif ?>

                        <?php if ($features->isNotEmpty()): ?>
                            <ul class="mt-6 space-y-3">
                                <?php foreach ($features as $feature): ?>
                                    <?php $text = (string) $feature->text()->value(); ?>
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
                            <a
                                href="<?= esc($ctaUrl) ?>"
                                class="<?= $featured ? 'btn-lime' : 'border border-white/20 text-white hover:bg-white/10' ?> mt-auto inline-flex w-fit rounded-full px-6 py-3 text-sm font-bold tracking-wide transition"
                            >
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
