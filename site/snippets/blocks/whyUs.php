<?php

$eyebrow = (string) $block->eyebrow()->value();
$heading = (string) $block->heading()->value();
$image = $block->image()->toFiles()->first();
$items = $block->items()->toStructure();

?>

<?php if ($heading !== '' || $items->isNotEmpty()): ?>
<section id="why-us" class="bg-ink px-4 py-20 sm:px-6 lg:px-10 lg:py-28">
    <div class="reveal mx-auto max-w-7xl rounded-[2rem] bg-panel px-6 py-12 sm:rounded-[2.5rem] sm:px-10 lg:px-14 lg:py-16">
        <div class="grid gap-10 lg:grid-cols-[0.95fr_1.05fr] lg:gap-16">
            <div>
                <?php if ($eyebrow !== ''): ?>
                    <p class="mb-3 text-xs font-semibold uppercase tracking-[0.22em] text-soft">
                        <?= esc($eyebrow) ?>
                    </p>
                <?php endif ?>

                <?php if ($heading !== ''): ?>
                    <h2 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl">
                        <?= esc($heading) ?>
                    </h2>
                <?php endif ?>

                <div class="mt-10 overflow-hidden rounded-[1.75rem] ring-1 ring-white/10">
                    <?php if ($image): ?>
                        <img
                            src="<?= esc($image->url()) ?>"
                            alt="<?= esc($image->alt()->or($heading)) ?>"
                            class="h-64 w-full object-cover sm:h-80"
                            loading="lazy"
                        >
                    <?php else: ?>
                        <div class="grid h-64 place-items-center bg-white/5 text-sm text-soft sm:h-80">
                            Upload a Why Us image
                        </div>
                    <?php endif ?>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <?php foreach ($items as $item): ?>
                    <?php
                    $number = (string) $item->number()->value();
                    $title = (string) $item->title()->value();
                    $description = (string) $item->description()->kirbytext()->value();
                    ?>

                    <?php if ($title !== ''): ?>
                        <article class="why-card rounded-3xl p-6">
                            <?php if ($number !== ''): ?>
                                <span class="badge-num inline-flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold">
                                    <?= esc($number) ?>
                                </span>
                            <?php endif ?>

                            <h3 class="mt-5 text-xl font-extrabold text-white">
                                <?= esc($title) ?>
                            </h3>

                            <?php if ($description !== ''): ?>
                                <div class="mt-3 text-sm leading-relaxed text-soft">
                                    <?= $description ?>
                                </div>
                            <?php endif ?>
                        </article>
                    <?php endif ?>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</section>
<?php endif ?>
