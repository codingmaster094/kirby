<?php snippet('header') ?>

<?php
$concernsPayload = [];

foreach ($page->concerns()->toStructure() as $concern) {
    $keywords = array_values(array_filter(array_map(
        static fn ($part) => strtolower(trim($part)),
        preg_split('/[,|]+/', (string) $concern->keywords()->value()) ?: []
    )));

    // Always include the title as a keyword.
    $titleKey = strtolower(trim((string) $concern->title()->value()));
    if ($titleKey !== '' && !in_array($titleKey, $keywords, true)) {
        array_unshift($keywords, $titleKey);
    }

    $exercises = [];
    foreach ($concern->exercises()->toStructure() as $exercise) {
        $name = trim((string) $exercise->name()->value());
        $how  = trim((string) $exercise->how()->value());
        if ($name === '' || $how === '') {
            continue;
        }

        $exercises[] = [
            'name' => $name,
            'how'  => $how,
            'sets' => trim((string) $exercise->sets()->value()),
            'tip'  => trim((string) $exercise->tip()->value()),
        ];
    }

    $concernsPayload[] = [
        'id'        => $concern->id(),
        'title'     => (string) $concern->title()->value(),
        'keywords'  => $keywords,
        'summary'   => (string) $concern->summary()->value(),
        'focus'     => (string) $concern->focus()->value(),
        'exercises' => $exercises,
    ];
}

$placeholder = (string) $page->searchPlaceholder()->or('e.g. I have belly fat')->value();
$searchHelp  = (string) $page->searchHelp()->or('Type your problem or tap a suggestion below.')->value();
$emptyMsg    = (string) $page->emptyMessage()->or('No match yet. Try another phrase or pick a suggestion.')->value();
?>

<main class="flex-1 bg-ink">
    <?php snippet('layout') ?>

    <section class="px-4 py-16 sm:px-6 lg:px-10 lg:py-24">
        <div class="reveal mx-auto max-w-3xl text-center">
            <?php if ($page->eyebrow()->isNotEmpty()): ?>
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-soft">
                    <?= esc($page->eyebrow()->value()) ?>
                </p>
            <?php endif ?>

            <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-white sm:text-5xl">
                <?= esc($page->heading()->or($page->title())->value()) ?>
            </h1>

            <?php if ($page->description()->isNotEmpty()): ?>
                <div class="mx-auto mt-4 max-w-2xl text-base leading-7 text-soft">
                    <?= $page->description()->kt() ?>
                </div>
            <?php endif ?>
        </div>

        <div
            id="workout-guide"
            class="reveal mx-auto mt-12 max-w-3xl"
            data-empty="<?= esc($emptyMsg) ?>"
            data-concerns="<?= esc(json_encode($concernsPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ?>"
        >
            <label for="workout-query" class="sr-only">Your goal or problem</label>
            <div class="relative">
                <input
                    id="workout-query"
                    type="search"
                    autocomplete="off"
                    placeholder="<?= esc($placeholder) ?>"
                    class="w-full rounded-2xl border border-white/15 bg-panel px-5 py-4 pr-12 text-base text-white outline-none transition placeholder:text-white/35 focus:border-lime"
                >
                <span class="pointer-events-none absolute inset-y-0 right-4 grid place-items-center text-soft" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="M20 20l-3-3" stroke-linecap="round"/>
                    </svg>
                </span>
            </div>

            <?php if ($searchHelp !== ''): ?>
                <p class="mt-3 text-center text-sm text-soft"><?= esc($searchHelp) ?></p>
            <?php endif ?>

            <?php if ($concernsPayload !== []): ?>
                <div id="workout-chips" class="mt-6 flex flex-wrap justify-center gap-2">
                    <?php foreach ($concernsPayload as $concern): ?>
                        <button
                            type="button"
                            class="workout-chip rounded-full border border-white/15 bg-white/5 px-4 py-2 text-sm font-semibold text-white transition hover:border-lime hover:bg-lime/10 hover:text-lime"
                            data-query="<?= esc($concern['title']) ?>"
                        >
                            <?= esc($concern['title']) ?>
                        </button>
                    <?php endforeach ?>
                </div>
            <?php endif ?>

            <div id="workout-status" class="mt-8 hidden text-center text-sm text-soft" role="status"></div>
            <div id="workout-results" class="mt-8 space-y-6" aria-live="polite"></div>
        </div>
    </section>
</main>

