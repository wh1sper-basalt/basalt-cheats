<?php

declare(strict_types=1);

/** @var list<array{slug:string,name_ru:string,name_en:string,text:string,url:string,img:string}> $cities */
/** @var string $colsClass */
$colsClass = $colsClass ?? 'row-cols-1 row-cols-md-3';
?>
<div class="cards d-flex justify-content-evenly row <?= htmlspecialchars($colsClass, ENT_QUOTES, 'UTF-8') ?> g-4">
  <?php foreach ($cities as $city) {
      $name = htmlspecialchars($city['name_ru'], ENT_QUOTES, 'UTF-8');
      ?>
    <div class="col">
      <div class="card h-100">
        <img src="<?= htmlspecialchars($city['img'], ENT_QUOTES, 'UTF-8') ?>" class="card-img-top" alt="<?= $name ?>" loading="lazy"
             onerror="this.dataset.missing='1';">
        <div class="card-body">
          <h5 class="card-title fs-3"><?= $name ?></h5>
          <p class="card-text"><?= htmlspecialchars($city['text'], ENT_QUOTES, 'UTF-8') ?></p>
          <a href="<?= htmlspecialchars($city['url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer" class="btn stretched-link">Узнать больше</a>
        </div>
      </div>
    </div>
  <?php } ?>
</div>
