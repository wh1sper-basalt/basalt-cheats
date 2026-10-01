<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$isEn = current_lang() === 'en';
$pageTitle = __('practice_wp3.title');
require COMPONENTS_PATH . '/header.php';

$breadcrumbs = [
    ['label' => $pageTitle, 'href' => null],
];

$wp = 'http://localhost/wp-lab/';

$panels = [
    'shop' => [
        'label' => __('practice_wp3.btn_shop'),
        'src'   => $wp . 'shop/',
    ],
    'products' => [
        'label' => __('practice_wp3.btn_products'),
        'src'   => $wp . 'wp-admin/edit.php?post_type=product',
    ],
    'orders' => [
        'label' => __('practice_wp3.btn_orders'),
        'src'   => $wp . 'wp-admin/edit.php?post_type=shop_order',
    ],
    'settings' => [
        'label' => __('practice_wp3.btn_settings'),
        'src'   => $wp . 'wp-admin/admin.php?page=wc-settings&tab=products&section=inventory',
    ],
];
$defaultKey = 'shop';
$defaultSrc = $panels[$defaultKey]['src'];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge">WP-3</p>
        <h1 class="ws-page-title"><?= h($pageTitle) ?></h1>
        <p class="ws-page-lead"><?= h(__('practice_wp3.lead')) ?></p>
    </header>
    <div class="card card-hover stack" style="padding:20px;">
        <div class="practice-tabs" role="tablist"
             aria-label="<?= h($isEn ? 'WooCommerce sections' : 'Разделы WooCommerce') ?>"
             style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:14px;">
            <?php foreach ($panels as $key => $panel) { ?>
                <button
                    type="button"
                    class="btn<?= $key === $defaultKey ? ' btn-primary' : ' btn-ghost' ?>"
                    role="tab"
                    aria-selected="<?= $key === $defaultKey ? 'true' : 'false' ?>"
                    data-wp-panel="<?= h($key) ?>"
                    data-wp-src="<?= h($panel['src']) ?>"
                ><?= h($panel['label']) ?></button>
            <?php } ?>
        </div>
        <div class="console-frame" style="height: 70vh;">
            <iframe
                id="wp-frame"
                title="<?= h($pageTitle) ?>"
                src="<?= h($defaultSrc) ?>"
                style="width:100%;height:100%;border:0;background:#fff;"
            ></iframe>
        </div>
    </div>
</div>
<script>
(() => {
  const frame = document.getElementById("wp-frame");
  const tabs = document.querySelectorAll("[data-wp-panel]");
  if (!frame || !tabs.length) return;

  const setActive = (active) => {
    tabs.forEach((btn) => {
      const on = btn === active;
      btn.setAttribute("aria-selected", on ? "true" : "false");
      btn.classList.toggle("btn-primary", on);
      btn.classList.toggle("btn-ghost", !on);
    });
  };

  tabs.forEach((btn) => {
    btn.addEventListener("click", () => {
      const src = btn.getAttribute("data-wp-src");
      if (!src || frame.getAttribute("src") === src) return;
      frame.setAttribute("src", src);
      setActive(btn);
    });
  });
})();
</script>
<?php require COMPONENTS_PATH . '/footer.php'; ?>