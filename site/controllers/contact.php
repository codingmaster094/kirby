<?php

return function ($kirby, $page) {
	$alert = null;
	$success = get('sent') === '1';

	if ($kirby->request()->is('POST') === false || get('contact-form') !== '1') {
		return compact('alert', 'success');
	}

	// Honeypot
	if (trim((string) get('website')) !== '') {
		go($page->url() . '?sent=1');
	}

	if (csrf(get('csrf')) === false) {
		$alert = 'Your session expired. Please try again.';
		return compact('alert', 'success');
	}

	$data = [
		'name'    => trim((string) get('name')),
		'email'   => trim((string) get('email')),
		'phone'   => trim((string) get('phone')),
		'goal'    => trim((string) get('goal')),
		'message' => trim((string) get('message')),
	];

	if ($data['name'] === '' || $data['email'] === '' || $data['message'] === '') {
		$alert = 'Please fill in your name, email, and message.';
		return compact('alert', 'success');
	}

	if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
		$alert = 'Please enter a valid email address.';
		return compact('alert', 'success');
	}

	try {
		$kirby->impersonate('kirby', function () use ($page, $data, $kirby) {
			$inquiry = $page->createChild([
				'slug'     => 'inquiry-' . date('Ymd-His') . '-' . bin2hex(random_bytes(2)),
				'template' => 'inquiry',
				'content'  => [
					'title'    => $data['name'],
					'received' => date('Y-m-d H:i:s'),
					'email'    => $data['email'],
					'phone'    => $data['phone'],
					'goal'     => $data['goal'],
					'message'  => $data['message'],
				],
			]);

			$inquiry->changeStatus('unlisted');

			$to = trim((string) $page->email()->value());

			if ($page->layout()->isNotEmpty()) {
				foreach ($page->layout()->toLayouts() as $layout) {
					foreach ($layout->columns() as $column) {
						foreach ($column->blocks() as $block) {
							if ($block->type() === 'contact') {
								$fromBlock = trim((string) $block->email()->value());
								if ($fromBlock !== '') {
									$to = $fromBlock;
								}
							}
						}
					}
				}
			}

			if ($to !== '') {
				try {
					$kirby->email([
						'from'    => $to,
						'to'      => $to,
						'replyTo' => $data['email'],
						'subject' => 'New coaching inquiry from ' . $data['name'],
						'body'    => implode("\n", [
							'Name: ' . $data['name'],
							'Email: ' . $data['email'],
							'Phone: ' . ($data['phone'] !== '' ? $data['phone'] : '—'),
							'Goal: ' . ($data['goal'] !== '' ? $data['goal'] : '—'),
							'',
							$data['message'],
						]),
					]);
				} catch (Throwable $emailError) {
					// Inquiry is already saved in Panel if mail is not configured.
				}
			}
		});

		go($page->url() . '?sent=1#contact-form');
	} catch (Throwable $e) {
		$alert = 'Something went wrong. Please email us directly or try again.';
	}

	return compact('alert', 'success');
};
