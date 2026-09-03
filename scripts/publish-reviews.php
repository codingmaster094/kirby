<?php

/**
 * Updates Reviews content in home.txt as JSON (keeps Kirby layout parseable)
 * and removes stale Panel _changes that hide the section in Preview.
 */

$path = dirname(__DIR__) . '/content/1_home/home.txt';
$raw = file_get_contents($path);

if (!preg_match('/^Layout:\s*(\[.*\])\s*$/m', $raw, $match)) {
	fwrite(STDERR, "Could not find JSON Layout field\n");
	exit(1);
}

$layouts = json_decode($match[1], true);
if (!is_array($layouts)) {
	fwrite(STDERR, 'JSON error: ' . json_last_error_msg() . PHP_EOL);
	exit(1);
}

$reviewsContent = [
	'eyebrow' => 'Reviews',
	'heading' => 'What athletes say',
	'description' => 'Real feedback from IronPace members who train with structured coaching and consistent support.',
	'items' => [
		[
			'quote' => 'IronPace has completely changed how I train. Clear plans, great coaching, and results I can feel every week.',
			'name' => 'James Anderson',
			'role' => 'Member · Strength & Fitness',
			'rating' => 5,
		],
		[
			'quote' => 'The personalized training is excellent. My coach adjusted everything around my schedule and I finally stayed consistent.',
			'name' => 'Emily Davis',
			'role' => 'Member · Personal Training',
			'rating' => 5,
		],
		[
			'quote' => 'I love the atmosphere at IronPace. Serious training without the intimidation — and the programming actually works.',
			'name' => 'Ryan Mitchell',
			'role' => 'Member · Strength Training',
			'rating' => 5,
		],
		[
			'quote' => 'From my first session, I felt supported. Technique cues and weekly check-ins made a huge difference in my progress.',
			'name' => 'Sophia Johnson',
			'role' => 'Member · Fitness Coaching',
			'rating' => 5,
		],
		[
			'quote' => 'IronPace is more than just workouts. The functional training focus helped me feel stronger in everyday life.',
			'name' => 'Daniel Carter',
			'role' => 'Member · Functional Training',
			'rating' => 5,
		],
		[
			'quote' => 'Great trainers, great equipment, and a plan that keeps me accountable. Highly recommend for anyone chasing real progress.',
			'name' => 'Olivia Martin',
			'role' => 'Member · Fitness & Conditioning',
			'rating' => 5,
		],
	],
];

$hasReviews = false;
foreach ($layouts as &$layout) {
	foreach ($layout['columns'] as &$column) {
		// drop empty block columns
		$column['blocks'] = array_values(array_filter($column['blocks'] ?? [], static fn ($b) => !empty($b)));
		foreach ($column['blocks'] as &$block) {
			if (($block['type'] ?? '') === 'reviews') {
				$block['content'] = $reviewsContent;
				$block['isHidden'] = false;
				$hasReviews = true;
			}
		}
	}
}
unset($layout, $column, $block);

$layouts = array_values(array_filter($layouts, static function ($layout) {
	foreach ($layout['columns'] ?? [] as $column) {
		if (!empty($column['blocks'])) {
			return true;
		}
	}
	return false;
}));

if ($hasReviews === false) {
	$insertAt = count($layouts);
	foreach ($layouts as $index => $layout) {
		foreach ($layout['columns'] ?? [] as $column) {
			foreach ($column['blocks'] ?? [] as $block) {
				if (($block['type'] ?? '') === 'faq') {
					$insertAt = $index;
					break 3;
				}
			}
		}
	}
	array_splice($layouts, $insertAt, 0, [[
		'attrs' => [],
		'columns' => [[
			'blocks' => [[
				'content' => $reviewsContent,
				'id' => 'reviews-block-001',
				'isHidden' => false,
				'type' => 'reviews',
			]],
			'id' => 'reviews-col-001',
			'width' => '1/1',
		]],
		'id' => 'reviews-row-001',
	]]);
}

$json = json_encode($layouts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$updated = preg_replace('/^Layout:\s*\[.*\]\s*$/m', 'Layout: ' . $json, $raw, 1);
file_put_contents($path, $updated);

// Remove Panel draft that can hide Reviews in Preview
$changesDir = dirname(__DIR__) . '/content/1_home/_changes';
if (is_dir($changesDir)) {
	foreach (glob($changesDir . '/*') ?: [] as $file) {
		@unlink($file);
	}
	@rmdir($changesDir);
}

require dirname(__DIR__) . '/kirby/bootstrap.php';
$kirby = new Kirby(['roots' => ['index' => dirname(__DIR__)]]);
$home = $kirby->page('home');
$count = $home->layout()->toLayouts()->count();
$reviews = 0;
foreach ($home->layout()->toLayouts() as $layout) {
	foreach ($layout->columns() as $column) {
		foreach ($column->blocks() as $block) {
			if ($block->type() === 'reviews') {
				$reviews = $block->items()->toStructure()->count();
				echo 'heading=' . $block->heading()->value() . PHP_EOL;
			}
		}
	}
}
echo "layouts={$count} reviewItems={$reviews} changes=" . ($home->version('changes')->exists() ? 'yes' : 'no') . PHP_EOL;
