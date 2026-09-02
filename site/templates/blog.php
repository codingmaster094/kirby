<?php snippet('header') ?>

<main class="flex-1">
    <?php
    $rendered = false;

    foreach ($page->layout()->toLayouts() as $layout) {
        foreach ($layout->columns() as $column) {
            foreach ($column->blocks() as $block) {
                if ($block->isHidden()) {
                    continue;
                }

                snippet('blocks/' . $block->type(), [
                    'block' => $block,
                    'page'  => $page,
                ]);

                $rendered = true;
            }
        }
    }

    if ($rendered === false) {
        snippet('sections/hero');
    }
    ?>

    <section class="border-t border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-10">
            <div class="reveal max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-500">Journal</p>
                <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-ink sm:text-5xl">
                    <?= esc($page->title()->value()) ?>
                </h1>
                <?php if ($page->description()->isNotEmpty()): ?>
                    <p class="mt-4 text-base leading-7 text-slate-600">
                        <?= esc($page->description()->value()) ?>
                    </p>
                <?php endif ?>
            </div>

            <div class="mt-12 grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                <?php foreach ($page->children()->listed() as $post): ?>
                    <a href="<?= $post->url() ?>" class="reveal group">
                        <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition duration-300 group-hover:-translate-y-1 group-hover:shadow-xl">
                            <?php if ($image = $post->cover()->toFile()): ?>
                                <img
                                    src="<?= esc($image->url()) ?>"
                                    alt="<?= esc((string) $post->title()->value()) ?>"
                                    class="h-56 w-full object-cover transition duration-500 group-hover:scale-105"
                                    loading="lazy"
                                >
                            <?php else: ?>
                                <div class="grid h-56 place-items-center bg-slate-100 text-sm text-slate-500">
                                    No cover image
                                </div>
                            <?php endif ?>

                            <div class="p-6">
                                <h2 class="text-xl font-bold text-ink transition group-hover:text-slate-700">
                                    <?= esc((string) $post->title()->value()) ?>
                                </h2>

                                <?php if ($post->description()->isNotEmpty()): ?>
                                    <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-600">
                                        <?= esc((string) $post->description()->value()) ?>
                                    </p>
                                <?php endif ?>

                                <span class="mt-4 inline-flex text-sm font-bold text-ink">
                                    Read article →
                                </span>
                            </div>
                        </article>
                    </a>
                <?php endforeach ?>
            </div>
        </div>
    </section>
</main>

<?php snippet('footer') ?>
