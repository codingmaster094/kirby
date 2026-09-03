<?php

require dirname(__DIR__) . '/kirby/bootstrap.php';

$kirby = new Kirby([
	'roots' => [
		'index' => dirname(__DIR__),
	],
]);

$kirby->impersonate('kirby');

function mutateLayout(string $json, array $mutatorsByType): string
{
	$layouts = json_decode($json, true);
	if (!is_array($layouts)) {
		return $json;
	}

	foreach ($layouts as &$layout) {
		foreach ($layout['columns'] as &$column) {
			foreach ($column['blocks'] as &$block) {
				$type = $block['type'] ?? '';
				if (!isset($mutatorsByType[$type])) {
					continue;
				}
				$block['content'] = $mutatorsByType[$type]($block['content'] ?? []);
			}
		}
	}
	unset($layout, $column, $block);

	return json_encode($layouts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

$contactLink = 'page://g4xzcsot7mak6oor';
$programsLink = 'page://hnrhc7ij3xlmqp10';
$updated = [];

$home = page('home');
if ($home) {
	$layout = mutateLayout((string) $home->layout()->value(), [
		'hero' => function ($c) use ($contactLink, $programsLink) {
			$c['eyebrow'] = 'Professional Running Coaching';
			$c['heading'] = 'Run Faster. Train Smarter. Race Stronger.';
			$c['description'] = 'Personalized coaching for runners and athletes who want clear plans, smarter training, and stronger race-day execution.';
			$c['primarytext'] = 'Book a Free Strategy Call';
			$c['primarylink'] = $contactLink;
			$c['secondarytext'] = 'Explore Programs';
			$c['secondarylink'] = $programsLink;
			return $c;
		},
		'benefits' => function ($c) {
			$c['eyebrow'] = 'Social proof';
			$c['heading'] = 'Results that keep athletes moving forward';
			$c['description'] = 'A snapshot of outcomes reported across coached training blocks. Individual results vary by experience, consistency, and goals.';
			$c['items'] = [
				['title' => '89%', 'description' => "Achieved a new PR\n\nAmong athletes who completed a race-focused coaching block"],
				['title' => '650+', 'description' => "Athletes coached\n\nRunners and performance athletes across ability levels"],
				['title' => '150+', 'description' => "Podium finishes\n\nTracked across race seasons with coached athletes"],
			];
			return $c;
		},
		'whyUs' => function ($c) {
			$c['eyebrow'] = 'Why IronPace';
			$c['heading'] = 'Built for runners who want more than generic plans';
			$c['items'] = [
				['number' => '01', 'title' => 'Personalized training', 'description' => 'Weekly plans shaped around your goals, schedule, and current fitness—not a one-size template.'],
				['number' => '02', 'title' => 'Data-driven coaching', 'description' => 'Pacing, volume, and recovery decisions guided by how you actually respond to training.'],
				['number' => '03', 'title' => 'Strength & conditioning', 'description' => 'Runner-focused strength that supports durability, power, and better form under fatigue.'],
				['number' => '04', 'title' => 'Race strategy', 'description' => 'Clear pacing, fueling, and race-week execution so you show up prepared—not guessing.'],
				['number' => '05', 'title' => 'Injury-resilient training', 'description' => 'Progression and recovery built in so you can stay consistent across the full season.'],
				['number' => '06', 'title' => 'Nutrition guidance', 'description' => 'Practical fueling support that fits training load and race demands without extremes.'],
				['number' => '07', 'title' => 'Progress tracking', 'description' => 'Regular check-ins and adjustments so every block has a purpose and a next step.'],
				['number' => '08', 'title' => 'Coach accountability', 'description' => 'Direct feedback and structure that keeps you honest when motivation dips.'],
			];
			return $c;
		},
		'coach' => function ($c) use ($contactLink) {
			$c['eyebrow'] = 'Meet the Coach';
			$c['heading'] = "Experience-led coaching\nfor serious runners";
			$c['description'] = 'Coach Adrian Pratama built IronPace to make high-quality running coaching personal and practical—plans you can follow, feedback you can use, and a method designed for race performance.';
			$c['highlights'] = [
				['text' => 'Years of coaching athletes across 5K to marathon distances'],
				['text' => 'Training methodology balancing run load, strength, and recovery'],
				['text' => 'Focus on sustainable progress and race-day confidence'],
			];
			$c['primarytext'] = 'Book a Free Strategy Call';
			$c['primarylink'] = $contactLink;
			return $c;
		},
		'method_in_action' => function ($c) {
			$c['eyebrow'] = 'How coaching works';
			$c['heading'] = 'See the method in action';
			$c['description'] = 'Training quality, strength support, and consistent execution—watch how the coaching process comes together.';
			$captions = [
				'Run sessions with purpose',
				'Strength that supports running',
				'Recovery and mobility work',
				'Form and pacing focus',
				'Conditioning for race demands',
				'Coach-guided progression',
				'Building race-day readiness',
			];
			$alts = [
				'Athlete completing a structured run session',
				'Strength training for runners',
				'Mobility and recovery work',
				'Form and pacing coaching',
				'Conditioning work for race prep',
				'Progressive training guidance',
				'Race preparation training',
			];
			if (!empty($c['slides']) && is_array($c['slides'])) {
				foreach ($c['slides'] as $i => &$slide) {
					$slide['caption'] = $captions[$i] ?? 'Coaching session';
					$slide['alt'] = $alts[$i] ?? 'IronPace coaching session';
				}
				unset($slide);
			}
			return $c;
		},
		'transformations' => function ($c) use ($contactLink) {
			$c['eyebrow'] = 'Athlete results';
			$c['heading'] = 'Case studies from coached athletes';
			$c['description'] = 'These stories show how coaching supports running performance through strength, consistency, and smarter training—without inventing race times.';
			$existing = $c['items'] ?? [];
			$stories = [
				[
					'title' => 'Stronger training base for race prep',
					'athlete' => 'Recreational runner building toward race season',
					'goal' => 'Improve strength and conditioning that support running load',
					'challenge' => 'Unstructured gym work that did not transfer to better running consistency',
					'approach' => 'Runner-focused strength, progressive loading, and recovery guidance alongside training',
					'result' => 'More consistent training weeks and a stronger foundation for race preparation',
					'points' => [
						['text' => 'Personalized strength plan'],
						['text' => 'Technique and progression'],
						['text' => 'Recovery support'],
						['text' => 'Progress check-ins'],
					],
					'ctatext' => 'Book a Free Strategy Call',
					'ctalink' => $contactLink,
					'imagealt' => 'Athlete strength and conditioning progress',
				],
				[
					'title' => 'From inconsistent effort to structured progress',
					'athlete' => 'Athlete returning to structured training',
					'goal' => 'Rebuild strength, endurance support, and training discipline',
					'challenge' => 'Stop-start routines and limited accountability',
					'approach' => 'Goal-based programming with progressive workouts and coach support',
					'result' => 'A clearer weekly rhythm and better carryover into running-focused work',
					'points' => [
						['text' => 'Goal-based training'],
						['text' => 'Strength & conditioning'],
						['text' => 'Recovery guidance'],
						['text' => 'Coach accountability'],
					],
					'ctatext' => 'Book a Free Strategy Call',
					'ctalink' => $contactLink,
					'imagealt' => 'Athlete building consistent training habits',
				],
				[
					'title' => 'Mobility and durability for active athletes',
					'athlete' => 'Active athlete improving durability',
					'goal' => 'Train more consistently with better mobility and strength balance',
					'challenge' => 'Stiffness and uneven fitness limiting training quality',
					'approach' => 'Personalized coaching combining strength, mobility, and sustainable habits',
					'result' => 'Improved training quality and confidence to stay active week to week',
					'points' => [
						['text' => 'Personalized coaching'],
						['text' => 'Mobility & strength balance'],
						['text' => 'Nutrition guidance'],
						['text' => 'Long-term consistency'],
					],
					'ctatext' => 'Book a Free Strategy Call',
					'ctalink' => $contactLink,
					'imagealt' => 'Athlete improving mobility and durability',
				],
			];
			$c['items'] = [];
			foreach ($stories as $i => $story) {
				$story['image'] = $existing[$i]['image'] ?? [];
				$story['description'] = '';
				$c['items'][] = $story;
			}
			return $c;
		},
		'faq' => function ($c) {
			$c['eyebrow'] = 'FAQ';
			$c['heading'] = 'Questions runners ask before starting';
			$c['description'] = 'Clear answers about coaching fit, plans, and what happens after you book a call.';
			$c['items'] = [
				['question' => 'Who is the coaching for?', 'answer' => 'Runners and athletes who want structured guidance—whether you are building toward your first race or chasing a stronger performance season.'],
				['question' => 'Do I need to be an experienced runner?', 'answer' => 'No. Plans are personalized for beginners through competitive athletes. We start from your current fitness and goals.'],
				['question' => 'How does personalized coaching work?', 'answer' => 'We review your goals, schedule, and training history, then build a plan you can follow. Check-ins keep the plan aligned as you progress.'],
				['question' => 'How often will I receive my training plan?', 'answer' => 'Most athletes work from a weekly plan with ongoing adjustments. Exact cadence depends on your coaching track and race timeline.'],
				['question' => 'Can coaching help with race preparation?', 'answer' => 'Yes. Race-prep blocks cover peaking, pacing strategy, fueling guidance, and taper planning for your event.'],
				['question' => 'Is strength training included?', 'answer' => 'Yes where it supports your running. Strength and conditioning are programmed to improve durability and performance—not as a generic gym routine.'],
				['question' => 'How is progress tracked?', 'answer' => 'Through regular check-ins, workout feedback, and plan adjustments so training stays purposeful.'],
				['question' => 'What happens after I book a call?', 'answer' => 'We review fit, clarify goals, and recommend a coaching path with clear next steps—no pressure to commit on the call.'],
			];
			return $c;
		},
		'cta' => function ($c) use ($contactLink, $programsLink) {
			$c['eyebrow'] = 'Ready to train with purpose?';
			$c['heading'] = 'Book a free strategy call';
			$c['description'] = 'Tell us your race goals and schedule. We’ll map a clear coaching path so you can run faster, train smarter, and race stronger.';
			$c['primarytext'] = 'Book a Free Strategy Call';
			$c['primarylink'] = $contactLink;
			$c['secondarytext'] = 'Explore Programs';
			$c['secondarylink'] = $programsLink;
			return $c;
		},
	]);

	$home->update(['layout' => $layout]);
	$updated[] = 'home';
}

$programs = page('programs');
if ($programs) {
	$layout = mutateLayout((string) $programs->layout()->value(), [
		'programs' => function ($c) use ($contactLink) {
			$c['eyebrow'] = 'Coaching programs';
			$c['heading'] = 'Choose the support that matches your race goals';
			$c['description'] = 'Every program is built for runners and athletes. Pick a track, then book a call to confirm fit.';
			$c['items'] = [
				[
					'title' => '1:1 Performance Coaching',
					'audience' => 'For runners chasing year-round progress',
					'summary' => 'Fully personalized plans with weekly check-ins for serious runners and athletes.',
					'points' => [
						['text' => 'Custom weekly training plan'],
						['text' => 'Form and pacing feedback'],
						['text' => 'Strength + recovery support'],
						['text' => 'Direct coach messaging'],
					],
					'ctatext' => 'Book a Free Strategy Call',
					'ctalink' => $contactLink,
				],
				[
					'title' => 'Race Prep Program',
					'audience' => 'For 5K to marathon race goals',
					'summary' => 'A focused block to peak for your next key race with clear pacing and fueling support.',
					'points' => [
						['text' => 'Peak-phase programming'],
						['text' => 'Race simulation workouts'],
						['text' => 'Fueling strategy guidance'],
						['text' => 'Taper and race-week plan'],
					],
					'ctatext' => 'Book a Free Strategy Call',
					'ctalink' => $contactLink,
				],
				[
					'title' => 'Foundation Reset',
					'audience' => 'For beginners and returning runners',
					'summary' => 'Build consistency, mobility, and base fitness without burnout.',
					'points' => [
						['text' => 'Beginner-friendly structure'],
						['text' => 'Injury-prevention focus'],
						['text' => 'Habit and accountability system'],
						['text' => 'Sustainable weekly volume'],
					],
					'ctatext' => 'Book a Free Strategy Call',
					'ctalink' => $contactLink,
				],
			];
			return $c;
		},
	]);
	$programs->update(['layout' => $layout]);
	$updated[] = 'programs';
}

$pricing = page('pricing');
if ($pricing) {
	$layout = mutateLayout((string) $pricing->layout()->value(), [
		'pricing' => function ($c) use ($contactLink) {
			$c['eyebrow'] = 'Pricing';
			$c['heading'] = 'Simple plans for serious coaching';
			$c['description'] = 'Transparent packages for athletes who want clarity, support, and race-ready structure.';
			if (!empty($c['items']) && is_array($c['items'])) {
				foreach ($c['items'] as &$item) {
					$item['ctatext'] = 'Book a Free Strategy Call';
					$item['ctalink'] = $contactLink;
				}
				unset($item);
			}
			return $c;
		},
	]);
	$pricing->update(['layout' => $layout]);
	$updated[] = 'pricing';
}

$contact = page('contact');
if ($contact) {
	$layout = mutateLayout((string) $contact->layout()->value(), [
		'contact' => function ($c) {
			$c['eyebrow'] = 'Book a call';
			$c['heading'] = 'Let’s map your next breakthrough';
			$c['description'] = 'Book a free strategy call and get a clear plan based on your goals, schedule, and current fitness.';
			$c['formheading'] = 'Send a message';
			$c['bookingheading'] = 'Book a Free Strategy Call';
			return $c;
		},
	]);
	$contact->update(['layout' => $layout]);
	$updated[] = 'contact';
}

site()->update([
	'headerLinks' => [
		['text' => 'Programs', 'link' => $programsLink],
		['text' => 'How It Works', 'link' => '/#how-it-works'],
		['text' => 'Why Us', 'link' => '/#why-us'],
		['text' => 'Coach', 'link' => '/#coach'],
		['text' => 'Results', 'link' => '/#results'],
		['text' => 'FAQ', 'link' => '/#faq'],
	],
	'headerCtaText' => 'Book a Free Strategy Call',
	'headerCtaLink' => $contactLink,
	'footerHeading' => 'Train smarter. Run stronger.',
	'footerDescription' => 'Professional running coaching and athlete performance training—personalized plans, race strategy, and accountable coaching.',
	'instagram' => '',
	'facebook' => '',
	'Twitter' => '',
	'Linkedin' => '',
	'Github' => '',
]);
$updated[] = 'site';

echo 'Updated: ' . implode(', ', $updated) . PHP_EOL;
