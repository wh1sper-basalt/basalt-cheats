<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/inc/mobile-guard.php';

require __DIR__ . '/data.php';

$slides = practice11_carousel_slides();
$cities = practice11_hero_cities();
$previewCities = array_slice($cities, 0, 6);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>День Победы — практика 11</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/style/style.css">
</head>
<body id="top">
<?php require __DIR__ . '/inc/nav.php'; ?>

<section class="banner">
  <div class="content w-100">
    <h1>День Победы</h1>
    <h2>1941 - 1945</h2>
  </div>
</section>

<main>
  <div class="history p-5 text-center" id="history">
    <h2 class="display-5">
      Победа в <span class="select">Великой Отечественной войне</span> —<br>
      героический подвиг народа.
    </h2>
    <h2 class="display-5">День Победы мы отмечаем как главный праздник страны.</h2>
    <h2 class="display-5"><span class="select">Вечная память</span> павшим в боях! Слава победителям!</h2>
  </div>

  <section class="carousel p-3" aria-label="События войны">
    <div id="victoryCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php foreach ($slides as $i => $slide) { ?>
          <div class="carousel-item<?= $i === 0 ? ' active' : '' ?>">
            <img src="<?= htmlspecialchars($slide['img'], ENT_QUOTES, 'UTF-8') ?>" class="d-block w-100" alt="<?= htmlspecialchars($slide['title'], ENT_QUOTES, 'UTF-8') ?>">
            <div class="carousel-caption d-none d-md-block">
              <p class="carousel-date carousel-note"><?= htmlspecialchars($slide['year'], ENT_QUOTES, 'UTF-8') ?></p>
              <p class="carousel-text"><?= htmlspecialchars($slide['text'], ENT_QUOTES, 'UTF-8') ?></p>
              <p class="carousel-note"><?= htmlspecialchars($slide['tag'], ENT_QUOTES, 'UTF-8') ?></p>
              <h3 class="carousel-title"><?= htmlspecialchars($slide['title'], ENT_QUOTES, 'UTF-8') ?></h3>
            </div>
          </div>
        <?php } ?>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#victoryCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#victoryCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </section>

  <section class="hero p-5" id="hero">
    <h2 class="display-4 text-center pb-5">
      Нет в России семьи такой,<br>Где б не памятен был свой герой...
    </h2>
    <div class="hero-info row d-flex justify-content-evenly">
      <div class="col col-12 col-md-3 hero mb-3 text-center">
        <img src="assets/media/hero.jpg" alt="Фамилия Имя Отчество" class="hero-photo img-fluid">
      </div>
      <div class="col col-12 col-md-8 hero-history px-5">
        <h2 class="hero-name display-3 p-1">Фамилия Имя Отчество</h2>
        <h3 class="hero-date display-4 p-1">1909-1945</h3>
        <p class="fs-3">Разместите здесь информацию о герое вашей семьи, учебного заведения или города.</p>
        <p class="fs-3"><span class="select">Никто не забыт! Ничто не забыто!</span></p>
      </div>
    </div>
  </section>

  <section class="container py-5" id="hero-city">
    <h2 class="display-4 text-center pb-5">Города — герои</h2>
    <p class="fs-3">Когда в июне 1941 года фашистская Германия обрушила на нашу страну всю мощь своих армий,
      на их пути <span class="select">могучими бастионами</span> встали советские города.</p>
    <?php
    $cities = $previewCities;
    $colsClass = 'row-cols-1 row-cols-md-3';
    require __DIR__ . '/inc/cities-grid.php';
    ?>
    <div class="d-flex justify-content-end">
      <a href="hero-city.php" class="btn fs-4 mt-3">Все города...</a>
    </div>
  </section>
</main>

<footer class="footer text-center p-1">
  <div class="content p-3">
    <p>Проект подготовлен для практики №11 (учебный мини-сайт «День Победы»).</p>
    <p>Материалы: <a href="https://may9.ru/" target="_blank" rel="noopener noreferrer">may9.ru</a></p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcYF0tY31HB60NNkMxCs5s9fDVZLESaAA55NDzOxhy9GkcIds1k1eN7N6jIeHz"
        crossorigin="anonymous"></script>
<script src="../../assets/js/devtools-guard.js" defer></script>
</body>
</html>
