<?php
$eyebrow = (string) $block->eyebrow()->or('Our team')->value();
$heading = (string) $block->heading()->value();
$description = (string) $block->description()->kirbytext()->value();
$members = $block->members()->toBlocks();
?>
<section class="bg-ink px-4 py-16 sm:px-6 lg:px-10 lg:py-24">
    <div class="mx-auto max-w-7xl">
        <div class="reveal max-w-3xl">
            <?php if ($eyebrow !== ''): ?>
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-soft"><?= esc($eyebrow) ?></p>
            <?php endif ?>
            <?php if ($heading !== ''): ?>
                <h2 class="mt-3 text-4xl font-extrabold tracking-tight text-white sm:text-5xl"><?= esc($heading) ?></h2>
            <?php endif ?>
            <?php if ($description !== ''): ?>
                <div class="mt-4 text-base leading-7 text-soft"><?= $description ?></div>
            <?php endif ?>
        </div>

        <?php if ($members->isNotEmpty()): ?>
            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($members as $member): ?>
                    <?php if ($member->isHidden()) {
                        continue;
                    } ?>
                    <div class="reveal">
                        <?php snippet('blocks/' . $member->type(), [
                            'block' => $member,
                            'page'  => $page,
                        ]) ?>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>
</section>
