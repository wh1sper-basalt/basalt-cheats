<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/inc/mobile-guard.php';

require __DIR__ . '/data.php';

$practice11HeroCityPage = true;
$cities = practice11_hero_cities();
$colsClass = 'row-cols-1 row-cols-md-2';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Города-герои — практика 11</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/style/style.css">
</head>
<body>
<?php require __DIR__ . '/inc/nav.php'; ?>

<main class="container py-5">
  <h1 class="display-4 text-center pb-4">Города — герои</h1>
  <p class="fs-3 pb-4">Полный список городов, удостоенных звания «Город-герой».</p>
  <?php require __DIR__ . '/inc/cities-grid.php'; ?>
  <p class="mt-4"><a href="index.php" class="btn btn-outline-secondary">На главную</a></p>
</main>

<footer class="footer text-center p-1">
  <div class="content p-3">
    <p><a href="index.php">День Победы</a> — практика №11</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcYF0tY31HB60NNkMxCs5s9fDVZLESaAA55NDzOxhy9GkcIds1k1eN7N6jIeHz"
        crossorigin="anonymous"></script>
<script src="../../assets/js/devtools-guard.js" defer></script>
</body>
</html>
