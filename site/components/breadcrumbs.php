<?php

declare(strict_types=1);

/** @var array<int, array{label: string, href: ?string}>|null $breadcrumbs */
if (empty($breadcrumbs) || !is_array($breadcrumbs)) {
    return;
}
?>
<nav class="breadcrumbs" aria-label="Breadcrumb">
    <a class="bc-home" href="<?= h(asset('index.php')) ?>" aria-label="<?= h(__('nav.home')) ?>">⌂</a>
    <?php foreach ($breadcrumbs as $i => $crumb) {
        $label = $crumb['label'] ?? '';
        $href = $crumb['href'] ?? null;
        ?>
        <span class="bc-sep" aria-hidden="true"><img src="<?= h(asset('assets/svg/chevron-right.svg')) ?>" width="12" height="12" alt=""></span>
        <?php if ($href !== null && $href !== '') { ?>
            <a href="<?= h($href) ?>"><?= h($label) ?></a>
        <?php } else { ?>
            <span class="bc-current"><?= h($label) ?></span>
        <?php }
    } ?>
</nav>
