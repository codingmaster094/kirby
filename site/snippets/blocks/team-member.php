<article class="team-member overflow-hidden rounded-3xl border border-white/10 bg-panel text-center shadow-xl shadow-black/20">
    <?php $image = $block->image()->toFiles()->first(); ?>

    <?php if ($image): ?>
        <img
            src="<?= esc($image->url()) ?>"
            alt="<?= esc($block->name()->value()) ?>"
            class="h-72 w-full object-cover"
            loading="lazy"
        >
    <?php else: ?>
        <div class="grid h-72 place-items-center bg-white/5 text-sm text-soft">
            Upload profile image
        </div>
    <?php endif ?>

    <div class="p-6">
        <?php if ($block->name()->isNotEmpty()): ?>
            <h3 class="text-xl font-bold text-white">
                <?= esc($block->name()->value()) ?>
            </h3>
        <?php endif ?>

        <?php if ($block->position()->isNotEmpty()): ?>
            <p class="mt-1 text-sm font-semibold text-lime">
                <?= esc($block->position()->value()) ?>
            </p>
        <?php endif ?>

        <?php if ($block->description()->isNotEmpty()): ?>
            <div class="mt-4 text-sm leading-6 text-soft">
                <?= $block->description()->kt() ?>
            </div>
        <?php endif ?>

        <?php if ($block->linkedin()->isNotEmpty()): ?>
            <a
                href="<?= esc($block->linkedin()->value()) ?>"
                target="_blank"
                rel="noopener noreferrer"
                class="mt-5 inline-flex text-sm font-bold text-white transition hover:text-lime"
            >
                LinkedIn →
            </a>
        <?php endif ?>
    </div>
</article>
