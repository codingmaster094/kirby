<?php

$image = $block->image()->toFiles()->first();
$eyebrow = (string) $block->eyebrow()->value();
$heading = (string) $block->heading()->value();
$description = (string) $block->description()->kirbytext()->value();
$highlights = $block->highlights()->toStructure();
$certifications = $block->certifications()->toStructure();
$primaryText = (string) $block->primaryText()->or('Book a Free Strategy Call')->value();
$primaryUrl = (string) ($block->primaryLink()->toUrl() ?? '');
if ($primaryUrl === '' && $block->primaryLink()->isNotEmpty()) {
    $primaryUrl = (string) $block->primaryLink()->value();
}
if ($primaryUrl === '') {
    $primaryUrl = (string) ((site()->find('contact') ?? site()->homePage())->url());
}
?>

<section id="coach" class="bg-black px-2 py-2 sm:px-4">
    <div class="mx-auto max-w-7xl overflow-hidden rounded-[2.5rem] bg-[#101010]">
        <div class="grid min-h-[32rem] lg:grid-cols-2">
            <div class="relative min-h-[22rem] lg:min-h-[32rem]">
                <?php if ($image): ?>
                    <img
                        src="<?= esc($image->url()) ?>"
                        alt="<?= esc($image->alt()->or('IronPace coach')) ?>"
                        class="absolute inset-0 h-full w-full object-cover"
                        loading="lazy"
                        decoding="async"
                    >
                <?php else: ?>
                    <div class="absolute inset-0 grid place-items-center bg-[#151515] text-sm text-slate-500">
                        Upload coach image in the Panel
                    </div>
                <?php endif ?>
            </div>

            <div class="flex items-center px-8 py-12 sm:px-12 lg:px-16 lg:py-16">
                <div class="w-full max-w-xl">
                    <?php if ($eyebrow !== ''): ?>
                        <p class="mb-4 text-xs font-bold uppercase tracking-[0.25em] text-slate-400">
                            <?= esc($eyebrow) ?>
                        </p>
                    <?php endif ?>

                    <?php if ($heading !== ''): ?>
                        <h2 class="max-w-xl text-4xl font-bold leading-[1.05] tracking-tight text-white sm:text-5xl">
                            <?= nl2br(esc($heading)) ?>
                        </h2>
                    <?php endif ?>

                    <?php if ($description !== ''): ?>
                        <div class="mt-7 max-w-xl text-base leading-7 text-slate-300 sm:text-lg">
                            <?= $description ?>
                        </div>
                    <?php endif ?>

                    <?php if ($highlights->isNotEmpty()): ?>
                        <ul class="mt-7 space-y-3">
                            <?php foreach ($highlights as $highlight): ?>
                                <?php $text = trim((string) $highlight->text()->value()); ?>
                                <?php if ($text !== ''): ?>
                                    <li class="flex gap-3 text-sm leading-6 text-white/90">
                                        <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-lime" aria-hidden="true"></span>
                                        <span><?= esc($text) ?></span>
                                    </li>
                                <?php endif ?>
                            <?php endforeach ?>
                        </ul>
                    <?php endif ?>

                    <?php if ($certifications->isNotEmpty()): ?>
                        <div class="mt-10">
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-soft">Certifications</p>
                            <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <?php foreach ($certifications as $certification): ?>
                                    <?php $name = (string) $certification->name()->value(); ?>
                                    <?php if ($name !== ''): ?>
                                        <div class="flex min-h-9 items-center justify-center rounded-full bg-[#caff24] px-5 py-2 text-center text-sm font-bold text-black">
                                            <?= esc($name) ?>
                                        </div>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </div>
                        </div>
                    <?php endif ?>

                    <?php if ($primaryText !== '' && $primaryUrl !== ''): ?>
                        <a href="<?= esc($primaryUrl) ?>" class="btn-lime mt-10 inline-flex rounded-full px-7 py-3.5 text-sm font-bold tracking-wide">
                            <?= esc($primaryText) ?>
                        </a>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</section>
