<?php

declare(strict_types=1);

$c = site_config();
$brand = $c['brand'] ?? 'Basalt';
$siteTitle = __('meta.title_suffix');
$pagePart = trim((string) ($pageTitle ?? ''));
$title = $siteTitle . ($pagePart !== '' ? ' · ' . $pagePart : '');
$cssBase = asset('assets/css');
$tg = $c['telegram_url'] ?? '#';
$user = current_user();
$isLoggedIn = $user !== null;
$logoPath = $c['logo_path'] ?? 'assets/img/components/logo.png';
$faviconPath = $c['favicon_path'] ?? 'assets/img/components/icon.ico';
$authError = flash_get('auth_error');
$authOk = flash_get('auth_ok');
$authRegisterError = flash_get('auth_register_error');
$authPanel = flash_get('auth_panel') ?? ($authRegisterError !== null ? 'register' : 'login');
if (!in_array($authPanel, ['login', 'register'], true)) {
    $authPanel = 'login';
}
?>
<!DOCTYPE html>
<html lang="<?= h(current_lang()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($title) ?></title>
    <meta name="theme-color" content="#070707">
    <link rel="icon" type="image/x-icon" href="<?= h(asset($faviconPath)) ?>">
    <link rel="stylesheet" href="<?= h($cssBase) ?>/variables.css">
    <link rel="stylesheet" href="<?= h($cssBase) ?>/reset.css">
    <link rel="stylesheet" href="<?= h($cssBase) ?>/animations.css">
    <link rel="stylesheet" href="<?= h($cssBase) ?>/base.css">
    <link rel="stylesheet" href="<?= h($cssBase) ?>/layout.css">
    <link rel="stylesheet" href="<?= h($cssBase) ?>/components.css">
    <link rel="stylesheet" href="<?= h($cssBase) ?>/forms.css">
    <link rel="stylesheet" href="<?= h($cssBase) ?>/pages.css">
</head>
<body>
<div id="scroll-progress" aria-hidden="true"></div>
<div class="custom-cursor-dot" aria-hidden="true"></div>
<div class="custom-cursor-ring" aria-hidden="true"></div>
<div class="bg-stack animated-bg-ws" aria-hidden="true">
    <div class="floating-orbs">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>
    <div class="bg-photo"></div>
    <div class="bg-scrim"></div>
    <canvas id="network-bg" class="bg-network"></canvas>
