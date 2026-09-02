<?php

/**
 * Host-specific config for the Render deployment.
 * Loaded automatically when the request host is kirby-2.onrender.com
 */
return [
	'debug' => false,
	'url'   => 'https://kirby-2.onrender.com',
	'panel' => [
		// First deploy has no accounts in the image; allow creating the admin once.
		// After you create the account this stays safe if an account folder exists
		// (see site/config/config.php). You can also set KIRBY_PANEL_INSTALL=false.
		'install' => true,
	],
];
