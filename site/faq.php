<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$c = site_config();
$pageTitle = __('nav.faq');
require COMPONENTS_PATH . '/header.php';
$isEn = current_lang() === 'en';

$name = $isEn ? ($c['company_name_en'] ?? 'Basalt Cheats LLC') : ($c['company_name_ru'] ?? 'ООО «Базальт Читс»');
$inn = $c['company_inn'] ?? '0000000000';
$addr = $isEn ? ($c['company_address_en'] ?? '') : ($c['company_address_ru'] ?? '');
$mapSrc = $c['map_embed_src'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d667.5875791516536!2d37.53720106290784!3d55.750024367473436!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54be6b17e590d%3A0xff98795ca4e09adb!2sFederation%20Tower!5e0!3m2!1sen!2sru!4v1776696009702!5m2!1sen!2sru" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade';

$breadcrumbs = [
    ['label' => __('nav.faq'), 'href' => null],
];

$faqRows = [
    ['q' => __('faq.q_buy'), 'a' => __('faq.a_buy')],
    ['q' => __('faq.q_pay'), 'a' => __('faq.a_pay')],
    ['q' => __('faq.q_safe'), 'a' => __('faq.a_safe')],
    ['q' => __('faq.q_support'), 'a' => __('faq.a_support')],
    ['q' => __('faq.q_os'), 'a' => __('faq.a_os')],
    ['q' => __('faq.q_refund'), 'a' => __('faq.a_refund')],
];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>

    <header class="ws-page-hero">
        <p class="ws-hero-badge"><?= h(__('faq.hero_badge')) ?></p>
        <h1 class="ws-page-title">
            <?= h(__('faq.hero_title_1')) ?> <span class="accent"><?= h(__('faq.hero_title_2')) ?></span>
        </h1>
        <p class="ws-page-lead"><?= h(__('faq.hero_lead')) ?></p>
        <div class="ws-divider" aria-hidden="true"><span>◇</span></div>
    </header>

    <div class="ws-accordion" id="faq-accordion">
        <?php
        $i = 0;
        foreach ($faqRows as $row) {
            ++$i;
            ?>
            <details class="faq-disclosure">
                <summary class="ws-acc-sum">
                    <span class="ws-acc-num"><?= (int) $i ?></span>
                    <span><?= h($row['q']) ?></span>
                    <span class="ws-acc-chev" aria-hidden="true"></span>
                </summary>
                <div class="ws-acc-body">
                    <div class="ws-acc-body-inner">
                        <p><?= h($row['a']) ?></p>
                    </div>
                </div>
            </details>
            <?php
        }
        ?>
        <details class="faq-disclosure">
            <summary class="ws-acc-sum">
                <span class="ws-acc-num"><?= count($faqRows) + 1 ?></span>
                <span><?= h($isEn ? 'Scam?' : 'Скам?') ?></span>
                <span class="ws-acc-chev" aria-hidden="true"></span>
            </summary>
            <div class="ws-acc-body">
                <div class="ws-acc-body-inner">
                    <p><?= $isEn
                        ? 'No. Basalt Cheats publishes legal details, a physical office address, and a verifiable map pin. Payments use a unique QR session tied to your cart (game + cheat + subscription term).'
                        : 'Нет. Basalt Cheats публикует реквизиты, адрес офиса и точку на карте. Оплата — уникальная QR-сессия, привязанная к корзине (игра + чит + срок подписки).' ?></p>
                    <div class="company-block">
                        <h3><?= $isEn ? 'Company' : 'Реквизиты' ?></h3>
                        <p><strong><?= h($name) ?></strong></p>
                        <p class="muted"><?= $isEn ? 'TIN' : 'ИНН' ?>: <code><?= h($inn) ?></code></p>
                        <p><?= $isEn ? 'Office address:' : 'Адрес офиса:' ?> <?= h($addr) ?></p>
                    </div>
                    <h3 class="map-heading"><?= $isEn ? 'Office on map' : 'Офис на карте' ?></h3>
                    <div class="map-frame map-frame-faq">
                        <iframe title="office map" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="<?= h($mapSrc) ?>"></iframe>
                    </div>
                </div>
            </div>
        </details>
    </div>

    <div class="ws-page-hero" style="margin-top: 36px;">
        <p class="ws-page-lead"><?= h(__('faq.cta_hint')) ?></p>
        <p style="margin-top: 14px;">
            <a class="btn btn-primary" href="<?= h(asset('feedback.php')) ?>"><?= h(__('faq.cta_btn')) ?> <img src="<?= h(asset('assets/svg/arrow-right.svg')) ?>" width="14" height="14" alt=""></a>
        </p>
    </div>

    <div class="ws-callout">
        <h3><?= $isEn ? 'Important notice' : 'Важное предупреждение' ?></h3>
        <p class="muted"><?= $isEn
            ? 'Third-party modules can violate game rules and lead to sanctions. Use at your own risk and avoid valuable accounts.'
            : 'Сторонние модули могут нарушать правила игр и привести к санкциям. Используйте на свой риск и не на ценных аккаунтах.' ?></p>
    </div>
</div>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
