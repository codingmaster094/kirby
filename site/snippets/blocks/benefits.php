<?php
$eyebrow = (string) $block->eyebrow()->value();
$heading = (string) $block->heading()->value();
$description = (string) $block->description()->kirbytext()->value();
$items = $block->items()->toStructure();
?>

<?php if ($heading !== '' || $items->isNotEmpty()): ?>
<section class="bg-ink px-4 py-16 sm:px-6 sm:py-20 lg:px-10 lg:py-28">
    <div class="reveal mx-auto flex max-w-7xl flex-col gap-10 lg:flex-row lg:items-end lg:justify-between lg:gap-12">
        <div class="max-w-xl">
            <?php if ($eyebrow !== ''): ?>
                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.22em] text-soft">
                    <?= esc($eyebrow) ?>
                </p>
            <?php endif ?>

            <?php if ($heading !== ''): ?>
                <h2 class="text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-5xl">
                    <?= esc($heading) ?>
                </h2>
            <?php endif ?>

            <?php if ($description !== ''): ?>
                <div class="mt-4 text-base leading-7 text-soft">
                    <?= $description ?>
                </div>
            <?php endif ?>
        </div>

        <?php if ($items->isNotEmpty()): ?>
            <div class="w-full max-w-2xl border-t border-white/15 pt-8 lg:border-l lg:border-t-0 lg:pl-10 lg:pt-0">
                <div class="grid grid-cols-3 gap-3 sm:gap-8">
                    <?php foreach ($items as $item): ?>
                        <div class="min-w-0 text-center sm:text-left">
                            <p class="text-[1.7rem] font-semibold leading-none tracking-tight text-lime sm:text-5xl md:text-6xl">
                                <?= esc((string) $item->title()->value()) ?>
                            </p>
                            <div class="mt-2 text-[11px] leading-snug text-white sm:text-sm">
                                <?= $item->description()->kirbytext()->value() ?>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        <?php endif ?>
    </div>
</section>
<?php endif ?>
