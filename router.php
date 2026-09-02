<?php

/**
 * Windows-safe router for PHP's built-in server.
 * Start with: php -S localhost:8000 router.php
 *
 * Kirby's stock router compares realpath() with DOCUMENT_ROOT using substr().
 * On Windows those paths often use different separators, so static files under
 * /media/panel/... incorrectly fall through to index.php and return HTML.
 */

$uri = parse_url('https://getkirby.com/' . ltrim($_SERVER['REQUEST_URI'] ?? '/', '/'), PHP_URL_PATH) ?? '/';
$uri = urldecode($uri);

$root = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__;
$path = $root . '/' . ltrim($uri, '/');

if ($uri !== '/' && is_file($path)) {
	$realFile = realpath($path);
	$realRoot = realpath($root);

	if ($realFile !== false && $realRoot !== false) {
		$normalize = static function (string $value): string {
			return strtolower(str_replace('\\', '/', $value));
		};

		$file = $normalize($realFile);
		$base = rtrim($normalize($realRoot), '/');

		if (str_starts_with($file, $base . '/') || $file === $base) {
			return false;
		}
	}
}

$_SERVER['SCRIPT_NAME'] = '/index.php';

require $root . '/index.php';
