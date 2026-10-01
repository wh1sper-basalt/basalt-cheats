<?php

declare(strict_types=1);

$isHeroCity = !empty($practice11HeroCityPage);
$home = $isHeroCity ? 'index.php' : '#top';
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid p-3">
    <a class="navbar-brand d-block d-lg-none" href="index.php">День Победы</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#header-nav"
            aria-controls="header-nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-md-center fs-5" id="header-nav">
      <div class="navbar-nav">
        <a class="nav-link me-4" href="<?= $isHeroCity ? 'index.php' : 'index.php#top' ?>">Главная</a>
        <a class="nav-link me-4" href="<?= $isHeroCity ? 'index.php#history' : '#history' ?>">История</a>
        <a class="nav-link me-4" href="<?= $isHeroCity ? 'index.php#hero' : '#hero' ?>">Герой моей семьи</a>
        <a class="nav-link me-4" href="<?= $isHeroCity ? 'hero-city.php' : '#hero-city' ?>">Города-герои</a>
        <?php if (!$isHeroCity) { ?>
          <a class="nav-link" href="hero-city.php">Все города...</a>
        <?php } ?>
      </div>
    </div>
  </div>
</nav>
