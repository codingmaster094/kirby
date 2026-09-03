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
$errors = is_array($errors ?? null) ? $errors : [];
$data = is_array($data ?? null) ? $data : [];
$formPage = $page ?? page();

$fieldValue = static function (string $key) use ($data): string {
    if (array_key_exists($key, $data) && $data[$key] !== null && $data[$key] !== '') {
        return (string) $data[$key];
    }
    return (string) (get($key) ?? '');
};

$inputClass = static function (string $key) use ($errors): string {
    $base = 'mt-2 w-full rounded-xl border bg-ink px-4 py-3 text-sm text-white outline-none transition';
    return $base . (isset($errors[$key])
        ? ' border-red-400/70 focus:border-red-300'
        : ' border-white/10 focus:border-lime');
};
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
                <p class="mt-4 rounded-2xl border border-lime/30 bg-lime/10 px-4 py-3 text-sm text-lime" role="status"><?= esc($formSuccess) ?></p>
            <?php endif ?>

            <?php if (!empty($alert)): ?>
                <p class="mt-4 rounded-2xl border border-red-400/30 bg-red-500/10 px-4 py-3 text-sm text-red-200" role="alert"><?= esc($alert) ?></p>
            <?php endif ?>

            <?php if (!$success): ?>
                <form
                    method="post"
                    action="<?= $formPage->url() ?>#contact-form"
                    class="mt-6 space-y-4"
                    id="coaching-contact-form"
                    novalidate
                    data-contact-form
                >
                    <input type="hidden" name="csrf" value="<?= csrf() ?>">
                    <input type="hidden" name="contact-form" value="1">
                    <div class="sr-only" aria-hidden="true">
                        <label for="website">Website</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>
                    <div>
                        <label for="name" class="text-xs font-bold uppercase tracking-[0.18em] text-white">Name <span class="text-lime">*</span></label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            required
                            minlength="2"
                            maxlength="80"
                            autocomplete="name"
                            value="<?= esc($fieldValue('name')) ?>"
                            class="<?= $inputClass('name') ?>"
                            aria-invalid="<?= e(isset($errors['name']), 'true', 'false') ?>"
                            aria-describedby="<?= e(isset($errors['name']), 'error-name') ?>"
                            data-validate="name"
                        >
                        <p id="error-name" class="mt-1.5 text-xs text-red-300 <?= e(isset($errors['name']), '', 'hidden') ?>" data-error-for="name" role="alert">
                            <?= esc($errors['name'] ?? '') ?>
                        </p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="email" class="text-xs font-bold uppercase tracking-[0.18em] text-white">Email <span class="text-lime">*</span></label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                required
                                maxlength="190"
                                autocomplete="email"
                                value="<?= esc($fieldValue('email')) ?>"
                                class="<?= $inputClass('email') ?>"
                                aria-invalid="<?= e(isset($errors['email']), 'true', 'false') ?>"
                                aria-describedby="<?= e(isset($errors['email']), 'error-email') ?>"
                                data-validate="email"
                            >
                            <p id="error-email" class="mt-1.5 text-xs text-red-300 <?= e(isset($errors['email']), '', 'hidden') ?>" data-error-for="email" role="alert">
                                <?= esc($errors['email'] ?? '') ?>
                            </p>
                        </div>
                        <div>
                            <label for="phone" class="text-xs font-bold uppercase tracking-[0.18em] text-white">Phone</label>
                            <input
                                id="phone"
                                name="phone"
                                type="tel"
                                autocomplete="tel"
                                inputmode="tel"
                                value="<?= esc($fieldValue('phone')) ?>"
                                class="<?= $inputClass('phone') ?>"
                                aria-invalid="<?= e(isset($errors['phone']), 'true', 'false') ?>"
                                aria-describedby="<?= e(isset($errors['phone']), 'error-phone') ?>"
                                data-validate="phone"
                            >
                            <p id="error-phone" class="mt-1.5 text-xs text-red-300 <?= e(isset($errors['phone']), '', 'hidden') ?>" data-error-for="phone" role="alert">
                                <?= esc($errors['phone'] ?? '') ?>
                            </p>
                        </div>
                    </div>
                    <?php if ($goals->isNotEmpty()): ?>
                        <div>
                            <label for="goal" class="text-xs font-bold uppercase tracking-[0.18em] text-white">Goal</label>
                            <select
                                id="goal"
                                name="goal"
                                class="<?= $inputClass('goal') ?>"
                                aria-invalid="<?= e(isset($errors['goal']), 'true', 'false') ?>"
                            >
                                <option value="">Select a goal</option>
                                <?php foreach ($goals as $goal): ?>
                                    <?php $label = trim((string) $goal->text()->value()); ?>
                                    <?php if ($label !== ''): ?>
                                        <option value="<?= esc($label) ?>" <?= e($fieldValue('goal') === $label, 'selected') ?>><?= esc($label) ?></option>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </select>
                            <p id="error-goal" class="mt-1.5 text-xs text-red-300 <?= e(isset($errors['goal']), '', 'hidden') ?>" data-error-for="goal" role="alert">
                                <?= esc($errors['goal'] ?? '') ?>
                            </p>
                        </div>
                    <?php endif ?>
                    <div>
                        <label for="message" class="text-xs font-bold uppercase tracking-[0.18em] text-white">Message <span class="text-lime">*</span></label>
                        <textarea
                            id="message"
                            name="message"
                            required
                            minlength="10"
                            maxlength="2000"
                            rows="5"
                            class="<?= $inputClass('message') ?>"
                            aria-invalid="<?= e(isset($errors['message']), 'true', 'false') ?>"
                            aria-describedby="<?= e(isset($errors['message']), 'error-message message-hint', 'message-hint') ?>"
                            data-validate="message"
                        ><?= esc($fieldValue('message')) ?></textarea>
                        <p id="message-hint" class="mt-1.5 text-xs text-soft">At least 10 characters.</p>
                        <p id="error-message" class="mt-1.5 text-xs text-red-300 <?= e(isset($errors['message']), '', 'hidden') ?>" data-error-for="message" role="alert">
                            <?= esc($errors['message'] ?? '') ?>
                        </p>
                    </div>
                    <button type="submit" class="btn-lime inline-flex rounded-full px-7 py-3.5 text-sm font-bold tracking-wide">Send message</button>
                </form>
                <script>
                    (function () {
                        var form = document.querySelector('[data-contact-form]');
                        if (!form) return;

                        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        var namePattern = /^[\p{L}\p{M}\s'\-\.]+$/u;

                        var messages = {
                            name: {
                                required: 'Please enter your name.',
                                min: 'Name must be at least 2 characters.',
                                max: 'Name must be 80 characters or fewer.',
                                pattern: 'Name can only include letters, spaces, hyphens, and apostrophes.'
                            },
                            email: {
                                required: 'Please enter your email address.',
                                invalid: 'Please enter a valid email address.'
                            },
                            phone: {
                                invalid: 'Enter a valid phone number (7–15 digits).'
                            },
                            message: {
                                required: 'Please enter a short message.',
                                min: 'Message must be at least 10 characters.',
                                max: 'Message must be 2000 characters or fewer.'
                            }
                        };

                        var setError = function (field, message) {
                            var input = form.querySelector('[name="' + field + '"]');
                            var error = form.querySelector('[data-error-for="' + field + '"]');
                            if (!input || !error) return;

                            if (message) {
                                error.textContent = message;
                                error.classList.remove('hidden');
                                input.setAttribute('aria-invalid', 'true');
                                input.classList.remove('border-white/10', 'focus:border-lime');
                                input.classList.add('border-red-400/70', 'focus:border-red-300');
                            } else {
                                error.textContent = '';
                                error.classList.add('hidden');
                                input.setAttribute('aria-invalid', 'false');
                                input.classList.add('border-white/10', 'focus:border-lime');
                                input.classList.remove('border-red-400/70', 'focus:border-red-300');
                            }
                        };

                        var validateField = function (field) {
                            var input = form.querySelector('[name="' + field + '"]');
                            if (!input) return true;
                            var value = (input.value || '').trim();

                            if (field === 'name') {
                                if (!value) { setError(field, messages.name.required); return false; }
                                if (value.length < 2) { setError(field, messages.name.min); return false; }
                                if (value.length > 80) { setError(field, messages.name.max); return false; }
                                if (!namePattern.test(value)) { setError(field, messages.name.pattern); return false; }
                            }

                            if (field === 'email') {
                                if (!value) { setError(field, messages.email.required); return false; }
                                if (!emailPattern.test(value) || value.length > 190) {
                                    setError(field, messages.email.invalid);
                                    return false;
                                }
                            }

                            if (field === 'phone' && value) {
                                var digits = value.replace(/\D+/g, '');
                                if (digits.length < 7 || digits.length > 15 || !/^[+\d][\d\s().\-]{6,24}$/.test(value)) {
                                    setError(field, messages.phone.invalid);
                                    return false;
                                }
                            }

                            if (field === 'message') {
                                if (!value) { setError(field, messages.message.required); return false; }
                                if (value.length < 10) { setError(field, messages.message.min); return false; }
                                if (value.length > 2000) { setError(field, messages.message.max); return false; }
                            }

                            setError(field, '');
                            return true;
                        };

                        ['name', 'email', 'phone', 'message'].forEach(function (field) {
                            var input = form.querySelector('[name="' + field + '"]');
                            if (!input) return;
                            input.addEventListener('blur', function () { validateField(field); });
                            input.addEventListener('input', function () {
                                if (input.getAttribute('aria-invalid') === 'true') {
                                    validateField(field);
                                }
                            });
                        });

                        form.addEventListener('submit', function (event) {
                            var ok = ['name', 'email', 'phone', 'message'].every(validateField);
                            if (!ok) {
                                event.preventDefault();
                                var firstInvalid = form.querySelector('[aria-invalid="true"]');
                                if (firstInvalid) firstInvalid.focus();
                            }
                        });
                    })();
                </script>
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
