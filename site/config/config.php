<?php

$debugEnv = getenv('KIRBY_DEBUG');
$config = [
	// Local default on; set KIRBY_DEBUG=false (or use host config) in production.
	'debug' => $debugEnv === false || $debugEnv === ''
		? true
		: filter_var($debugEnv, FILTER_VALIDATE_BOOLEAN),
	'email' => [
		'from' => getenv('KIRBY_EMAIL_FROM') ?: 'coach@ironpace.com',
	],
	'panel' => [
		'vue' => [
			'compiler' => true,
		],
	],
	'thumbs' => [
		'driver' => 'gd',
		'quality' => 85,
	],
];

// Required behind Render/Docker reverse proxy so assets don't use :PORT or http://
$url = getenv('KIRBY_URL') ?: getenv('APP_URL') ?: '';
if (is_string($url) && trim($url) !== '') {
	$config['url'] = rtrim(trim($url), '/');
}

return $config;
