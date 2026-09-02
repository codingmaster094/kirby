<?php

use Kirby\Cms\Page;
use Kirby\Cms\Site;

Kirby::plugin('my-company/live-preview', [
	'sections' => [
		'live-preview' => [
			'mixins' => [
				'headline',
			],
			'props' => [
				'label' => function (string $label = 'Preview') {
					return $label;
				},
			],
			'computed' => [
				'url' => function () {
					$model = $this->model();

					if ($model instanceof Page || $model instanceof Site) {
						return $model->previewUrl('changes')
							?? $model->previewUrl('latest')
							?? $model->url();
					}

					return null;
				},
				'openUrl' => function () {
					$model = $this->model();

					if ($model instanceof Page || $model instanceof Site) {
						return $model->previewUrl('latest') ?? $model->url();
					}

					return null;
				},
			],
			'toArray' => function () {
				return [
					'label'   => $this->label,
					'url'     => $this->url,
					'openUrl' => $this->openUrl,
				];
			},
		],
	],
]);