</div>
<header class="site-header">
    <div class="container nav-outer">
        <div class="nav-dock glass-dock" id="nav-dock">
            <div class="nav-dock-main">
            <a class="brand-mark" href="<?= h(asset('index.php')) ?>" aria-label="<?= h($brand) ?>">
                <img class="brand-logo" src="<?= h(asset($logoPath)) ?>" width="36" height="36" alt="<?= h($brand) ?>" draggable="false">
            </a>
            <nav class="nav-primary" aria-label="Main">
                <a class="<?= h(nav_class('home')) ?>" href="<?= h(asset('index.php')) ?>"><?= h(__('nav.home')) ?></a>
                <a class="<?= h(nav_class('shop')) ?>" href="<?= h(asset('games.php')) ?>"><?= h(__('nav.shop')) ?></a>
                <a class="<?= h(nav_class('reviews')) ?>" href="<?= h(asset('reviews.php')) ?>"><?= h(__('nav.reviews')) ?></a>
                <a class="<?= h(nav_class('blog')) ?>" href="<?= h(asset('chronicle.php')) ?>"><?= h(__('nav.blog')) ?></a>
                <a class="<?= h(nav_class('freebies')) ?>" href="<?= h(asset('freebies.php')) ?>"><?= h(__('nav.freebies')) ?></a>
                <a class="<?= h(nav_class('support')) ?>" href="<?= h(asset('feedback.php')) ?>"><?= h(__('nav.support')) ?></a>
                <a class="<?= h(nav_class('faq')) ?>" href="<?= h(asset('faq.php')) ?>"><?= h(__('nav.faq')) ?></a>
                <?php if (has_admin_access() && basename($_SERVER['SCRIPT_NAME'] ?? '') === 'admin.php') { ?>
                    <a class="<?= h(nav_class('admin')) ?>" href="<?= h(asset('admin.php')) ?>"><?= h(__('nav.admin')) ?></a>
                <?php } ?>
            </nav>
            <div class="nav-actions">
                <div class="lang-pill" role="group" aria-label="Language">
                    <a class="<?= current_lang() === 'ru' ? 'is-active' : '' ?>" href="<?= h(lang_switch_url('ru')) ?>"><?= h(__('lang.ru')) ?></a>
                    <a class="<?= current_lang() === 'en' ? 'is-active' : '' ?>" href="<?= h(lang_switch_url('en')) ?>"><?= h(__('lang.en')) ?></a>
                </div>
                <a class="nav-tg" href="<?= h($tg) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= h(__('nav.telegram')) ?>">
                    <span class="ws-sr-only"><?= h(__('nav.telegram')) ?></span>
                    <img class="nav-tg-ico" src="<?= h(asset('assets/img/components/telegram-logo.svg')) ?>" alt="" width="18" height="18">
                </a>
                <?php if ($isLoggedIn) {
                    $navAvatar = trim((string) ($user['avatar_path'] ?? ''));
                    $navAvatarSrc = $navAvatar !== '' ? asset($navAvatar) : asset('assets/img/components/account-icon.svg');
                    ?>
                    <a class="nav-account<?= $navAvatar !== '' ? ' has-avatar' : '' ?>" href="<?= h(asset('user-info.php')) ?>" title="<?= h(__('auth.account')) ?>">
                        <img src="<?= h($navAvatarSrc) ?>" width="18" height="18" alt="">
                    </a>
                <?php } else { ?>
                    <button type="button" class="nav-account" id="account-launcher" aria-expanded="false" aria-controls="account-modal" title="<?= h(__('auth.account')) ?>">
                        <img src="<?= h(asset('assets/img/components/account-icon.svg')) ?>" width="18" height="18" alt="">
                    </button>
                <?php } ?>
                <button type="button" class="nav-more" id="nav-more" aria-expanded="false" aria-controls="nav-secondary" title="<?= h(__('nav.more')) ?>">
                    <img src="<?= h(asset('assets/svg/more-horizontal.svg')) ?>" width="18" height="18" alt="">
                </button>
            </div>
            </div>
            <div class="nav-expand-panel">
                <div class="nav-expand-panel-inner">
                    <nav class="nav-secondary" id="nav-secondary" aria-label="<?= h(__('nav.more')) ?>">
                        <div class="drawer-grid nav-secondary-links">
                            <a href="<?= h(asset('gallery.php')) ?>"><?= h(__('nav.gallery')) ?></a>
                            <a href="<?= h(asset('team.php')) ?>"><?= h(__('nav.team')) ?></a>
                            <a href="<?= h(asset('regions.php')) ?>"><?= h(__('nav.regions')) ?></a>
                            <a href="<?= h(asset('operations.php')) ?>"><?= h(__('nav.operations')) ?></a>
                            <a href="<?= h(asset('history.php')) ?>"><?= h(__('nav.history')) ?></a>
                            <a href="<?= h(asset('index-db-blocks.php')) ?>"><?= h(__('nav.indexdb')) ?></a>
                            <a href="<?= h(asset('iframe-target-lab.php')) ?>"><?= h(__('nav.iframe_lab')) ?></a>
                            <a href="<?= h(asset('practice10.php')) ?>"><?= h(__('nav.practice10')) ?></a>
                            <a href="<?= h(asset('practice10-sweetty-79.php')) ?>"><?= h(__('nav.practice10_79')) ?></a>
                            <a href="<?= h(asset('practice10-sweetty-710.php')) ?>"><?= h(__('nav.practice10_710')) ?></a>
                            <a href="<?= h(asset('practice11.php')) ?>"><?= h(__('nav.practice11')) ?></a>
                            <a href="<?= h(asset('practice11-cities.php')) ?>"><?= h(__('nav.practice11_cities')) ?></a>
                            <a href="<?= h(asset('practice12-async.php')) ?>"><?= h(__('nav.practice12')) ?></a>
                            <a href="<?= h(asset('practice13.php')) ?>"><?= h(__('nav.practice13')) ?></a>
                            <a href="<?= h(asset('validation-lab.php')) ?>"><?= h(__('nav.validation')) ?></a>
                            <a href="<?= h(asset('runtime-demo.php')) ?>"><?= h(__('nav.runtime')) ?></a>
                            <a href="<?= h(asset('support-console.php')) ?>"><?= h(__('nav.console')) ?></a>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
