<?php

declare(strict_types=1);

$c = site_config();
$brand = $c['brand'] ?? 'Basalt';
$tg = $c['telegram_url'] ?? '#';
$dc = $c['discord_url'] ?? '';
$gh = trim((string) ($c['github_url'] ?? ''));
$logoPath = $c['logo_path'] ?? 'assets/img/components/logo.png';
$isRu = current_lang() === 'ru';
$tag = $isRu ? ($c['tagline_ru'] ?? '') : ($c['tagline_en'] ?? '');
$gamesFoot = [
    ['slug' => 'rust', 'label' => 'Rust'],
    ['slug' => 'eft', 'label' => 'Escape from Tarkov'],
    ['slug' => 'genshin-impact', 'label' => 'Genshin Impact'],
];
?>
</main>
<footer class="site-footer ws-footer">
    <div class="container footer-grid">
        <div class="footer-col footer-brand">
            <a class="footer-logo footer-logo-link" href="<?= h(asset('index.php')) ?>">
                <img class="brand-logo sm" src="<?= h(asset($logoPath)) ?>" width="36" height="36" alt="<?= h($brand) ?>" draggable="false">
                <strong>Basalt Cheats</strong>
            </a>
            <p class="muted footer-tag"><?= h($tag) ?></p>
        </div>
        <div class="footer-col">
            <h3 class="footer-heading"><?= h(__('footer.categories')) ?></h3>
            <ul class="footer-links">
                <li><a href="<?= h(asset('games.php')) ?>"><?= h(__('footer.all_games')) ?></a></li>
                <?php foreach ($gamesFoot as $gf) { ?>
                    <li><a href="<?= h(url_to('cheats.php', ['game' => $gf['slug']])) ?>"><?= h($gf['label']) ?></a></li>
                <?php } ?>
            </ul>
        </div>
        <div class="footer-col">
            <h3 class="footer-heading"><?= h(__('footer.info')) ?></h3>
            <ul class="footer-links">
                <li><a href="<?= h(asset('faq.php')) ?>"><?= h(__('nav.faq')) ?></a></li>
                <li><a href="<?= h(asset('feedback.php')) ?>"><?= h(__('nav.support')) ?></a></li>
                <li><a href="<?= h(asset('freebies.php')) ?>"><?= h(__('nav.freebies')) ?></a></li>
                <li><a href="<?= h(asset('reviews.php')) ?>"><?= h(__('nav.reviews')) ?></a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h3 class="footer-heading"><?= h(__('footer.contact')) ?></h3>
            <ul class="footer-links footer-social">
                <li><a href="<?= h($tg) ?>" target="_blank" rel="noopener noreferrer">Telegram</a></li>
                <?php if ($dc !== '') { ?>
                    <li><a href="<?= h($dc) ?>" target="_blank" rel="noopener noreferrer">Discord</a></li>
                <?php } ?>
                <?php if ($gh !== '') { ?>
                    <li><a href="<?= h($gh) ?>" target="_blank" rel="noopener noreferrer">GitHub</a></li>
                <?php } ?>
                <li><a href="mailto:<?= h($c['support_email'] ?? '') ?>"><?= h($c['support_email'] ?? '') ?></a></li>
            </ul>
        </div>
    </div>
    <div class="container footer-bar">
        <p class="muted">© <?= h(date('Y')) ?> <?= h($brand) ?>. <?= h(__('footer.rights')) ?></p>
        <p class="muted"><a href="<?= h(asset('faq.php')) ?>"><?= h(__('footer.terms_hint')) ?></a></p>
    </div>
</footer>
<button type="button" class="back-to-top" id="back-to-top" hidden aria-label="<?= h($isRu ? 'Наверх' : 'Back to top') ?>">
    <img src="<?= h(asset('assets/svg/arrow-up.svg')) ?>" width="18" height="18" alt="">
</button>
<script src="<?= h(asset('assets/js/devtools-guard.js')) ?>" defer></script>
<script src="<?= h(asset('assets/js/network-bg.js')) ?>" defer></script>
<script src="<?= h(asset('assets/js/main.js')) ?>" defer></script>
</body>
</html>
