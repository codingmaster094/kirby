<?php

$eyebrow = (string) $block->eyebrow()->value();
$heading = (string) $block->heading()->value();
$intro = (string) $block->description()->kirbytext()->value();
$items = $block->items()->toStructure()->limit(3);
$total = $items->count();
$sectionId = 'transformations-' . $block->id();

if ($total < 1) {
    return;
}

$pad = static function (int $n): string {
    return str_pad((string) $n, 2, '0', STR_PAD_LEFT);
};
?>

<section
    id="results"
    class="tf-section relative overflow-x-clip bg-ink px-4 py-20 sm:px-6 lg:px-10 lg:py-28"
    data-tf-section
    data-tf-count="<?= $total ?>"
    aria-labelledby="<?= esc($sectionId) ?>-heading"
>
    <div class="mx-auto max-w-7xl">
        <header class="tf-reveal mx-auto max-w-3xl text-center">
            <?php if ($eyebrow !== ''): ?>
                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.22em] text-soft">
                    <?= esc($eyebrow) ?>
                </p>
            <?php endif ?>

            <?php if ($heading !== ''): ?>
                <h2
                    id="<?= esc($sectionId) ?>-heading"
                    class="text-3xl font-extrabold tracking-tight text-white sm:text-5xl"
                >
                    <?= esc($heading) ?>
                </h2>
            <?php endif ?>

            <?php if ($intro !== ''): ?>
                <div class="mx-auto mt-4 max-w-2xl text-base leading-7 text-soft">
                    <?= $intro ?>
                </div>
            <?php endif ?>
        </header>

        <div class="relative mt-14 lg:mt-20">
            <aside
                class="tf-progress pointer-events-none absolute bottom-0 left-0 top-0 z-20 hidden lg:block"
                aria-hidden="true"
            >
                <div class="tf-progress-rail sticky top-[30vh]">
                    <div class="tf-progress-track">
                        <div class="tf-progress-fill" data-tf-progress></div>
                    </div>
                    <ol class="tf-progress-dots">
                        <?php for ($i = 1; $i <= $total; $i++): ?>
                            <li
                                class="tf-progress-dot<?= $i === 1 ? ' is-active' : '' ?>"
                                data-tf-dot="<?= $i - 1 ?>"
                            ></li>
                        <?php endfor ?>
                    </ol>
                </div>
            </aside>

            <div class="flex flex-col gap-16 sm:gap-20 lg:gap-28 lg:pl-14">
                <?php foreach ($items as $index => $item): ?>
                    <?php
                    $title = (string) $item->title()->value();
                    $athlete = trim((string) $item->athlete()->value());
                    $goal = trim((string) $item->goal()->value());
                    $challenge = trim((string) $item->challenge()->value());
                    $approach = trim((string) $item->approach()->value());
                    $result = trim((string) $item->result()->value());
                    $description = (string) $item->description()->kirbytext()->value();
                    $points = $item->points()->toStructure();
                    $image = $item->image()->toFiles()->first()
                        ?? $item->afterImage()->toFiles()->first()
                        ?? $item->beforeImage()->toFiles()->first();
                    $ctaText = (string) $item->ctaText()->value();
                    $ctaUrl = (string) $item->ctaLink()->toUrl();
                    if ($ctaUrl === '' && $item->ctaLink()->isNotEmpty()) {
                        $ctaUrl = (string) $item->ctaLink()->value();
                    }
                    $alt = trim((string) $item->imageAlt()->value());
                    if ($alt === '') {
                        $alt = $title !== '' ? $title . ' athlete story' : 'Athlete coaching story';
                    }
                    $reverse = $index % 2 === 1;
                    $number = $pad($index + 1);
                    $totalLabel = $pad($total);
                    $hasCaseStructure = $athlete !== '' || $goal !== '' || $challenge !== '' || $approach !== '' || $result !== '';
                    ?>

                    <article
                        class="tf-item tf-reveal grid items-center gap-8 lg:grid-cols-2 lg:gap-14 xl:gap-20"
                        data-tf-item
                        data-tf-index="<?= $index ?>"
                    >
                        <div class="<?= $reverse ? 'order-2 lg:order-2' : 'order-1 lg:order-1' ?>">
                            <div class="flex items-center gap-3">
                                <span class="font-mono text-xs font-semibold tracking-[0.2em] text-lime">
                                    <?= esc($number) ?> / <?= esc($totalLabel) ?>
                                </span>
                                <span class="h-px flex-1 bg-white/10"></span>
                            </div>

                            <?php if ($title !== ''): ?>
                                <h3 class="mt-5 text-2xl font-extrabold tracking-tight text-white sm:text-4xl">
                                    <?= esc($title) ?>
                                </h3>
                            <?php endif ?>

                            <?php if ($hasCaseStructure): ?>
                                <dl class="mt-6 space-y-4">
                                    <?php
                                    $rows = [
                                        'Athlete' => $athlete,
                                        'Goal' => $goal,
                                        'Challenge' => $challenge,
                                        'Approach' => $approach,
                                        'Result' => $result,
                                    ];
                                    ?>
                                    <?php foreach ($rows as $label => $value): ?>
                                        <?php if ($value === '') {
                                            continue;
                                        } ?>
                                        <div>
                                            <dt class="text-[11px] font-bold uppercase tracking-[0.18em] text-lime"><?= esc($label) ?></dt>
                                            <dd class="mt-1 text-sm leading-6 text-white/90 sm:text-base"><?= esc($value) ?></dd>
                                        </div>
                                    <?php endforeach ?>
                                </dl>
                            <?php elseif ($description !== ''): ?>
                                <div class="mt-4 max-w-xl text-base leading-7 text-soft">
                                    <?= $description ?>
                                </div>
                            <?php endif ?>

                            <?php if ($hasCaseStructure && $description !== ''): ?>
                                <div class="mt-5 max-w-xl text-sm leading-7 text-soft">
                                    <?= $description ?>
                                </div>
                            <?php endif ?>

                            <?php if ($points->isNotEmpty()): ?>
                                <ul class="mt-6 space-y-3">
                                    <?php foreach ($points as $point): ?>
                                        <?php $pointText = (string) $point->text()->value(); ?>
                                        <?php if ($pointText !== ''): ?>
                                            <li class="flex gap-3 text-sm leading-6 text-white/90">
                                                <span class="mt-2 inline-block h-1.5 w-1.5 shrink-0 rounded-full bg-lime" aria-hidden="true"></span>
                                                <span><?= esc($pointText) ?></span>
                                            </li>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </ul>
                            <?php endif ?>

                            <?php if ($ctaText !== '' && $ctaUrl !== ''): ?>
                                <a
                                    href="<?= esc($ctaUrl) ?>"
                                    class="btn-lime mt-8 inline-flex rounded-full px-7 py-3.5 text-sm font-bold tracking-wide"
                                >
                                    <?= esc($ctaText) ?>
                                </a>
                            <?php endif ?>
                        </div>

                        <div class="<?= $reverse ? 'order-1 lg:order-1' : 'order-2 lg:order-2' ?>">
                            <div class="tf-media relative overflow-hidden rounded-[1.75rem] bg-panel ring-1 ring-white/10">
                                <span class="tf-badge absolute left-4 top-4 z-10 inline-flex rounded-full bg-ink/80 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-lime backdrop-blur sm:left-5 sm:top-5 sm:text-xs">
                                    Case Study
                                </span>

                                <?php if ($image): ?>
                                    <div class="tf-parallax-frame relative overflow-hidden bg-[#101010]">
                                        <img
                                            src="<?= esc($image->url()) ?>"
                                            alt="<?= esc($alt) ?>"
                                            class="tf-parallax-img relative block h-auto w-full object-contain"
                                            loading="lazy"
                                            decoding="async"
                                        >
                                    </div>
                                <?php else: ?>
                                    <div class="grid aspect-[3/2] place-items-center px-6 text-center text-sm text-soft">
                                        Upload a case study image
                                    </div>
                                <?php endif ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</section>

