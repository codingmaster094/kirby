<?php

use Kirby\Cms\App;
use Kirby\Cms\File;
use Kirby\Cms\Page;
use Kirby\Cms\Site;
use Kirby\Panel\Ui\Item\FileItem;

class MediaHub
{
	public static function payload(App $kirby): array
	{
		$folders = [];
		$files = [];
		$tags = [];
		$contentBlob = '';

		$addFolder = function (
			string $id,
			string $label,
			string $upload,
			int $count
		) use (&$folders) {
			$folders[] = [
				'id'     => $id,
				'label'  => $label,
				'count'  => $count,
				'upload' => $upload,
			];
		};

		$addFiles = function (
			$parent,
			string $folderId
		) use (&$files, &$tags, &$contentBlob) {
			$contentBlob .= json_encode($parent->content()->toArray());

			foreach ($parent->files() as $file) {
				/** @var File $file */
				$item = (new FileItem(
					file: $file,
					layout: 'cards',
					info: '{{ file.niceSize }}'
				))->props();

				$fileTags = array_filter($file->tags()->split(','));
				foreach ($fileTags as $tag) {
					$tags[$tag] = ($tags[$tag] ?? 0) + 1;
				}

				$uuid = $file->uuid()?->toString() ?? '';
				$api = $parent instanceof Site
					? 'site/files/' . $file->filename()
					: 'pages/' . str_replace('/', '+', $parent->id()) . '/files/' . $file->filename();

				$files[] = [
					...$item,
					'filename'  => $file->filename(),
					'extension' => strtoupper($file->extension()),
					'size'      => $file->niceSize(),
					'type'      => $file->type() ?? 'document',
					'folder'    => $folderId,
					'tags'      => array_values($fileTags),
					'uuid'      => $uuid,
					'bytes'     => $file->size(),
					'api'       => $api,
					'canDelete' => $file->permissions()->can('delete'),
				];
			}
		};

		// Site files
		$addFolder('site', 'Site', 'site/files', $kirby->site()->files()->count());
		$addFiles($kirby->site(), 'site');

		$blogPage = null;
		$blogPosts = [];

		foreach ($kirby->site()->index(true) as $page) {
			/** @var Page $page */
			$template = $page->intendedTemplate()->name();

			// Blog page + all blog posts share one "Blog Posts" folder
			if ($template === 'blog') {
				$blogPage = $page;
				continue;
			}

			if ($template === 'blog-post') {
				$blogPosts[] = $page;
				continue;
			}

			$upload = 'pages/' . str_replace('/', '+', $page->id()) . '/files';
			$addFolder(
				'page:' . $page->id(),
				$page->title()->or($page->slug())->value(),
				$upload,
				$page->files()->count()
			);
			$addFiles($page, 'page:' . $page->id());
		}

		$blogPostCount = 0;
		$blogUpload = 'site/files';

		if ($blogPage !== null) {
			$blogPostCount += $blogPage->files()->count();
			$blogUpload = 'pages/' . str_replace('/', '+', $blogPage->id()) . '/files';
		}

		foreach ($blogPosts as $post) {
			$blogPostCount += $post->files()->count();
		}

		$addFolder('blog-posts', 'Blog Posts', $blogUpload, $blogPostCount);

		if ($blogPage !== null) {
			$addFiles($blogPage, 'blog-posts');
		}

		foreach ($blogPosts as $post) {
			$addFiles($post, 'blog-posts');
		}

		$used = 0;
		foreach ($files as &$file) {
			$needle = $file['filename'];
			$uuid = $file['uuid'];
			$file['used'] = str_contains($contentBlob, $needle)
				|| ($uuid !== '' && str_contains($contentBlob, $uuid));
			if ($file['used']) {
				$used++;
			}
		}
		unset($file);

		$names = array_count_values(array_map(
			fn ($file) => strtolower($file['filename']),
			$files
		));

		foreach ($files as &$file) {
			$file['duplicate'] = ($names[strtolower($file['filename'])] ?? 0) > 1;
		}
		unset($file);

		$tagList = [];
		foreach ($tags as $name => $count) {
			$tagList[] = ['name' => $name, 'count' => $count];
		}
		usort($tagList, fn ($a, $b) => strcasecmp($a['name'], $b['name']));

		$typeCount = fn (string $type) => count(array_filter($files, fn ($file) => $file['type'] === $type));

		return [
			'folders' => $folders,
			'files'   => $files,
			'tags'    => $tagList,
			'stats'   => [
				'total'     => count($files),
				'unused'    => count($files) - $used,
				'folders'   => count($folders),
				'images'    => $typeCount('image'),
				'videos'    => $typeCount('video'),
				'documents' => $typeCount('document'),
				'audio'     => $typeCount('audio'),
			],
		];
	}
}
