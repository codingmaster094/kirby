<?php
$eyebrow = (string) $block->eyebrow()->value();
$heading = (string) $block->heading()->value();
$description = (string) $block->description()->kirbytext()->value();
$email = (string) $block->email()->value();
$phone = (string) $block->phone()->value();
$points = $block->points()->toStructure();
$formHeading = (string) $block->formHeading()->or('Send a message')->value();
$formSuccess = (string) $block->formSuccess()->or('Thanks — we received your message and will get back to you shortly.')->value();
$goals = $block->formGoals()->toStructure();
$bookingUrl = trim((string) $block->bookingUrl()->value());
$isCalendly = $bookingUrl !== '' && str_contains(strtolower($bookingUrl), 'calendly.com');
$alert = $alert ?? null;
$success = !empty($success);
$formPage = $page ?? page();
?>
<section id="contact" class="bg-ink px-4 py-16 sm:px-6 lg:px-10 lg:py-24">
    <div class="reveal mx-auto grid max-w-7xl gap-10 lg:grid-cols-[1fr_1.05fr] lg:items-start">
        <div>
            <?php if ($eyebrow !== ''): ?>
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-soft"><?= esc($eyebrow) ?></p>
            <?php endif ?>
            <?php if ($heading !== ''): ?>
                <h2 class="mt-3 text-4xl font-extrabold tracking-tight text-white sm:text-5xl"><?= esc($heading) ?></h2>
            <?php endif ?>
            <?php if ($description !== ''): ?>
                <div class="mt-4 max-w-xl text-base leading-7 text-soft"><?= $description ?></div>
            <?php endif ?>

            <?php if ($points->isNotEmpty()): ?>
                <ul class="mt-8 space-y-3">
                    <?php foreach ($points as $point): ?>
                        <?php $text = (string) $point->text()->value(); ?>
                        <?php if ($text !== ''): ?>
                            <li class="flex gap-3 text-sm text-white/90">
                                <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-lime"></span>
                                <span><?= esc($text) ?></span>
                            </li>
                        <?php endif ?>
                    <?php endforeach ?>
                </ul>
            <?php endif ?>

            <div class="mt-10 space-y-4 text-sm text-soft">
                <?php if ($email !== ''): ?>
                    <p>
                        <span class="block text-xs font-bold uppercase tracking-[0.18em] text-white">Email</span>
                        <a class="mt-1 inline-block text-lime transition hover:brightness-110" href="mailto:<?= esc($email) ?>"><?= esc($email) ?></a>
                    </p>
                <?php endif ?>
                <?php if ($phone !== ''): ?>
                    <p>
                        <span class="block text-xs font-bold uppercase tracking-[0.18em] text-white">Phone / WhatsApp</span>
                        <span class="mt-1 inline-block text-white/90"><?= esc($phone) ?></span>
                    </p>
                <?php endif ?>
            </div>
        </div>

        <div id="contact-form" class="rounded-[1.75rem] border border-white/10 bg-panel p-7 sm:p-8">
            <h3 class="text-xl font-extrabold tracking-tight text-white"><?= esc($formHeading) ?></h3>

            <?php if ($success): ?>
                <p class="mt-4 rounded-2xl border border-lime/30 bg-lime/10 px-4 py-3 text-sm text-lime"><?= esc($formSuccess) ?></p>
            <?php endif ?>

            <?php if (!empty($alert)): ?>
                <p class="mt-4 rounded-2xl border border-red-400/30 bg-red-500/10 px-4 py-3 text-sm text-red-200"><?= esc($alert) ?></p>
            <?php endif ?>

            <?php if (!$success): ?>
                <form method="post" action="<?= $formPage->url() ?>#contact-form" class="mt-6 space-y-4">
                    <input type="hidden" name="csrf" value="<?= csrf() ?>">
                    <input type="hidden" name="contact-form" value="1">
                    <div class="sr-only" aria-hidden="true">
                        <label for="website">Website</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>
                    <div>
                        <label for="name" class="text-xs font-bold uppercase tracking-[0.18em] text-white">Name</label>
                        <input id="name" name="name" type="text" required value="<?= esc(get('name') ?? '') ?>" class="mt-2 w-full rounded-xl border border-white/10 bg-ink px-4 py-3 text-sm text-white outline-none transition focus:border-lime">
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="email" class="text-xs font-bold uppercase tracking-[0.18em] text-white">Email</label>
                            <input id="email" name="email" type="email" required value="<?= esc(get('email') ?? '') ?>" class="mt-2 w-full rounded-xl border border-white/10 bg-ink px-4 py-3 text-sm text-white outline-none transition focus:border-lime">
                        </div>
                        <div>
                            <label for="phone" class="text-xs font-bold uppercase tracking-[0.18em] text-white">Phone</label>
                            <input id="phone" name="phone" type="tel" value="<?= esc(get('phone') ?? '') ?>" class="mt-2 w-full rounded-xl border border-white/10 bg-ink px-4 py-3 text-sm text-white outline-none transition focus:border-lime">
                        </div>
                    </div>
                    <?php if ($goals->isNotEmpty()): ?>
                        <div>
                            <label for="goal" class="text-xs font-bold uppercase tracking-[0.18em] text-white">Goal</label>
                            <select id="goal" name="goal" class="mt-2 w-full rounded-xl border border-white/10 bg-ink px-4 py-3 text-sm text-white outline-none transition focus:border-lime">
                                <option value="">Select a goal</option>
                                <?php foreach ($goals as $goal): ?>
                                    <?php $label = trim((string) $goal->text()->value()); ?>
                                    <?php if ($label !== ''): ?>
                                        <option value="<?= esc($label) ?>" <?= e(get('goal') === $label, 'selected') ?>><?= esc($label) ?></option>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </select>
                        </div>
                    <?php endif ?>
                    <div>
                        <label for="message" class="text-xs font-bold uppercase tracking-[0.18em] text-white">Message</label>
                        <textarea id="message" name="message" required rows="5" class="mt-2 w-full rounded-xl border border-white/10 bg-ink px-4 py-3 text-sm text-white outline-none transition focus:border-lime"><?= esc(get('message') ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn-lime inline-flex rounded-full px-7 py-3.5 text-sm font-bold tracking-wide">Send message</button>
                </form>
            <?php endif ?>
        </div>
    </div>
</section>

<?php if ($bookingUrl !== ''): ?>
    <section id="booking" class="border-t border-white/10 bg-ink px-4 py-16 sm:px-6 lg:px-10 lg:py-24">
        <div class="reveal mx-auto max-w-7xl">
            <?php if ($block->bookingHeading()->isNotEmpty()): ?>
                <h2 class="text-center text-3xl font-extrabold tracking-tight text-white sm:text-4xl"><?= esc($block->bookingHeading()->value()) ?></h2>
            <?php endif ?>
            <?php if ($block->bookingDescription()->isNotEmpty()): ?>
                <p class="mx-auto mt-3 max-w-2xl text-center text-base leading-7 text-soft"><?= esc($block->bookingDescription()->value()) ?></p>
            <?php endif ?>
            <?php if ($isCalendly): ?>
                <div class="mt-10 overflow-hidden rounded-[1.75rem] border border-white/10 bg-panel">
                    <div class="calendly-inline-widget" data-url="<?= esc($bookingUrl) ?>" style="min-width:320px;height:720px;"></div>
                </div>
                <script src="https://assets.calendly.com/assets/external/widget.js" async></script>
            <?php else: ?>
                <div class="mt-10 flex justify-center">
                    <a href="<?= esc($bookingUrl) ?>" class="btn-lime inline-flex rounded-full px-8 py-3.5 text-sm font-bold tracking-wide" target="_blank" rel="noopener noreferrer">Open scheduler</a>
                </div>
            <?php endif ?>
        </div>
    </section>
<?php endif ?>
