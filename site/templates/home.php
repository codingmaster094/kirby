<?php snippet('header') ?>

<main class="flex-1">
    <?php
    $rendered = false;

    foreach ($page->layout()->toLayouts() as $layout) {
        foreach ($layout->columns() as $column) {
            foreach ($column->blocks() as $block) {
                if ($block->isHidden()) {
                    continue;
                }

                snippet('blocks/' . $block->type(), [
                    'block' => $block,
                    'page'  => $page,
                ]);

                $rendered = true;
            }
        }
    }

    if ($rendered === false) {
        snippet('sections/hero');
        snippet('sections/benefits');
    }
    ?>
</main>

<?php snippet('footer') ?>
