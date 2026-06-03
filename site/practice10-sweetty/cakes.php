<?php

require_once dirname(__DIR__) . '/inc/mobile-guard.php';

  //подключаем шапку
  include("inc/header.php");
  //подключаем базу данных в файл
  include("connect.php");
?>

  <!-- Секция для рекламного баннера -->
  <section class="banner">
    <div class="content">
      <h1>ДОМАШНЯЯ КОНДИТЕРСКАЯ</h1>
      <h2>СДЕЛАНО С ЛЮБОВЬЮ...</h2>
    </div>
  </section>


  <section class="cakes">
    <div class="content">
      <?php
        //создание переменной для контента
        $data = "";
        //запрос к базе данных
        $sql = "SELECT * FROM `cakes`";
        //выполнение запроса
        $result = $mysqli->query($sql);
        //обработка полученных данных
        while($row = $result->fetch_assoc()){
          //формирование контента в переменную
          $data .= sprintf('
          <div class="cake">
            <div class="cake__descript">
              <div class="cake__descript__text">
                <h2>%s</h2>
                <p>
                  %s
                </p>
              </div>
              <img src="%s" alt="%s">
            </div>
            <div class="cake__buy">
              <div class="cake__buy__wrap">
                <div class="cake__buy__wrap__filling">
                  Возможная начинка:
                  <ul>
                    <li>бисквитный</li>
                    <li>фруктовый</li>
                  </ul>
                </div>
                <div class="cake__buy__wrap__price">
                  Стоимость:
                  <ul>
                    <li>2 кг - 4000 р.</li>
                    <li>3 кг - 6000 р.</li>
                  </ul>
                </div>
              </div>
              <a href="index.php#feedback">ЗАКАЗАТЬ</a>
            </div>
          </div>
          ', $row['title'], $row['information'], $row['path'], $row['title']);
          
        }
        //вывод контента
        echo $data;
      ?>
    </div>
  </section>

<?php
  //подключаем подвал
  include("inc/footer.php");
?>