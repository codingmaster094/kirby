<?php
$eyebrow = (string) $block->eyebrow()->value();
$heading = (string) $block->heading()->value();
$description = (string) $block->description()->kirbytext()->value();
$searchHelp = (string) $block->searchHelp()->value();
$concernsPayload = [];

foreach ($block->concerns()->toStructure() as $concern) {
    $keywords = array_values(array_filter(array_map(
        static fn ($part) => strtolower(trim($part)),
        preg_split('/[,|]+/', (string) $concern->keywords()->value()) ?: []
    )));

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
?>
<section class="bg-ink px-4 py-16 sm:px-6 lg:px-10 lg:py-24">
    <div class="reveal mx-auto max-w-3xl text-center">
        <?php if ($eyebrow !== ''): ?>
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-soft"><?= esc($eyebrow) ?></p>
        <?php endif ?>
        <?php if ($heading !== ''): ?>
            <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-white sm:text-5xl"><?= esc($heading) ?></h1>
        <?php endif ?>
        <?php if ($description !== ''): ?>
            <div class="mx-auto mt-4 max-w-2xl text-base leading-7 text-soft"><?= $description ?></div>
        <?php endif ?>
        <?php if ($searchHelp !== ''): ?>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-6 text-soft"><?= esc($searchHelp) ?></p>
        <?php endif ?>
    </div>

    <div
        id="workout-guide"
        class="reveal mx-auto mt-12 max-w-3xl"
        data-concerns="<?= esc(json_encode($concernsPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ?>"
    >
        <?php if ($concernsPayload !== []): ?>
            <div id="workout-chips" class="mt-6 flex flex-wrap justify-center gap-2">
                <?php foreach ($concernsPayload as $index => $concern): ?>
                    <button
                        type="button"
                        class="workout-chip rounded-full border border-white/15 bg-white/5 px-4 py-2 text-sm font-semibold text-white transition hover:border-lime hover:bg-lime/10 hover:text-lime"
                        data-index="<?= (int) $index ?>"
                    >
                        <?= esc($concern['title']) ?>
                    </button>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <div id="workout-results" class="mt-8 space-y-6" aria-live="polite"></div>
    </div>
</section>

<script>
(function () {
    const root = document.getElementById('workout-guide');
    if (!root) return;

    const results = document.getElementById('workout-results');
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
                '<p class="text-xs font-bold uppercase tracking-[0.22em] text-lime">Your plan</p>' +
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

    const showConcern = function (index) {
        const concern = concerns[index];
        if (!concern) {
            results.innerHTML = '';
            return;
        }

        document.querySelectorAll('.workout-chip').forEach(function (chip) {
            const active = Number(chip.getAttribute('data-index')) === index;
            chip.classList.toggle('border-lime', active);
            chip.classList.toggle('bg-lime/15', active);
            chip.classList.toggle('text-lime', active);
        });

        results.innerHTML = renderConcern(concern);
        results.scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    document.querySelectorAll('.workout-chip').forEach(function (chip) {
        chip.addEventListener('click', function () {
            showConcern(Number(chip.getAttribute('data-index')));
        });
    });
})();
</script>
