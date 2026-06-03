<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/inc/mobile-guard.php';

include __DIR__ . '/inc/header.php';
?>
<section class="feedback" id="feedback">
  <div class="content">
    <h2>ГОТОВ К ЗАКАЗУ ИЛИ НЕ МОЖЕШЬ ОПРЕДЕЛИТЬСЯ? ЗВОНИ!</h2>
    <a href="tel:+72223332255"><h3>+7 (222) 333-22-55</h3></a>
    <h4>ИЛИ ОСТАВЬ НАМ СВОЙ НОМЕР И МЫ ПЕРЕЗВОНИМ САМИ</h4>
    <form action="add_feedback.php" method="post" enctype="multipart/form-data">
      <div class="form__contact">
        <input type="text" name="name" placeholder="НАПИШИТЕ СВОЕ ИМЯ" required pattern="[A-Za-zА-Яа-яЁё]{2,}">
        <input type="tel" name="tel-number" placeholder="НОМЕР ТЕЛЕФОНА" required pattern="[0-9]{11}">
      </div>
      <div class="form__download">
        ЕСТЬ ФОТО ПРИМЕРА?
        <label for="download">ЗАГРУЖАЙ ЗДЕСЬ</label>
        <input type="file" id="download" name="download" accept=".png,.jpg,.jpeg">
      </div>
      <div class="form__submit">
        <input type="checkbox" name="personal" id="personal">
        <label for="personal">Я СОГЛАСЕН НА ОБРАБОТКУ ПЕРСОНАЛЬНЫХ ДАННЫХ</label>
      </div>
      <input type="submit" value="ПООБЩАТЬСЯ">
    </form>
  </div>
</section>
<?php include __DIR__ . '/inc/footer.php'; ?>
