<?php
$site = site();
$page = page();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($page->title()->value()) ?> | <?= esc($site->title()->value()) ?></title>
    <meta name="description" content="<?= esc($page->description()->or($site->footerDescription())->value()) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <?= css('assets/css/output.css') ?>
    <?php
    $favicon = $site->Favlogo()->toFile();
    $faviconUrl = $favicon ? $favicon->url() : url('assets/images/favicon.svg');
    $faviconType = $favicon ? $favicon->mime() : 'image/svg+xml';

    // Preload hero video so refresh does not flash a still image first.
    if ($page && $page->layout()->isNotEmpty()) {
        foreach ($page->layout()->toLayouts() as $layout) {
            foreach ($layout->columns() as $column) {
                foreach ($column->blocks() as $block) {
                    if ($block->isHidden() || $block->type() !== 'hero') {
                        continue;
                    }

                    $heroVideo = $block->video()->toFiles()->first();
                    if ($heroVideo) {
                        echo '<link rel="preload" as="video" href="' . esc($heroVideo->url()) . '" type="' . esc($heroVideo->mime()) . '">' . PHP_EOL;
                    }
                    break 3;
                }
            }
        }
    }
    ?>
    <link rel="icon" type="<?= esc($faviconType) ?>" href="<?= esc($faviconUrl) ?>">
</head>
<body class="flex min-h-screen flex-col bg-white text-ink">
<header class="sticky top-0 z-40 border-b border-black/10 bg-white/95 backdrop-blur-xl">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-3.5 sm:px-6 lg:px-10">
        <a href="<?= $site->url() ?>" class="flex shrink-0 items-center">
            <?php snippet('site-logo', ['variant' => 'header']) ?>
        </a>

        <nav class="hidden items-center gap-1 text-sm font-semibold text-slate-600 md:flex">
            <?php foreach ($site->children()->listed()->filterBy('intendedTemplate', '!=', 'legal') as $item): ?>
                <a
                    href="<?= $item->url() ?>"
                    class="rounded-full px-4 py-2 transition hover:bg-slate-800 hover:text-white <?= e($item->isOpen(), 'bg-ink text-white hover:bg-black hover:text-black') ?>"
                    <?= e($item->isOpen(), 'aria-current="page"') ?>
                >
                    <?= esc($item->title()->value()) ?>
                </a>
            <?php endforeach ?>
        </nav>

        <a
            href="<?= ($site->find('contact') ?? $site->homePage())->url() ?>"  
            class="btn-lime hidden rounded-full px-5 py-2.5 text-sm font-bold md:inline-flex"
        >
            Book a Call
        </a>

        <button
            type="button"
            id="menu-open"
            class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-ink md:hidden"
            aria-label="Open menu"
            aria-controls="mobile-sidebar"
            aria-expanded="false"
        >
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/>
            </svg>
        </button>
    </div>
</header>

<div id="sidebar-overlay" class="fixed inset-0 z-40 hidden bg-black/50 md:hidden"></div>

<aside
    id="mobile-sidebar"
    class="fixed inset-y-0 right-0 z-50 flex w-72 max-w-[85%] translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 md:hidden"
    aria-hidden="true"
>
    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
        <?php snippet('site-logo', ['variant' => 'header', 'class' => 'h-8']) ?>
        <button
            type="button"
            id="menu-close"
            class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200"
            aria-label="Close menu"
        >
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/>
            </svg>
        </button>
    </div>
    <nav class="flex flex-col gap-1 p-4 text-sm font-semibold">
        <?php foreach ($site->children()->listed()->filterBy('intendedTemplate', '!=', 'legal') as $item): ?>
            <a
                href="<?= $item->url() ?>"
                class="rounded-xl px-4 py-3 text-slate-600 transition hover:bg-slate-100 hover:text-ink <?= e($item->isOpen(), 'bg-ink text-white hover:bg-black hover:text-white') ?>"
                <?= e($item->isOpen(), 'aria-current="page"') ?>
            >
                <?= esc($item->title()->value()) ?>
            </a>
        <?php endforeach ?>
        <a href="<?= ($site->find('contact') ?? $site->homePage())->url() ?>" class="btn-lime mt-2 rounded-xl px-4 py-3 text-center text-sm font-bold">
            Book a Call
        </a>
    </nav>
</aside>
