<?php
$heading = (string) $block->heading()->or($page->title())->value();
$description = (string) $block->description()->value();
$body = (string) $block->body()->kirbytext()->value();
$image = $block->cover()->toFile();
?>
<article class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-10">
    <div class="reveal">
        <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-500">Article</p>
        <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-ink sm:text-5xl"><?= esc($heading) ?></h1>
        <?php if ($description !== ''): ?>
            <p class="mt-5 text-lg leading-8 text-slate-600"><?= esc($description) ?></p>
        <?php endif ?>
    </div>

    <?php if ($image): ?>
        <img src="<?= esc($image->url()) ?>" alt="<?= esc($heading) ?>" class="reveal mt-10 w-full rounded-3xl object-cover ring-1 ring-black/5">
    <?php endif ?>

    <?php if ($body !== ''): ?>
        <div class="reveal prose prose-slate mt-10 max-w-none text-slate-600 prose-headings:font-extrabold prose-headings:tracking-tight prose-a:text-ink">
            <?= $body ?>
        </div>
    <?php endif ?>
</article>
