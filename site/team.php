<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/connect.php';

$pageTitle = __('team.title');
require COMPONENTS_PATH . '/header.php';

$isEn = current_lang() === 'en';
$res = $mysqli->query('SELECT * FROM team_members ORDER BY sort_order ASC, id ASC');
$breadcrumbs = [
    ['label' => __('nav.team'), 'href' => null],
];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= $isEn ? 'Team' : 'Команда' ?></p>
        <h1 class="ws-page-title"><?= h(__('team.title')) ?></h1>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>
</div>
<div class="container team-grid">
    <?php
    if ($res) {
        while ($t = $res->fetch_assoc()) {
            $name = $isEn ? $t['name_en'] : $t['name_ru'];
            $role = $isEn ? $t['role_en'] : $t['role_ru'];
            $bio = $isEn ? $t['bio_en'] : $t['bio_ru'];
            ?>
            <article class="card card-hover">
                <img src="<?= h(asset($t['photo_path'])) ?>" alt="<?= h($name) ?>" width="400" height="400" loading="lazy">
                <h2><?= h($name) ?></h2>
                <p class="muted"><?= h($role) ?></p>
                <p><?= h($bio) ?></p>
            </article>
            <?php
        }
        $res->free();
    }
    ?>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
