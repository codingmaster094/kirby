<?php
$eyebrow = (string) $block->eyebrow()->or('Journal')->value();
$heading = (string) $block->heading()->or('Blog')->value();
$description = (string) $block->description()->value();
$source = $block->source()->toPage() ?? $page;
$posts = $source ? $source->children()->listed() : new Kirby\Cms\Pages([]);
?>
<section class="border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-10">
        <div class="reveal max-w-2xl">
            <?php if ($eyebrow !== ''): ?>
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-500"><?= esc($eyebrow) ?></p>
            <?php endif ?>
            <?php if ($heading !== ''): ?>
                <h2 class="mt-3 text-4xl font-extrabold tracking-tight text-ink sm:text-5xl"><?= esc($heading) ?></h2>
            <?php endif ?>
            <?php if ($description !== ''): ?>
                <p class="mt-4 text-base leading-7 text-slate-600"><?= esc($description) ?></p>
            <?php endif ?>
        </div>

        <div class="mt-12 grid auto-rows-fr gap-8 md:grid-cols-2 xl:grid-cols-3">
            <?php foreach ($posts as $post): ?>
                <?php
                $postDescription = (string) $post->description()->value();
                $postImage = $post->cover()->toFile();
                if ($post->layout()->isNotEmpty()) {
                    foreach ($post->layout()->toLayouts() as $layout) {
                        foreach ($layout->columns() as $column) {
                            foreach ($column->blocks() as $articleBlock) {
                                if ($articleBlock->type() !== 'article') {
                                    continue;
                                }
                                if ($postDescription === '') {
                                    $postDescription = (string) $articleBlock->description()->value();
                                }
                                if (!$postImage) {
                                    $postImage = $articleBlock->cover()->toFile();
                                }
                            }
                        }
                    }
                }
                ?>
                <a href="<?= $post->url() ?>" class="reveal group flex h-full">
                    <article class="flex h-full w-full flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition duration-300 group-hover:-translate-y-1 group-hover:shadow-xl">
                        <?php if ($postImage): ?>
                            <img src="<?= esc($postImage->url()) ?>" alt="<?= esc((string) $post->title()->value()) ?>" class="h-56 w-full shrink-0 object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                        <?php else: ?>
                            <div class="grid h-56 shrink-0 place-items-center bg-slate-100 text-sm text-slate-500">No cover image</div>
                        <?php endif ?>
                        <div class="flex flex-1 flex-col p-6">
                            <h3 class="line-clamp-2 min-h-[3.5rem] text-xl font-bold leading-7 text-ink transition group-hover:text-slate-700"><?= esc((string) $post->title()->value()) ?></h3>
                            <p class="mt-2 line-clamp-3 min-h-[4.5rem] text-sm leading-6 text-slate-600">
                                <?php if ($postDescription !== ''): ?>
                                    <?= esc($postDescription) ?>
                                <?php endif ?>
                            </p>
                            <span class="mt-auto inline-flex pt-4 text-sm font-bold text-ink">Read article →</span>
                        </div>
                    </article>
                </a>
            <?php endforeach ?>
        </div>
    </div>
</section>
