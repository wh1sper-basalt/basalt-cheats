<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$isEn = current_lang() === 'en';
$pageTitle = __('practice_wp1.title');
require COMPONENTS_PATH . '/header.php';

$breadcrumbs = [
    ['label' => $pageTitle, 'href' => null],
];

$wp = 'http://localhost/wp-lab/';

$panels = [
    'site' => [
        'label' => __('practice_wp1.btn_site'),
        'src'   => $wp,
    ],
    'admin' => [
        'label' => __('practice_wp1.btn_admin'),
        'src'   => $wp . 'wp-admin/',
    ],
    'about' => [
        'label' => __('practice_wp1.btn_about'),
        'src'   => $wp . 'about/',
    ],
    'contacts' => [
        'label' => __('practice_wp1.btn_contacts'),
        'src'   => $wp . 'contacts/',
    ],
];
$defaultKey = 'site';
$defaultSrc = $panels[$defaultKey]['src'];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge">WP-1</p>
        <h1 class="ws-page-title"><?= h($pageTitle) ?></h1>
        <p class="ws-page-lead"><?= h(__('practice_wp1.lead')) ?></p>
    </header>
    <div class="card card-hover stack" style="padding:20px;">
        <div class="practice-tabs" role="tablist"
             aria-label="<?= h($isEn ? 'WordPress sections' : 'Разделы практики WordPress') ?>"
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