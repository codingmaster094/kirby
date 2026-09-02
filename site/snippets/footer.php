<?php $site = site() ?>
<footer class="mt-auto border-t border-white/10 bg-ink text-slate-300">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-10">
        <?php
        $resolveFooterLink = static function ($item): array {
            $text = trim((string) $item->text()->value());
            $url = (string) ($item->link()->toUrl() ?? '');

            if ($url === '' && $item->link()->isNotEmpty()) {
                $url = trim((string) $item->link()->value());
            }

            return [$text, $url];
        };

        $legalHeading = trim((string) $site->footerLegalHeading()->or('Legal')->value());
        $legalLinks = $site->footerLegalLinks()->toStructure();
        ?>
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-1">
                <a href="<?= $site->url() ?>" class="inline-flex">
                    <?php snippet('site-logo', ['variant' => 'footer']) ?>
                </a>

                <?php if ($site->footerHeading()->isNotEmpty()): ?>
                    <p class="mt-5 max-w-md text-base font-semibold text-white">
                        <?= esc($site->footerHeading()->value()) ?>
                    </p>
                <?php endif ?>

                <?php if ($site->footerDescription()->isNotEmpty()): ?>
                    <p class="mt-3 max-w-md text-sm leading-6 text-soft">
                        <?= esc($site->footerDescription()->value()) ?>
                    </p>
                <?php endif ?>
            </div>

            <div>
                <h4 class="text-sm font-bold uppercase tracking-[0.18em] text-white">Links</h4>
                <ul class="mt-4 space-y-2 text-sm">
                    <?php foreach ($site->footerLinks()->toStructure() as $link): ?>
                        <?php [$linkText, $linkUrl] = $resolveFooterLink($link); ?>
                        <?php if ($linkText !== '' && $linkUrl !== ''): ?>
                            <li>
                                <a
                                    href="<?= esc($linkUrl) ?>"
                                    class="text-soft transition hover:text-lime"
                                >
                                    <?= esc($linkText) ?>
                                </a>
                            </li>
                        <?php endif ?>
                    <?php endforeach ?>
                </ul>
            </div>

            <?php if ($legalLinks->isNotEmpty()): ?>
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-[0.18em] text-white">
                        <?= esc($legalHeading !== '' ? $legalHeading : 'Legal') ?>
                    </h4>
                    <ul class="mt-4 space-y-2 text-sm">
                        <?php foreach ($legalLinks as $link): ?>
                            <?php [$linkText, $linkUrl] = $resolveFooterLink($link); ?>
                            <?php if ($linkText !== '' && $linkUrl !== ''): ?>
                                <li>
                                    <a
                                        href="<?= esc($linkUrl) ?>"
                                        class="text-soft transition hover:text-lime"
                                    >
                                        <?= esc($linkText) ?>
                                    </a>
                                </li>
                            <?php endif ?>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>

            <div>
                <h4 class="text-sm font-bold uppercase tracking-[0.18em] text-white">Follow</h4>
                <div class="mt-4 flex flex-wrap gap-3">
                    <?php
                    $socials = [
                        'instagram' => 'instagramLogo',
                        'facebook'  => 'facebookLogo',
                        'twitter'   => 'TwitterLogo',
                    ];
                    ?>

                    <?php foreach ($socials as $social => $logoField): ?>
                        <?php
                        $url = $site->$social();
                        $image = $site->$logoField()->toFile();
                        ?>

                        <?php if ($url->isNotEmpty()): ?>
                            <a
                                href="<?= esc($url->value()) ?>"
                                class="grid h-10 w-10 place-items-center rounded-full border border-white/15 transition hover:border-lime hover:bg-white/5"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="<?= esc(ucfirst($social)) ?>"
                            >
                                <?php if ($image): ?>
                                    <img
                                        src="<?= esc($image->url()) ?>"
                                        alt=""
                                        width="18"
                                        height="18"
                                        class="h-[18px] w-[18px] brightness-0 invert"
                                    >
                                <?php else: ?>
                                    <span class="text-xs font-bold uppercase text-white"><?= esc(substr($social, 0, 1)) ?></span>
                                <?php endif ?>
                            </a>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
            </div>
        </div>

        <div class="mt-10 border-t border-white/10 pt-6 text-sm text-soft">
            © <?= date('Y') ?> <?= esc($site->copyright()->or($site->title())->value()) ?>. All rights reserved.
        </div>
    </div>
