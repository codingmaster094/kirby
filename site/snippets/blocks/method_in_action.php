<?php

$heading = (string) $block->heading()->value();
$slides = $block->slides()->toStructure();
$id = 'method-swiper-' . $block->id();
$modalId = 'method-modal-' . $block->id();
$slideCount = $slides->count();

?>

<?php if ($slides->isNotEmpty()): ?>

    <?php if (!defined('KIRBY_METHOD_SWIPER_ASSETS')): ?>
        <?php define('KIRBY_METHOD_SWIPER_ASSETS', true) ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <?php endif ?>

    <section id="how-it-works" class="overflow-hidden bg-white py-16 sm:py-20 lg:py-28">
        <div class="mx-auto max-w-[1400px] px-4 sm:px-6">
            <?php
            $eyebrow = (string) $block->eyebrow()->value();
            $description = (string) $block->description()->kirbytext()->value();
            ?>
            <div class="mx-auto max-w-3xl text-center">
                <?php if ($eyebrow !== ''): ?>
                    <p class="reveal text-xs font-bold uppercase tracking-[0.22em] text-slate-500"><?= esc($eyebrow) ?></p>
                <?php endif ?>
                <?php if ($heading !== ''): ?>
                    <h2 class="reveal mt-3 text-3xl font-extrabold uppercase tracking-tight text-ink sm:text-5xl lg:text-[3.25rem]">
                        <?= esc($heading) ?>
                    </h2>
                <?php endif ?>
                <?php if ($description !== ''): ?>
                    <div class="reveal mx-auto mt-4 max-w-2xl text-base leading-7 text-slate-600"><?= $description ?></div>
                <?php endif ?>
            </div>

            <div class="reveal method-carousel relative mx-auto mt-12 sm:mt-16">
                <div class="method-carousel-viewport overflow-hidden">
                    <div
                        id="<?= esc($id) ?>"
                        class="method-swiper swiper"
                        data-slide-count="<?= (int) $slideCount ?>"
                    >
                        <div class="swiper-wrapper">
                            <?php foreach ($slides as $slide): ?>
                                <?php
                                $image = $slide->image()->toFiles()->first();
                                $videoFile = $slide->video()->toFiles()->first();
                                $videoUrl = trim((string) $slide->videoUrl()->value());
                                $alt = (string) $slide->alt()->value();

                                if ($alt === '') {
                                    $alt = (string) $slide->caption()->value() ?: $heading;
                                }

                                $hasVideo = $videoFile || $videoUrl !== '';
                                $videoType = $videoFile ? 'file' : 'embed';
                                $videoSource = $videoFile ? $videoFile->url() : $videoUrl;
                                $videoMime = $videoFile ? $videoFile->mime() : '';
                                ?>

                                <div class="swiper-slide">
                                    <article class="method-card relative overflow-hidden rounded-[1.75rem] bg-slate-200 shadow-[0_20px_50px_rgba(0,0,0,0.12)]">
                                        <?php if ($videoFile): ?>
                                            <video
                                                class="method-card-preview absolute inset-0 h-full w-full object-cover"
                                                src="<?= esc($videoFile->url()) ?>"
                                                <?php if ($image): ?>
                                                    poster="<?= esc($image->url()) ?>"
                                                <?php endif ?>
                                                muted
                                                loop
                                                playsinline
                                                webkit-playsinline
                                                preload="metadata"
                                            ></video>
                                        <?php elseif ($image): ?>
                                            <img
                                                src="<?= esc($image->url()) ?>"
                                                alt="<?= esc($alt) ?>"
                                                class="absolute inset-0 h-full w-full object-cover"
                                                loading="lazy"
                                            >
                                        <?php else: ?>
                                            <div class="absolute inset-0 grid place-items-center bg-slate-100 text-sm text-slate-500">
                                                Upload cover image or video
                                            </div>
                                        <?php endif ?>

                                        <div class="method-card-wash pointer-events-none absolute inset-0"></div>

                                        <?php if ($hasVideo): ?>
                                            <button
                                                type="button"
                                                class="method-play-btn absolute left-1/2 top-1/2 z-10 grid h-[3.75rem] w-[3.75rem] -translate-x-1/2 -translate-y-1/2 place-items-center rounded-full bg-white text-ink shadow-[0_8px_30px_rgba(0,0,0,0.18)] transition-transform duration-300 hover:scale-110"
                                                aria-label="Play video"
                                                data-modal="<?= esc($modalId) ?>"
                                                data-video-type="<?= esc($videoType) ?>"
                                                data-video-source="<?= esc($videoSource) ?>"
                                                <?php if ($videoMime !== ''): ?>
                                                    data-video-mime="<?= esc($videoMime) ?>"
                                                <?php endif ?>
                                            >
                                                <svg viewBox="0 0 24 24" fill="currentColor" class="ml-1 h-7 w-7">
                                                    <path d="M8 5.14v13.72a1 1 0 0 0 1.52.85l10.17-6.86a1 1 0 0 0 0-1.66L9.52 4.29A1 1 0 0 0 8 5.14Z"/>
                                                </svg>
                                            </button>
                                        <?php endif ?>
                                    </article>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>

                <div class="method-nav-layer pointer-events-none absolute inset-0 z-20 flex items-center justify-center">
                    <div class="method-nav-track flex w-[min(100%,280px)] justify-between sm:w-[min(100%,320px)] lg:w-[min(100%,360px)]">
                        <button
                            type="button"
                            class="method-prev-<?= esc($id) ?> pointer-events-auto grid h-10 w-10 -translate-x-1/2 place-items-center rounded-full bg-ink text-white shadow-lg transition hover:scale-105"
                            aria-label="Previous slide"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="h-5 w-5">
                                <path d="m15 18-6-6 6-6"/>
                            </svg>
                        </button>

                        <button
                            type="button"
                            class="method-next-<?= esc($id) ?> pointer-events-auto grid h-10 w-10 translate-x-1/2 place-items-center rounded-full bg-ink text-white shadow-lg transition hover:scale-105"
                            aria-label="Next slide"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="h-5 w-5">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div
        id="<?= esc($modalId) ?>"
        class="method-video-modal fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 p-4 backdrop-blur-sm"
        aria-hidden="true"
        role="dialog"
        aria-modal="true"
    >
        <div class="relative w-full max-w-4xl">
            <button
                type="button"
                class="method-modal-close absolute -top-12 right-0 grid h-10 w-10 place-items-center rounded-full bg-white/10 text-white transition hover:bg-white/20"
                aria-label="Close video"
            >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                    <path d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </button>
            <div class="method-video-shell aspect-video overflow-hidden rounded-2xl bg-black shadow-2xl">
                <iframe
                    class="method-video-frame hidden h-full w-full"
                    src=""
                    title="Video player"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                ></iframe>
                <video
                    class="method-video-player hidden h-full w-full bg-black object-contain"
                    controls
                    playsinline
                    preload="metadata"
                ></video>
            </div>
        </div>
    </div>

    <style>
        #<?= esc($id) ?> {
            overflow: visible;
            padding: 2rem 0;
        }

        #<?= esc($id) ?> .swiper-wrapper {
            align-items: center;
        }

        #<?= esc($id) ?> .swiper-slide {
            width: 280px !important;
            height: auto;
        }

        #<?= esc($id) ?> .method-card {
            aspect-ratio: 3 / 4.2;
            width: 100%;
            transform: scale(0.78);
            opacity: 0.55;
            transition: transform 0.55s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.55s ease;
        }

        #<?= esc($id) ?> .method-card-wash {
            background: rgba(255, 255, 255, 0.62);
            transition: background-color 0.55s ease;
        }

        #<?= esc($id) ?> .swiper-slide-active .method-card {
            transform: scale(1);
            opacity: 1;
        }

        #<?= esc($id) ?> .swiper-slide-active .method-card-wash {
            background: rgba(255, 255, 255, 0);
        }

        @media (min-width: 640px) {
            #<?= esc($id) ?> .swiper-slide {
                width: 320px !important;
            }

            #<?= esc($id) ?> .method-card {
                transform: scale(0.8);
            }
        }

        @media (min-width: 1024px) {
            #<?= esc($id) ?> .swiper-slide {
                width: 360px !important;
            }
        }
    </style>

    <script>
        window.__methodSwipers = window.__methodSwipers || [];
        window.__methodSwipers.push({
            id: <?= json_encode($id) ?>,
            modalId: <?= json_encode($modalId) ?>,
            slideCount: <?= (int) $slideCount ?>
        });
    </script>

    <?php if (!defined('KIRBY_METHOD_SWIPER_BOOT')): ?>
        <?php define('KIRBY_METHOD_SWIPER_BOOT', true) ?>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script>
            (function () {
                function toEmbedUrl(url) {
                    if (!url) {
                        return '';
                    }

                    try {
                        const parsed = new URL(url);

                        if (parsed.hostname.includes('youtu.be')) {
                            return 'https://www.youtube.com/embed/' + parsed.pathname.replace('/', '') + '?autoplay=1';
                        }

                        if (parsed.hostname.includes('youtube.com')) {
                            const videoId = parsed.searchParams.get('v');
                            if (videoId) {
                                return 'https://www.youtube.com/embed/' + videoId + '?autoplay=1';
                            }
                        }

                        if (parsed.hostname.includes('vimeo.com')) {
                            return 'https://player.vimeo.com/video/' + parsed.pathname.replace('/', '') + '?autoplay=1';
                        }
                    } catch (error) {
                        return '';
                    }

                    return url;
                }

                function pausePreviewVideos(container) {
                    if (!container) {
                        return;
                    }

                    container.querySelectorAll('video.method-card-preview').forEach(function (video) {
                        video.pause();
                    });
                }

                function playActivePreviewVideo(swiper) {
                    const container = swiper && swiper.el ? swiper.el : swiper;

                    if (!container) {
                        return;
                    }

                    pausePreviewVideos(container);

                    let activeVideo = null;

                    if (swiper && swiper.slides && typeof swiper.activeIndex !== 'undefined') {
                        const activeSlide = swiper.slides[swiper.activeIndex];
                        activeVideo = activeSlide ? activeSlide.querySelector('video.method-card-preview') : null;
                    }

                    if (!activeVideo) {
                        activeVideo = container.querySelector('.swiper-slide-active video.method-card-preview');
                    }

                    if (!activeVideo) {
                        return;
                    }

                    activeVideo.muted = true;
                    activeVideo.playsInline = true;
                    activeVideo.loop = true;

                    const playPromise = activeVideo.play();

                    if (playPromise && typeof playPromise.catch === 'function') {
                        playPromise.catch(function () {
                            window.setTimeout(function () {
                                activeVideo.play().catch(function () {});
                            }, 150);
                        });
                    }
                }

                function initVideoModal(modalId) {
                    const modal = document.getElementById(modalId);
                    const frame = modal ? modal.querySelector('.method-video-frame') : null;
                    const player = modal ? modal.querySelector('.method-video-player') : null;
                    const closeBtn = modal ? modal.querySelector('.method-modal-close') : null;

                    if (!modal || !frame || !player || modal.dataset.ready === 'true') {
                        return;
                    }

                    modal.dataset.ready = 'true';

                    const closeModal = function () {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                        modal.setAttribute('aria-hidden', 'true');
                        frame.src = '';
                        frame.classList.add('hidden');
                        player.pause();
                        player.removeAttribute('src');
                        player.innerHTML = '';
                        player.load();
                        player.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');

                        document.querySelectorAll('.method-swiper').forEach(function (swiperEl) {
                            if (swiperEl.swiper) {
                                playActivePreviewVideo(swiperEl.swiper);
                            }
                        });
                    };

                    document.querySelectorAll('[data-modal="' + modalId + '"]').forEach(function (button) {
                        button.addEventListener('click', function () {
                            const videoType = button.getAttribute('data-video-type');
                            const videoSource = button.getAttribute('data-video-source');

                            if (!videoSource) {
                                return;
                            }

                            document.querySelectorAll('video.method-card-preview').forEach(function (video) {
                                video.pause();
                            });

                            if (videoType === 'file') {
                                frame.classList.add('hidden');
                                frame.src = '';
                                player.classList.remove('hidden');
                                player.innerHTML = '';

                                const source = document.createElement('source');
                                source.src = videoSource;

                                const videoMime = button.getAttribute('data-video-mime');

                                if (videoMime) {
                                    source.type = videoMime;
                                }

                                player.appendChild(source);
                                player.load();

                                const playPromise = player.play();

                                if (playPromise && typeof playPromise.catch === 'function') {
                                    playPromise.catch(function () {});
                                }
                            } else {
                                const embedUrl = toEmbedUrl(videoSource);

                                if (!embedUrl) {
                                    return;
                                }

                                player.classList.add('hidden');
                                player.pause();
                                player.removeAttribute('src');
                                player.load();
                                frame.classList.remove('hidden');
                                frame.src = embedUrl;
                            }

                            modal.classList.remove('hidden');
                            modal.classList.add('flex');
                            modal.setAttribute('aria-hidden', 'false');
                            document.body.classList.add('overflow-hidden');
                        });
                    });

                    if (closeBtn) {
                        closeBtn.addEventListener('click', closeModal);
                    }

                    modal.addEventListener('click', function (event) {
                        if (event.target === modal) {
                            closeModal();
                        }
                    });
                }

                function initMethodSwipers() {
                    if (typeof Swiper === 'undefined') {
                        return;
                    }

                    (window.__methodSwipers || []).forEach(function (config) {
                        const el = document.getElementById(config.id);

                        if (!el || el.dataset.initialized === 'true') {
                            return;
                        }

                        el.dataset.initialized = 'true';

                        const swiper = new Swiper(el, {
                            loop: config.slideCount > 1,
                            centeredSlides: true,
                            slidesPerView: 'auto',
                            spaceBetween: 20,
                            speed: 650,
                            grabCursor: true,
                            watchOverflow: true,
                            slideToClickedSlide: true,
                            navigation: {
                                nextEl: '.method-next-' + config.id,
                                prevEl: '.method-prev-' + config.id
                            },
                            breakpoints: {
                                640: { spaceBetween: 24 },
                                1024: { spaceBetween: 28 }
                            },
                            on: {
                                init: function () {
                                    playActivePreviewVideo(this);
                                },
                                slideChange: function () {
                                    pausePreviewVideos(this.el);
                                },
                                slideChangeTransitionEnd: function () {
                                    playActivePreviewVideo(this);
                                }
                            }
                        });

                        initVideoModal(config.modalId);
                    });
                }

                function boot() {
                    initMethodSwipers();
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', boot);
                } else {
                    boot();
                }

                window.addEventListener('load', boot);
            })();
        </script>
    <?php endif ?>

<?php endif ?>
