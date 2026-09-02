<?php

require dirname(__DIR__) . '/kirby/bootstrap.php';

$kirby = new Kirby([
	'roots' => [
		'index' => dirname(__DIR__),
	],
]);
$kirby->impersonate('kirby');

function layoutId(): string
{
	return Kirby\Uuid\Uuid::generate();
}

function layoutSection(string $type, array $content): array
{
	return [
		'attrs' => [],
		'columns' => [[
			'blocks' => [[
				'content' => $content,
				'id' => layoutId(),
				'isHidden' => false,
				'type' => $type,
			]],
			'id' => layoutId(),
			'width' => '1/1',
		]],
		'id' => layoutId(),
	];
}

function structureRows($field): array
{
	$rows = [];
	foreach ($field->toStructure() as $row) {
		$rows[] = $row->content()->toArray();
	}
	return $rows;
}

function pageHasBlockType($page, string $type): bool
{
	if ($page->layout()->isEmpty()) {
		return false;
	}

	foreach ($page->layout()->toLayouts() as $layout) {
		foreach ($layout->columns() as $column) {
			foreach ($column->blocks() as $block) {
				if ($block->type() === $type) {
					return true;
				}
			}
		}
	}

	return false;
}

function appendLayout($page, array $section): void
{
	$layouts = $page->layout()->isNotEmpty()
		? json_decode($page->layout()->value(), true)
		: [];

	if (!is_array($layouts)) {
		$layouts = [];
	}

	$layouts[] = $section;
	$page->update(['layout' => json_encode($layouts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
}

$updated = [];

$programs = page('programs');
if ($programs && !pageHasBlockType($programs, 'programs')) {
	appendLayout($programs, layoutSection('programs', [
		'eyebrow' => $programs->eyebrow()->value(),
		'heading' => $programs->heading()->or($programs->title())->value(),
		'description' => $programs->description()->value(),
		'items' => structureRows($programs->programs()),
	]));
	$updated[] = 'programs';
}

$pricing = page('pricing');
if ($pricing && !pageHasBlockType($pricing, 'pricing')) {
	appendLayout($pricing, layoutSection('pricing', [
		'eyebrow' => $pricing->eyebrow()->value(),
		'heading' => $pricing->heading()->or($pricing->title())->value(),
		'description' => $pricing->description()->value(),
		'featuredlabel' => 'Most popular',
		'items' => structureRows($pricing->plans()),
	]));
	$updated[] = 'pricing';
}

$team = page('trainers');
if ($team && !pageHasBlockType($team, 'team_section')) {
	$members = json_decode((string) $team->members()->value(), true);
	appendLayout($team, layoutSection('team_section', [
		'eyebrow' => 'Our team',
		'heading' => $team->heading()->or($team->title())->value(),
		'description' => $team->description()->value(),
		'members' => is_array($members) ? $members : [],
	]));
	$updated[] = 'trainers';
}

$contact = page('contact');
if ($contact && !pageHasBlockType($contact, 'contact')) {
	appendLayout($contact, layoutSection('contact', [
		'eyebrow' => $contact->eyebrow()->value(),
		'heading' => $contact->heading()->or($contact->title())->value(),
		'description' => $contact->description()->value(),
		'email' => $contact->email()->value(),
		'phone' => $contact->phone()->value(),
		'points' => structureRows($contact->points()),
		'formheading' => $contact->formHeading()->value(),
		'formsuccess' => $contact->formSuccess()->value(),
		'formgoals' => structureRows($contact->formGoals()),
		'bookingheading' => $contact->bookingHeading()->value(),
		'bookingdescription' => $contact->bookingDescription()->value(),
		'bookingurl' => $contact->bookingUrl()->value(),
	]));
	$updated[] = 'contact';
}

$blog = page('blog');
if ($blog && !pageHasBlockType($blog, 'blog_posts')) {
	appendLayout($blog, layoutSection('blog_posts', [
		'eyebrow' => 'Journal',
		'heading' => $blog->heading()->or($blog->title())->value(),
		'description' => $blog->description()->value(),
		'source' => [],
	]));
	$updated[] = 'blog';
}

$workout = page('workout-guide');
if ($workout && !pageHasBlockType($workout, 'workout_guide')) {
	appendLayout($workout, layoutSection('workout_guide', [
		'eyebrow' => $workout->eyebrow()->value(),
		'heading' => $workout->heading()->or($workout->title())->value(),
		'description' => $workout->description()->value(),
		'searchhelp' => $workout->searchHelp()->value(),
		'concerns' => structureRows($workout->concerns()),
	]));
	$updated[] = 'workout-guide';
}

foreach (['privacy-policy', 'terms-and-conditions'] as $slug) {
	$legal = page($slug);
	if ($legal && !pageHasBlockType($legal, 'legal_content')) {
		appendLayout($legal, layoutSection('legal_content', [
			'eyebrow' => $legal->eyebrow()->value(),
			'heading' => $legal->heading()->or($legal->title())->value(),
			'text' => $legal->text()->value(),
		]));
		$updated[] = $slug;
	}
}

$error = $kirby->site()->errorPage();
if ($error && !pageHasBlockType($error, 'error_page')) {
	appendLayout($error, layoutSection('error_page', [
		'eyebrow' => $error->eyebrow()->value(),
		'heading' => $error->heading()->or($error->title())->value(),
		'text' => $error->text()->value(),
		'primarytext' => $error->primaryText()->value(),
		'primarylink' => $error->primaryLink()->value(),
		'secondarytext' => $error->secondaryText()->value(),
		'secondarylink' => $error->secondaryLink()->value(),
	]));
	$updated[] = 'error';
}

$blogPage = page('blog');
if ($blogPage) {
	foreach ($blogPage->children() as $post) {
		if (pageHasBlockType($post, 'article')) {
			continue;
		}

		$cover = [];
		foreach ($post->cover()->toFiles() as $file) {
			$cover[] = $file->id();
		}

		appendLayout($post, layoutSection('article', [
			'heading' => $post->title()->value(),
			'description' => $post->description()->value(),
			'cover' => $cover,
			'body' => $post->content()->get('content')->value(),
		]));
		$updated[] = $post->id();
	}
}

echo 'Updated: ' . implode(', ', $updated) . PHP_EOL;
