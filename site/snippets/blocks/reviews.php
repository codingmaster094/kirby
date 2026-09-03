<?php

$eyebrow = (string) $block->eyebrow()->value();
$heading = (string) $block->heading()->value();
$description = (string) $block->description()->kirbytext()->value();
$items = $block->items()->toStructure();
$id = 'reviews-swiper-' . $block->id();

if ($heading === '' && $eyebrow === '' && $items->isEmpty()) {
    return;
}

$cards = [];
foreach ($items as $item) {
    $quote = trim((string) $item->quote()->value());
    $name = trim((string) $item->name()->value());
    $role = trim((string) $item->role()->value());
    $rating = max(1, min(5, (int) $item->rating()->or(5)->value()));

    if ($quote === '' || $name === '') {
        continue;
    }

    $cards[] = compact('quote', 'name', 'role', 'rating');
}

if ($cards === []) {
    return;
}
?>

<?php if (!defined('KIRBY_REVIEWS_SWIPER_CSS') && !defined('KIRBY_SWIPER_ASSETS') && !defined('KIRBY_METHOD_SWIPER_ASSETS')): ?>
    <?php define('KIRBY_REVIEWS_SWIPER_CSS', true) ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<?php endif ?>
<?php if (!defined('KIRBY_REVIEWS_SWIPER_JS') && !defined('KIRBY_SWIPER_ASSETS')): ?>
    <?php define('KIRBY_REVIEWS_SWIPER_JS', true) ?>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
<?php endif ?>

<section
    id="reviews"
    class="border-t border-white/10 bg-ink px-4 py-20 sm:px-6 lg:px-10 lg:py-28"
    style="--swiper-theme-color: #d8ff3e; --swiper-navigation-size: 1.25rem;"
>
    <div class="reveal mx-auto max-w-7xl">
        <div class="mx-auto max-w-3xl text-center">
            <?php if ($eyebrow !== ''): ?>
                <p class="text-xs font-bold uppercase tracking-[0.28em] text-soft">
                    <?= esc($eyebrow) ?>
                </p>
            <?php endif ?>

            <?php if ($heading !== ''): ?>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-white sm:text-5xl">
                    <?= esc($heading) ?>
                </h2>
            <?php endif ?>

            <?php if ($description !== ''): ?>
                <div class="mx-auto mt-4 max-w-2xl text-base leading-7 text-soft">
                    <?= $description ?>
                </div>
            <?php endif ?>
        </div>

        <div class="relative mt-12">
            <div id="<?= esc($id) ?>" class="reviews-swiper swiper overflow-visible sm:overflow-hidden">
                <div class="swiper-wrapper">
                    <?php foreach ($cards as $card): ?>
                        <div class="swiper-slide !h-auto">
                            <article class="flex h-full flex-col rounded-[1.75rem] border border-white/10 bg-panel p-6 sm:p-7">
                                <div class="flex items-center gap-1 text-lime" aria-label="<?= esc((string) $card['rating']) ?> out of 5 stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <svg class="h-4 w-4 <?= $i <= $card['rating'] ? 'opacity-100' : 'opacity-25' ?>" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    <?php endfor ?>
                                </div>

                                <blockquote class="mt-5 flex-1 text-base leading-7 text-white/90">
                                    “<?= esc($card['quote']) ?>”
                                </blockquote>

                                <footer class="mt-6 border-t border-white/10 pt-5">
                                    <p class="text-sm font-bold text-white"><?= esc($card['name']) ?></p>
                                    <?php if ($card['role'] !== ''): ?>
                                        <p class="mt-1 text-xs font-semibold uppercase tracking-[0.16em] text-soft"><?= esc($card['role']) ?></p>
                                    <?php endif ?>
                                </footer>
                            </article>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-center gap-4">
                <button
                    type="button"
                    class="reviews-prev-<?= esc($block->id()) ?> inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/15 text-white transition hover:border-lime hover:text-lime"
                    aria-label="Previous review"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M15 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="reviews-pagination-<?= esc($block->id()) ?> !relative !bottom-auto !top-auto !w-auto"></div>
                <button
                    type="button"
                    class="reviews-next-<?= esc($block->id()) ?> inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/15 text-white transition hover:border-lime hover:text-lime"
                    aria-label="Next review"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M9 18l6-6-6-6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>

<style>
    #<?= esc($id) ?> .swiper-slide {
        height: auto;
    }

    .reviews-pagination-<?= esc($block->id()) ?> .swiper-pagination-bullet {
        background: rgba(255, 255, 255, 0.35);
        opacity: 1;
    }

    .reviews-pagination-<?= esc($block->id()) ?> .swiper-pagination-bullet-active {
        background: #d8ff3e;
    }
</style>

<script>
    (function () {
        var elId = <?= json_encode('#' . $id) ?>;
        var prevEl = <?= json_encode('.reviews-prev-' . $block->id()) ?>;
        var nextEl = <?= json_encode('.reviews-next-' . $block->id()) ?>;
        var paginationEl = <?= json_encode('.reviews-pagination-' . $block->id()) ?>;

        function boot() {
            if (typeof Swiper === 'undefined') {
                return false;
            }

            var root = document.querySelector(elId);
            if (!root || root.swiper) {
                return true;
            }

            new Swiper(elId, {
                loop: true,
                speed: 550,
                spaceBetween: 20,
                slidesPerView: 1,
                watchOverflow: true,
                autoplay: {
                    delay: 4500,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 20
                    },
                    1100: {
                        slidesPerView: 3,
                        spaceBetween: 24
                    }
                },
                pagination: {
                    el: paginationEl,
                    clickable: true
                },
                navigation: {
                    nextEl: nextEl,
                    prevEl: prevEl
                }
            });

            return true;
        }

        if (boot()) {
            return;
        }

        // Swiper JS not loaded yet (defer / other block order)
        var script = document.querySelector('script[src*="swiper-bundle.min.js"]');
        if (!script) {
            script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js';
            document.head.appendChild(script);
        }

        script.addEventListener('load', boot);
        var tries = 0;
        var timer = setInterval(function () {
            tries += 1;
            if (boot() || tries > 40) {
                clearInterval(timer);
            }
        }, 100);
    })();
</script>
