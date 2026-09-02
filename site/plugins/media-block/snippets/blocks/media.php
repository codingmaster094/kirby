<?php

$images = $block->images()->toFiles();
$selectedId = $block->selected()->value();
$image = $selectedId !== '' ? $images->findBy('id', $selectedId) : null;

if ($image):
?>
	<div class="media-block">
		<img
			src="<?= esc($image->url()) ?>"
			alt="<?= esc($image->alt()->value()) ?>"
		>
	</div>
<?php endif ?>

