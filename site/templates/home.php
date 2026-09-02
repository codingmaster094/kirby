<?php snippet('header') ?>

<main class="flex-1">
    <?php
    snippet('layout');

    if ($page->layout()->isEmpty()) {
        snippet('sections/hero');
        snippet('sections/benefits');
    }
    ?>
</main>

<?php snippet('footer') ?>
