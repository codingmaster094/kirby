<?php snippet('header') ?>

<main class="flex-1">
    <?php snippet('layout') ?>

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

            <div class="mt-12 grid auto-rows-fr gap-8 md:grid-cols-2 xl:grid-cols-3">
                <?php foreach ($page->children()->listed() as $post): ?>
                    <a href="<?= $post->url() ?>" class="reveal group flex h-full">
                        <article class="flex h-full w-full flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition duration-300 group-hover:-translate-y-1 group-hover:shadow-xl">
                            <?php if ($image = $post->cover()->toFile()): ?>
                                <img
                                    src="<?= esc($image->url()) ?>"
                                    alt="<?= esc((string) $post->title()->value()) ?>"
                                    class="h-56 w-full shrink-0 object-cover transition duration-500 group-hover:scale-105"
                                    loading="lazy"
                                >
                            <?php else: ?>
                                <div class="grid h-56 shrink-0 place-items-center bg-slate-100 text-sm text-slate-500">
                                    No cover image
                                </div>
                            <?php endif ?>

                            <div class="flex flex-1 flex-col p-6">
                                <h2 class="line-clamp-2 min-h-[3.5rem] text-xl font-bold leading-7 text-ink transition group-hover:text-slate-700">
                                    <?= esc((string) $post->title()->value()) ?>
                                </h2>

                                <p class="mt-2 line-clamp-3 min-h-[4.5rem] text-sm leading-6 text-slate-600">
                                    <?php if ($post->description()->isNotEmpty()): ?>
                                        <?= esc((string) $post->description()->value()) ?>
                                    <?php endif ?>
                                </p>

                                <span class="mt-auto inline-flex pt-4 text-sm font-bold text-ink">
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
