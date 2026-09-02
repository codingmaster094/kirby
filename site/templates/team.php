<?php snippet('header') ?>

<main class="flex-1">
    <?php snippet('layout') ?>

    <section class="bg-ink px-4 py-16 sm:px-6 lg:px-10 lg:py-24">
        <div class="mx-auto max-w-7xl">
            <div class="reveal max-w-3xl">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-soft">Our team</p>
                <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-white sm:text-5xl">
                    <?= esc($page->heading()->or($page->title())->value()) ?>
                </h1>

                <?php if ($page->description()->isNotEmpty()): ?>
                    <div class="mt-4 text-base leading-7 text-soft">
                        <?= $page->description()->kt() ?>
                    </div>
                <?php endif ?>
            </div>

            <?php $blocks = $page->members()->toBlocks(); ?>
            <?php if ($blocks->isNotEmpty()): ?>
                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <?php foreach ($blocks as $block): ?>
                        <?php if ($block->isHidden()) {
                            continue;
                        } ?>
                        <div class="reveal">
                            <?php snippet('blocks/' . $block->type(), [
                                'block' => $block,
                                'page'  => $page,
                            ]) ?>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
        </div>
    </section>
</main>

<?php snippet('footer') ?>