</footer>

<button
    type="button"
    id="back-to-top"
    class="back-to-top"
    aria-label="Back to top"
    title="Back to top"
>
    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
        <path d="M12 19V5M5 12l7-7 7 7" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</button>

<?php snippet('cookie-consent') ?>

<script>
    (function () {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        const smoothScrollTo = function (targetY, duration) {
            const startY = window.scrollY || window.pageYOffset || 0;
            const endY = Math.max(0, targetY);
            const distance = endY - startY;

            if (!distance) {
                return;
            }

            if (reduceMotion || duration <= 0) {
                window.scrollTo(0, endY);
                return;
            }

            const startTime = performance.now();
            const easeOutCubic = function (t) {
                return 1 - Math.pow(1 - t, 3);
            };

            const step = function (now) {
                const progress = Math.min((now - startTime) / duration, 1);
                window.scrollTo(0, startY + distance * easeOutCubic(progress));

                if (progress < 1) {
                    requestAnimationFrame(step);
                }
            };

            requestAnimationFrame(step);
        };

        const backToTop = document.getElementById('back-to-top');

        if (backToTop) {
            const toggleBackToTop = function () {
                if (window.scrollY > 400) {
                    backToTop.classList.add('is-visible');
                } else {
                    backToTop.classList.remove('is-visible');
                }
            };

            window.addEventListener('scroll', toggleBackToTop, { passive: true });
            toggleBackToTop();

            backToTop.addEventListener('click', function (event) {
                event.preventDefault();
                smoothScrollTo(0, 700);
            });
        }

        const openBtn = document.getElementById('menu-open');
        const closeBtn = document.getElementById('menu-close');
        const sidebar = document.getElementById('mobile-sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        if (openBtn && closeBtn && sidebar && overlay) {
            const open = function () {
                sidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                sidebar.setAttribute('aria-hidden', 'false');
                openBtn.setAttribute('aria-expanded', 'true');
                document.body.classList.add('overflow-hidden');
            };

            const close = function () {
                sidebar.classList.add('translate-x-full');
                overlay.classList.add('hidden');
                sidebar.setAttribute('aria-hidden', 'true');
                openBtn.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('overflow-hidden');
            };

            openBtn.addEventListener('click', open);
            closeBtn.addEventListener('click', close);
            overlay.addEventListener('click', close);

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    close();
                }
            });
        }

        document.querySelectorAll('[data-scroll], a[href^="#"]').forEach(function (anchor) {
            anchor.addEventListener('click', function (event) {
                const targetId = anchor.getAttribute('href');

                if (!targetId || targetId === '#' || !targetId.startsWith('#')) {
                    return;
                }

                const target = document.querySelector(targetId);

                if (!target) {
                    return;
                }

                event.preventDefault();
                const header = document.querySelector('header');
                const offset = header ? header.offsetHeight + 12 : 0;
                const top = target.getBoundingClientRect().top + window.scrollY - offset;
                smoothScrollTo(top, 700);
            });
        });

        const revealItems = Array.prototype.slice.call(
            document.querySelectorAll('.reveal, .tf-reveal')
        );

        if (!revealItems.length || reduceMotion || !('IntersectionObserver' in window)) {
            revealItems.forEach(function (item) {
                item.classList.add('is-visible');
            });
            return;
        }

        // Mark above-the-fold items visible BEFORE enabling hide styles
        // so refresh never blinks visible sections.
        revealItems.forEach(function (item) {
            const rect = item.getBoundingClientRect();
            const inView = rect.top < window.innerHeight * 0.92 && rect.bottom > 0;
            if (inView) {
                item.classList.add('is-visible');
            }
        });

        document.documentElement.classList.add('reveal-enabled');

        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

        revealItems.forEach(function (item) {
            if (!item.classList.contains('is-visible')) {
                observer.observe(item);
            }
        });
    })();
</script>
</body>
</html>
