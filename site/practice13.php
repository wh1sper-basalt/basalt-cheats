<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$isEn = current_lang() === 'en';
$pageTitle = $isEn ? 'Practice 13 — Music DB' : 'Практика 13 — Музыкальный каталог';
require COMPONENTS_PATH . '/header.php';

$breadcrumbs = [
    ['label' => $pageTitle, 'href' => null],
];

$panels = [
    'groups' => [
        'label' => $isEn ? 'Groups' : 'Группы',
        'src' => asset('practice13-music/index.php'),
    ],
    'albums' => [
        'label' => $isEn ? 'Albums' : 'Альбомы',
        'src' => asset('practice13-music/albums.php'),
    ],
    'tracks' => [
        'label' => $isEn ? 'Tracks' : 'Треки',
        'src' => asset('practice13-music/tracks.php'),
    ],
];
$defaultSrc = $panels['groups']['src'];
?>
<div class="container">
    <?php require COMPONENTS_PATH . '/breadcrumbs.php'; ?>
    <header class="ws-page-hero">
        <p class="ws-hero-badge">PR13</p>
        <h1 class="ws-page-title"><?= h($pageTitle) ?></h1>
        <p class="ws-page-lead"><?= h($isEn ? 'Groups, albums and tracks from basalt_practice13.' : 'Группы, альбомы и треки из отдельной БД basalt_practice13.') ?></p>
    </header>
    <div class="card card-hover stack" style="padding:20px;">
        <div class="practice13-tabs" role="tablist" aria-label="<?= h($isEn ? 'Music catalog sections' : 'Разделы музыкального каталога') ?>" style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:14px;">
            <?php foreach ($panels as $key => $panel) { ?>
                <button
                    type="button"
                    class="btn<?= $key === 'groups' ? ' btn-primary' : ' btn-ghost' ?>"
                    role="tab"
                    aria-selected="<?= $key === 'groups' ? 'true' : 'false' ?>"
                    data-practice13-panel="<?= h($key) ?>"
                    data-practice13-src="<?= h($panel['src']) ?>"
                ><?= h($panel['label']) ?></button>
            <?php } ?>
        </div>
        <div class="console-frame" style="height: 70vh;">
            <iframe
                id="practice13-frame"
                title="<?= h($isEn ? 'Practice 13 music catalog' : 'Практика 13 — музыкальный каталог') ?>"
                src="<?= h($defaultSrc) ?>"
                style="width:100%;height:100%;border:0;"
            ></iframe>
        </div>
    </div>
</div>
<script>
(() => {
  const frame = document.getElementById("practice13-frame");
  const tabs = document.querySelectorAll("[data-practice13-panel]");
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
      const src = btn.getAttribute("data-practice13-src");
      if (!src || frame.getAttribute("src") === src) return;
      frame.setAttribute("src", src);
      setActive(btn);
    });
  });
})();
</script>
<?php require COMPONENTS_PATH . '/footer.php'; ?>
