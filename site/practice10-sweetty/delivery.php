<?php

require_once dirname(__DIR__) . '/inc/mobile-guard.php';

  //подключаем шапку
  include("inc/header.php");
?>

  <!-- Секция для рекламного баннера -->
  <section class="banner">
    <div class="content">
      <h1>ДОМАШНЯЯ КОНДИТЕРСКАЯ</h1>
      <h2>СДЕЛАНО С ЛЮБОВЬЮ...</h2>
    </div>
  </section>


  <!-- Секция для блока условий доставки -->
  <section class="delivery">
    <div class="content">

      <div class="delivery__about">
        <blockquote>
          Доставка торта — это сложная задача, требующая предельной
          аккуратности. Для меня важно, чтобы ваш торт попал к вам в
          целости и сохранности.
        </blockquote>
      </div>

      <div class="delivery__price">
        <table>
          <thead>
            <tr>
              <td>РАЙОНЫ</td>
              <td>БЕСПЛАТНАЯ</td>
              <td>ПЛАТНАЯ</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                Дзержинский <br>
                Октябрьский<br>
                Калининский
              </td>
              <td>ОТ 800 РУБЛЕЙ</td>
              <td>200 РУБЛЕЙ</td>
            </tr>
            <tr>
              <td>
                Заельцовский <br>
                Железнодорожный <br>Центральный
              </td>
              <td>ОТ 1000 РУБЛЕЙ</td>
              <td>300 РУБЛЕЙ</td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </section>

<?php
  //подключаем подвал
  include("inc/footer.php");
?>