<?php if (!$isLoggedIn) { ?>
<div class="account-modal" id="account-modal" hidden data-initial-panel="<?= h($authPanel) ?>">
    <div class="account-modal-backdrop" data-account-close></div>
    <div class="account-modal-dialog card">
        <button type="button" class="account-modal-close" data-account-close aria-label="<?= h(__('common.close')) ?>">
            <img src="<?= h(asset('assets/svg/close.svg')) ?>" width="16" height="16" alt="">
        </button>
        <div class="account-modal-head">
            <div class="account-modal-tabs" role="tablist" aria-label="<?= h(__('auth.account')) ?>">
                <button type="button" class="account-modal-tab account-switcher<?= $authPanel === 'login' ? ' is-active' : '' ?>" role="tab" data-account-target="login" aria-selected="<?= $authPanel === 'login' ? 'true' : 'false' ?>">
                    <?= h(__('auth.login')) ?>
                </button>
                <button type="button" class="account-modal-tab account-switcher<?= $authPanel === 'register' ? ' is-active' : '' ?>" role="tab" data-account-target="register" aria-selected="<?= $authPanel === 'register' ? 'true' : 'false' ?>">
                    <?= h(__('auth.register')) ?>
                </button>
            </div>
        </div>
        <section class="account-panel<?= $authPanel === 'login' ? ' is-active' : '' ?>" id="account-panel-login" role="tabpanel">
            <p class="account-modal-lead muted"><?= h(__('auth.modal_lead_login')) ?></p>
            <?php if ($authError !== null) { ?>
                <p class="form-alert is-error"><?= h($authError) ?></p>
            <?php } ?>
            <?php if ($authOk !== null) { ?>
                <p class="form-alert is-ok"><?= h($authOk) ?></p>
            <?php } ?>
            <form method="post" action="<?= h(asset('actions/auth-login.php')) ?>" class="form-grid account-auth-form">
                <input type="hidden" name="back" value="<?= h($_SERVER['REQUEST_URI'] ?? asset('index.php')) ?>">
                <label class="full">
                    <span><?= h(__('checkout.email')) ?></span>
                    <input type="email" name="email" required autocomplete="email">
                </label>
                <label class="full">
                    <span><?= h(__('auth.password')) ?></span>
                    <input type="password" name="password" required autocomplete="current-password">
                </label>
                <label class="full remember-row">
                    <input class="remember-check" type="checkbox" name="remember" value="1">
                    <span><?= h(__('auth.remember_me')) ?></span>
                </label>
                <div class="full">
                    <button class="btn btn-primary full-width" type="submit"><?= h(__('auth.login_submit')) ?></button>
                </div>
                <p class="full account-modal-foot muted">
                    <?= h(__('auth.no_account')) ?>
                    <button type="button" class="account-modal-link account-switcher" data-account-target="register"><?= h(__('auth.switch_to_register')) ?></button>
                </p>
            </form>
        </section>
        <section class="account-panel<?= $authPanel === 'register' ? ' is-active' : '' ?>" id="account-panel-register" role="tabpanel">
            <p class="account-modal-lead muted"><?= h(__('auth.modal_lead_register')) ?></p>
            <?php if ($authRegisterError !== null) { ?>
                <p class="form-alert is-error"><?= h($authRegisterError) ?></p>
            <?php } ?>
            <form method="post" action="<?= h(asset('actions/auth-register.php')) ?>" class="form-grid account-auth-form">
                <input type="hidden" name="back" value="<?= h($_SERVER['REQUEST_URI'] ?? asset('index.php')) ?>">
                <label class="full">
                    <span><?= h(__('checkout.email')) ?></span>
                    <input type="email" name="email" required autocomplete="email">
                </label>
                <label class="full">
                    <span><?= h(__('auth.password')) ?></span>
                    <input type="password" name="password" required autocomplete="new-password" minlength="6">
                </label>
                <label class="full remember-row">
                    <input class="remember-check" type="checkbox" name="remember" value="1" checked>
                    <span><?= h(__('auth.remember_me')) ?></span>
                </label>
                <div class="full">
                    <button class="btn btn-primary full-width" type="submit"><?= h(__('auth.register_submit')) ?></button>
                </div>
                <p class="full account-modal-foot muted">
                    <?= h(__('auth.have_account')) ?>
                    <button type="button" class="account-modal-link account-switcher" data-account-target="login"><?= h(__('auth.switch_to_login')) ?></button>
                </p>
            </form>
        </section>
    </div>
</div>
<?php } ?>
<?php $mainClass = $mainClass ?? 'site-main'; ?>
<main class="<?= h($mainClass) ?>">
