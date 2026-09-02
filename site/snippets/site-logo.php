<?php

/**
 * Site logo snippet
 *
 * @var string $variant header|footer
 */

$variant = $variant ?? 'header';
$site = site();
$class = $class ?? '';

if ($variant === 'footer') {
    $logo = $site->footerLogo()->toFile() ?? $site->logo()->toFile();
    $fallback = url('assets/images/logo-footer.svg');
    $defaultClass = 'site-logo site-logo--footer h-10 w-auto max-w-[190px] object-contain';
} else {
    $logo = $site->logo()->toFile();
    $fallback = url('assets/images/logo-header.svg');
    $defaultClass = 'site-logo site-logo--header h-10 w-auto max-w-[190px] object-contain';
}

$logoClass = trim($defaultClass . ' ' . $class);
$alt = esc($site->title()->value());

if ($logo): ?>
    <img
        src="<?= esc($logo->url()) ?>"
        alt="<?= $alt ?>"
        class="<?= esc($logoClass) ?>"
        width="190"
        height="40"
    >
<?php else: ?>
    <img
        src="<?= esc($fallback) ?>"
        alt="<?= $alt ?>"
        class="<?= esc($logoClass) ?>"
        width="190"
        height="40"
    >
<?php endif ?>
