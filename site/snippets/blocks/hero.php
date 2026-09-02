<?php

$heading = (string) $block->heading()->value();
$description = (string) $block->description()->kirbytext()->value();
$primaryText = (string) $block->primaryText()->value();
$primaryUrl = (string) $block->primaryLink()->toUrl();
$secondaryText = (string) $block->secondaryText()->value();
$secondaryUrl = (string) $block->secondaryLink()->toUrl();
$image = $block->image()->toFiles()->first();
$video = $block->video()->toFiles()->first();

?>

<section id="home" class="relative min-h-[100svh] overflow-hidden bg-ink">
    <?php if ($video): ?>
        <video
            class="hero-kenburns absolute inset-0 h-full w-full object-cover"
            src="<?= esc($video->url()) ?>"
            autoplay
            muted
            loop
            playsinline
            preload="auto"
            aria-hidden="true"
            data-hero-video
        ></video>
        <script>
            (function () {
                var video = document.querySelector('[data-hero-video]');
                if (!video) {
                    return;
                }

                video.muted = true;
                video.defaultMuted = true;
                video.setAttribute('muted', '');
                video.playsInline = true;

                var tryPlay = function () {
                    var play = video.play();
                    if (play && typeof play.catch === 'function') {
                        play.catch(function () {});
                    }
                };

                if (video.readyState >= 2) {
                    tryPlay();
                } else {
                    video.addEventListener('loadeddata', tryPlay, { once: true });
                    video.addEventListener('canplay', tryPlay, { once: true });
                }
            })();
        </script>
    <?php elseif ($image): ?>
        <img
            src="<?= esc($image->url()) ?>"
            alt="<?= esc($heading) ?>"
            class="hero-kenburns absolute inset-0 h-full w-full object-cover"
            fetchpriority="high"
            decoding="async"
        >
    <?php else: ?>
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-black"></div>
    <?php endif ?>

    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/35 to-black/50"></div>

    <div class="relative mx-auto flex min-h-[100svh] max-w-7xl flex-col justify-end px-4 pb-16 pt-28 sm:px-6 lg:justify-center lg:px-10 lg:pb-24">
        <?php if ($heading !== ''): ?>
            <h1 class="reveal max-w-4xl text-5xl font-extrabold uppercase leading-[0.95] tracking-tight text-white sm:text-7xl md:text-8xl lg:text-[6.5rem]">
                <?= esc($heading) ?>
            </h1>
        <?php endif ?>

        <?php if ($description !== ''): ?>
            <div class="reveal mt-6 max-w-md text-base leading-7 text-white/75 sm:text-lg">
                <?= $description ?>
            </div>
        <?php endif ?>

        <div class="reveal mt-8 flex flex-wrap gap-3">
            <?php if ($primaryText !== '' && $primaryUrl !== ''): ?>
                <a
                    href="<?= esc($primaryUrl) ?>"
                    class="btn-lime inline-flex rounded-full px-7 py-3.5 text-sm font-bold tracking-wide"
                >
                    <?= esc($primaryText) ?>
                </a>
            <?php else: ?>
                <a
                    href="#cta"
                    data-scroll
                    class="btn-lime inline-flex rounded-full px-7 py-3.5 text-sm font-bold tracking-wide"
                >
                    BOOK A CALL
                </a>
            <?php endif ?>

            <?php if ($secondaryText !== '' && $secondaryUrl !== ''): ?>
                <a
                    href="<?= esc($secondaryUrl) ?>"
                    class="inline-flex rounded-full border border-white/30 bg-white/10 px-7 py-3.5 text-sm font-bold tracking-wide text-white backdrop-blur transition hover:bg-white/20"
                >
                    <?= esc($secondaryText) ?>
                </a>
            <?php endif ?>
        </div>
    </div>
</section>
