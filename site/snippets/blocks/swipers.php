<?php
$slides = $block->slides()->toStructure();
$title = (string) $block->title()->value();
$id = 'swiper-' . $block->id();
?>

<?php if ($slides->isNotEmpty()): ?>
<?php if (!defined('KIRBY_SWIPER_ASSETS')): ?>
    <?php define('KIRBY_SWIPER_ASSETS', true) ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<?php endif ?>

<section class="border-t border-slate-200 bg-white" style="--swiper-theme-color: #2563eb; --swiper-navigation-size: 1.5rem;">
    <div class="mx-auto max-w-6xl px-5 py-16">
        <?php if ($title !== ''): ?>
            <h2 class="mb-8 max-w-2xl text-3xl font-bold tracking-tight text-slate-950">
                <?= esc($title) ?>
            </h2>
        <?php endif ?>

        <div class="relative">
            <div id="<?= esc($id) ?>" class="swiper overflow-hidden rounded-[1.75rem]">
                <div class="swiper-wrapper">
                    <?php foreach ($slides as $slide): ?>
                        <?php
                        $image = $slide->image()->toFiles()->first() ?? $slide->image()->toFile();
                        $heading = (string) $slide->heading()->value();
                        $description = (string) $slide->description()->kirbytext()->value();
                        $link = (string) $slide->link()->toUrl();
                        ?>
                        <div class="swiper-slide">
                            <article class="relative min-h-[22rem] overflow-hidden bg-slate-950 sm:min-h-[28rem]">
                                <?php if ($image): ?>
                                    <img
                                        src="<?= esc($image->url()) ?>"
                                        alt="<?= esc($image->alt()->or($heading)) ?>"
                                        class="absolute inset-0 h-full w-full object-cover"
                                    >
                                <?php endif ?>
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/25 to-transparent"></div>
                                <div class="relative flex min-h-[22rem] flex-col justify-end p-8 sm:min-h-[28rem] sm:p-10">
                                    <?php if ($heading !== ''): ?>
                                        <h3 class="max-w-xl text-2xl font-semibold tracking-tight text-white sm:text-3xl">
                                            <?= esc($heading) ?>
                                        </h3>
                                    <?php endif ?>

                                    <?php if ($description !== ''): ?>
                                        <div class="mt-3 max-w-lg text-sm leading-6 text-slate-200 sm:text-base">
                                            <?= $description ?>
                                        </div>
                                    <?php endif ?>

                                    <?php if ($link !== ''): ?>
                                        <a
                                            href="<?= esc($link) ?>"
                                            class="mt-6 inline-flex h-11 w-fit items-center rounded-full bg-white px-5 text-sm font-semibold text-slate-950 transition hover:bg-slate-100"
                                        >
                                            Read more
                                        </a>
                                    <?php endif ?>
                                </div>
                            </article>
                        </div>
                    <?php endforeach ?>
                </div>

                <div class="swiper-pagination !bottom-5"></div>
                <button type="button" class="swiper-button-prev !left-3 !text-white after:!text-lg" aria-label="Previous slide"></button>
                <button type="button" class="swiper-button-next !right-3 !text-white after:!text-lg" aria-label="Next slide"></button>
            </div>
        </div>
    </div>
</section>

<script>
    (function () {
        if (typeof Swiper === 'undefined') {
            return;
        }

        new Swiper('#<?= esc($id) ?>', {
            loop: true,
            speed: 600,

            slidesPerView: 1,
            spaceBetween: 20,

            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 24
                }   
            },

            pagination: {
                el: '#<?= esc($id) ?> .swiper-pagination',
                clickable: true
            },

            navigation: {
                nextEl: '#<?= esc($id) ?> .swiper-button-next',
                prevEl: '#<?= esc($id) ?> .swiper-button-prev'
            }
});
    })();
</script>
<?php endif ?>
