<?php

require dirname(__DIR__) . '/kirby/bootstrap.php';

$kirby = new Kirby([
	'roots' => [
		'index' => dirname(__DIR__),
	],
]);

$kirby->impersonate('kirby');

$home = page('home');
if (!$home) {
	fwrite(STDERR, "Home page not found\n");
	exit(1);
}

$layouts = $home->layout()->toLayouts()->toArray();

foreach ($layouts as $layout) {
	foreach ($layout['columns'] ?? [] as $column) {
		foreach ($column['blocks'] ?? [] as $block) {
			if (($block['type'] ?? '') === 'reviews') {
				echo "Reviews block already present\n";
				exit(0);
			}
		}
	}
}

$reviewsRow = [
	'attrs' => [],
	'columns' => [[
		'blocks' => [[
			'content' => [
				'eyebrow' => 'Athlete reviews',
				'heading' => 'Coached athletes. Real results.',
				'description' => 'Runners and performance athletes share how structured coaching helped them race stronger and stay consistent.',
				'items' => [
					[
						'quote' => 'The weekly plan finally made sense of my training. I hit a half-marathon PR without burning out mid-block.',
						'name' => 'Priya Shah',
						'role' => 'Half marathon · New PR',
						'rating' => 5,
					],
					[
						'quote' => 'Clear pacing, strength work, and honest check-ins. I showed up to race day knowing exactly how to execute.',
						'name' => 'Marcus Lee',
						'role' => '10K · Sub-40 goal',
						'rating' => 5,
					],
					[
						'quote' => 'Coming back from injury felt overwhelming until we rebuilt my base. Consistent, patient coaching made the difference.',
						'name' => 'Elena Ruiz',
						'role' => 'Return-to-run athlete',
						'rating' => 5,
					],
				],
			],
			'id' => 'reviews-block-001',
			'isHidden' => false,
			'type' => 'reviews',
		]],
		'id' => 'reviews-col-001',
		'width' => '1/1',
	]],
	'id' => 'reviews-row-001',
];

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

array_splice($layouts, $insertAt, 0, [$reviewsRow]);

$home->update([
	'layout' => $layouts,
]);

echo "Inserted reviews block before FAQ (index {$insertAt})\n";
