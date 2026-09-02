<?php

$debugEnv = getenv('KIRBY_DEBUG');

$accountsDir = dirname(__DIR__) . '/accounts';
$hasAccounts = false;

if (is_dir($accountsDir)) {
	foreach (scandir($accountsDir) as $item) {
		if ($item === '.' || $item === '..' || $item === 'index.html') {
			continue;
		}

		if (is_dir($accountsDir . '/' . $item)) {
			$hasAccounts = true;
			break;
		}
	}
}

// Allow first-time Panel setup on Render/Docker when no account exists yet.
$panelInstallEnv = getenv('KIRBY_PANEL_INSTALL');
if ($panelInstallEnv === false || $panelInstallEnv === '') {
	$panelInstall = $hasAccounts === false;
} else {
	$panelInstall = filter_var($panelInstallEnv, FILTER_VALIDATE_BOOLEAN);
}

$config = [
	// Local default on; set KIRBY_DEBUG=false (or use host config) in production.
	'debug' => $debugEnv === false || $debugEnv === ''
		? true
		: filter_var($debugEnv, FILTER_VALIDATE_BOOLEAN),
	'email' => [
		'from' => getenv('KIRBY_EMAIL_FROM') ?: 'coach@ironpace.com',
	],
	'panel' => [
		'install' => $panelInstall,
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
