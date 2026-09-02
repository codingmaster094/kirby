<?php

/**
 * Renders a layout field on the current page.
 *
 * @var \Kirby\Cms\Page|null $page
 * @var string $field Field name (default: layout)
 */

$page  = $page ?? page();
$field = $field ?? 'layout';

if (!$page || $page->$field()->isEmpty()) {
	return;
}

foreach ($page->$field()->toLayouts() as $layout) {
	foreach ($layout->columns() as $column) {
		foreach ($column->blocks() as $block) {
			if ($block->isHidden()) {
				continue;
			}

			snippet('blocks/' . $block->type(), [
				'block'   => $block,
				'page'    => $page,
				'alert'   => $alert ?? null,
				'success' => $success ?? null,
			]);
		}
	}
}