<script>
(function () {
    const root = document.getElementById('workout-guide');
    if (!root) return;

    const input = document.getElementById('workout-query');
    const results = document.getElementById('workout-results');
    const status = document.getElementById('workout-status');
    const emptyMessage = root.getAttribute('data-empty') || 'No match yet.';
    let concerns = [];

    try {
        concerns = JSON.parse(root.getAttribute('data-concerns') || '[]');
    } catch (e) {
        concerns = [];
    }

    const escapeHtml = function (value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    };

    const normalize = function (value) {
        return String(value || '')
            .toLowerCase()
            .replace(/[^a-z0-9\s]/g, ' ')
            .replace(/\s+/g, ' ')
            .trim();
    };

    const scoreConcern = function (query, concern) {
        const q = normalize(query);
        if (!q) return 0;

        let score = 0;
        const keywords = concern.keywords || [];

        keywords.forEach(function (keyword) {
            const k = normalize(keyword);
            if (!k) return;
            if (q === k) score += 100;
            else if (q.indexOf(k) !== -1) score += 60;
            else if (k.indexOf(q) !== -1 && q.length > 2) score += 40;
            else {
                const parts = q.split(' ');
                parts.forEach(function (part) {
                    if (part.length > 2 && k.indexOf(part) !== -1) score += 15;
                });
            }
        });

        return score;
    };

    const renderConcern = function (concern) {
        const exercises = (concern.exercises || []).map(function (exercise, index) {
            return (
                '<li class="rounded-2xl border border-white/10 bg-ink/60 p-5">' +
                    '<div class="flex items-start gap-3">' +
                        '<span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-lime text-sm font-extrabold text-ink">' +
                            (index + 1) +
                        '</span>' +
                        '<div class="min-w-0 flex-1">' +
                            '<h3 class="text-lg font-extrabold text-white">' + escapeHtml(exercise.name) + '</h3>' +
                            (exercise.sets
                                ? '<p class="mt-1 text-xs font-bold uppercase tracking-[0.18em] text-lime">' + escapeHtml(exercise.sets) + '</p>'
                                : '') +
                            '<p class="mt-2 text-sm leading-6 text-soft">' + escapeHtml(exercise.how) + '</p>' +
                            (exercise.tip
                                ? '<p class="mt-3 text-sm text-white/90"><span class="font-bold text-lime">Tip:</span> ' + escapeHtml(exercise.tip) + '</p>'
                                : '') +
                        '</div>' +
                    '</div>' +
                '</li>'
            );
        }).join('');

        return (
            '<article class="overflow-hidden rounded-[1.75rem] border border-white/10 bg-panel p-6 sm:p-8">' +
                '<p class="text-xs font-bold uppercase tracking-[0.22em] text-lime">Matched plan</p>' +
                '<h2 class="mt-2 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">' +
                    escapeHtml(concern.title) +
                '</h2>' +
                (concern.summary
                    ? '<p class="mt-3 text-base leading-7 text-soft">' + escapeHtml(concern.summary) + '</p>'
                    : '') +
                (concern.focus
                    ? '<div class="mt-5 rounded-2xl border border-lime/25 bg-lime/10 px-4 py-3 text-sm leading-6 text-white">' +
                        '<span class="font-bold text-lime">Focus on: </span>' + escapeHtml(concern.focus) +
                      '</div>'
                    : '') +
                (exercises
                    ? '<ol class="mt-6 space-y-3">' + exercises + '</ol>'
                    : '<p class="mt-6 text-sm text-soft">Exercises coming soon for this goal.</p>') +
            '</article>'
        );
    };

    const runSearch = function (query, fromChip) {
        const q = String(query || '').trim();
        input.value = q;

        document.querySelectorAll('.workout-chip').forEach(function (chip) {
            const active = normalize(chip.getAttribute('data-query')) === normalize(q);
            chip.classList.toggle('border-lime', active);
            chip.classList.toggle('bg-lime/15', active);
            chip.classList.toggle('text-lime', active);
        });

        if (!q) {
            results.innerHTML = '';
            status.classList.add('hidden');
            status.textContent = '';
            return;
        }

        const matches = concerns
            .map(function (concern) {
                return { concern: concern, score: scoreConcern(q, concern) };
            })
            .filter(function (item) { return item.score > 0; })
            .sort(function (a, b) { return b.score - a.score; });

        if (!matches.length) {
            results.innerHTML = '';
            status.textContent = emptyMessage;
            status.classList.remove('hidden');
            return;
        }

        status.classList.add('hidden');
        results.innerHTML = matches.slice(0, 2).map(function (item) {
            return renderConcern(item.concern);
        }).join('');

        if (fromChip) {
            results.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };

    let timer = null;
    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(function () {
            runSearch(input.value, false);
        }, 180);
    });

    document.querySelectorAll('.workout-chip').forEach(function (chip) {
        chip.addEventListener('click', function () {
            runSearch(chip.getAttribute('data-query') || '', true);
        });
    });
})();
</script>

<?php snippet('footer') ?>
