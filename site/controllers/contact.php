<?php

return function ($kirby, $page) {
	$alert = null;
	$errors = [];
	$data = [
		'name'    => '',
		'email'   => '',
		'phone'   => '',
		'goal'    => '',
		'message' => '',
	];
	$success = get('sent') === '1';

	if ($kirby->request()->is('POST') === false || get('contact-form') !== '1') {
		return compact('alert', 'success', 'errors', 'data');
	}

	// Honeypot
	if (trim((string) get('website')) !== '') {
		go($page->url() . '?sent=1');
	}

	if (csrf(get('csrf')) === false) {
		$alert = 'Your session expired. Please try again.';
		return compact('alert', 'success', 'errors', 'data');
	}

	$data = [
		'name'    => trim((string) get('name')),
		'email'   => trim((string) get('email')),
		'phone'   => trim((string) get('phone')),
		'goal'    => trim((string) get('goal')),
		'message' => trim((string) get('message')),
	];

	$nameLen = mb_strlen($data['name']);
	if ($data['name'] === '') {
		$errors['name'] = 'Please enter your name.';
	} elseif ($nameLen < 2) {
		$errors['name'] = 'Name must be at least 2 characters.';
	} elseif ($nameLen > 80) {
		$errors['name'] = 'Name must be 80 characters or fewer.';
	} elseif (!preg_match('/^[\p{L}\p{M}\s\'\-\.]+$/u', $data['name'])) {
		$errors['name'] = 'Name can only include letters, spaces, hyphens, and apostrophes.';
	}

	if ($data['email'] === '') {
		$errors['email'] = 'Please enter your email address.';
	} elseif (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
		$errors['email'] = 'Please enter a valid email address.';
	} elseif (mb_strlen($data['email']) > 190) {
		$errors['email'] = 'Email must be 190 characters or fewer.';
	}

	if ($data['phone'] !== '') {
		$digits = preg_replace('/\D+/', '', $data['phone']);
		if (strlen((string) $digits) < 7 || strlen((string) $digits) > 15) {
			$errors['phone'] = 'Enter a valid phone number (7–15 digits).';
		} elseif (!preg_match('/^[+\d][\d\s().\-]{6,24}$/', $data['phone'])) {
			$errors['phone'] = 'Phone number contains invalid characters.';
		}
	}

	$messageLen = mb_strlen($data['message']);
	if ($data['message'] === '') {
		$errors['message'] = 'Please enter a short message.';
	} elseif ($messageLen < 10) {
		$errors['message'] = 'Message must be at least 10 characters.';
	} elseif ($messageLen > 2000) {
		$errors['message'] = 'Message must be 2000 characters or fewer.';
	}

	if ($data['goal'] !== '' && mb_strlen($data['goal']) > 120) {
		$errors['goal'] = 'Please choose a valid goal.';
	}

	if ($errors !== []) {
		$alert = 'Please fix the highlighted fields and try again.';
		return compact('alert', 'success', 'errors', 'data');
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

	return compact('alert', 'success', 'errors', 'data');
};