<script>
    (function () {
        var section = document.querySelector('[data-tf-section]');
        if (!section) {
            return;
        }

        var items = Array.prototype.slice.call(section.querySelectorAll('[data-tf-item]'));
        var progress = section.querySelector('[data-tf-progress]');
        var dots = Array.prototype.slice.call(section.querySelectorAll('[data-tf-dot]'));
        var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var isDesktop = window.matchMedia('(min-width: 1024px)').matches;

        var setActive = function (index) {
            dots.forEach(function (dot, i) {
                dot.classList.toggle('is-active', i === index);
            });

            if (progress && items.length > 1) {
                var ratio = index / (items.length - 1);
                progress.style.transform = 'scaleY(' + Math.max(0, Math.min(1, ratio)) + ')';
            } else if (progress) {
                progress.style.transform = 'scaleY(1)';
            }
        };

        if ('IntersectionObserver' in window) {
            var activeObserver = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) {
                        return;
                    }
                    var index = parseInt(entry.target.getAttribute('data-tf-index') || '0', 10);
                    setActive(index);
                });
            }, { threshold: 0.45, rootMargin: '-20% 0px -35% 0px' });

            items.forEach(function (item) {
                activeObserver.observe(item);
            });
        } else {
            setActive(0);
        }

        if (reduceMotion || !isDesktop) {
            return;
        }

        var frames = Array.prototype.slice.call(section.querySelectorAll('.tf-parallax-frame'));
        var ticking = false;

        var updateParallax = function () {
            ticking = false;
            var viewportMid = window.innerHeight * 0.5;

            frames.forEach(function (frame) {
                var img = frame.querySelector('.tf-parallax-img');
                if (!img) {
                    return;
                }

                var rect = frame.getBoundingClientRect();
                if (rect.bottom < 0 || rect.top > window.innerHeight) {
                    return;
                }

                var offset = (rect.top + rect.height * 0.5 - viewportMid) / window.innerHeight;
                var shift = Math.max(-18, Math.min(18, offset * -28));
                img.style.transform = 'translate3d(0, ' + shift.toFixed(2) + 'px, 0) scale(1.04)';
            });
        };

        var onScroll = function () {
            if (!ticking) {
                ticking = true;
                window.requestAnimationFrame(updateParallax);
            }
        };

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', function () {
            isDesktop = window.matchMedia('(min-width: 1024px)').matches;
            if (!isDesktop) {
                frames.forEach(function (frame) {
                    var img = frame.querySelector('.tf-parallax-img');
                    if (img) {
                        img.style.transform = '';
                    }
                });
                return;
            }
            onScroll();
        }, { passive: true });

        updateParallax();
    })();
</script>
