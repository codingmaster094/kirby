<?php

use Kirby\Cms\App;
use Kirby\Filesystem\Dir;
use Kirby\Filesystem\F;

App::plugin(
	name: 'local/media-fallback',
	extends: [
		'hooks' => [
			'file.create:after' => function ($file) {
				localPublishThumbs($file);
			},
			'file.replace:after' => function ($file) {
				localPublishThumbs($file);
			},
		],
		'components' => [
			'thumb' => function (App $kirby, string $src, string $dst, array $options) {
				try {
					if (extension_loaded('gd') === true || extension_loaded('imagick') === true) {
						return $kirby->nativeComponent('thumb')($kirby, $src, $dst, $options);
					}
				} catch (Throwable $e) {
					// fall through
				}

				Dir::make(dirname($dst));
				F::copy($src, $dst, true);

				return $dst;
			},
		],
	],
	info: [
		'description' => 'Local thumbnail fallback when GD/Imagick is unavailable',
		'authors' => [
			['name' => 'Local'],
		],
	],
	version: '1.0.0',
	license: 'MIT',
);

function localPublishThumbs($file): void
{
	$file->publish();

	if ($file->isResizable() !== true) {
		return;
	}

	$kirby = $file->kirby();

	foreach ([38, 76, 96, 192, 352, 864, 1408] as $width) {
		try {
			$version = $file->thumb(['width' => $width]);
			$dst = $version->root();

			if (is_file($dst) === true) {
				continue;
			}

			$kirby->thumb($file->root(), $dst, ['width' => $width]);
		} catch (Throwable $e) {
			continue;
		}
	}
}
