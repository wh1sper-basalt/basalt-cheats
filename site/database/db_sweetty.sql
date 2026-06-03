-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 18 2023 г., 13:58
-- Версия сервера: 5.7.39
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `db_sweetty`
--
CREATE DATABASE IF NOT EXISTS `db_sweetty` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_sweetty`;

-- --------------------------------------------------------

--
-- Структура таблицы `cakes`
--

DROP TABLE IF EXISTS `cakes`;
CREATE TABLE `cakes` (
  `id_cake` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `information` text COLLATE utf8mb4_unicode_ci,
  `path` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Очистить таблицу перед добавлением данных `cakes`
--

TRUNCATE TABLE `cakes`;
--
-- Дамп данных таблицы `cakes`
--

INSERT INTO `cakes` (`id_cake`, `title`, `information`, `path`) VALUES
(1, 'НЕЖНЫЙ БАРХАТ', 'Бисквитный торт ручной работы со сливочным кремом. Несмотря на простоту, имеет очень нарядный внешний вид. Идеально подходит для свадьбы или Дня Святого Валентина.', 'assets/img/cakes/gentle-velvet.jpg'),
(2, 'ИЗУМРУД', 'Очень яркий во вкусе и цвете торт! За счет контраста красного и зеленого выглядит празднично и поистине как драгоценный камень! Прекрасный подарок!', 'assets/img/cakes/emerald.jpg'),
(3, 'ПТИЧЬЕ МОЛОКО', 'Классика кондитерского искусства советских, да и российских времен. Воздушный, легкий и белоснежный!', 'assets/img/cakes/birds-milk.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
  `id_feedback` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` bigint(12) NOT NULL,
  `path` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Очистить таблицу перед добавлением данных `feedback`
--

TRUNCATE TABLE `feedback`;
-- --------------------------------------------------------

--
-- Структура таблицы `fillings`
--

DROP TABLE IF EXISTS `fillings`;
CREATE TABLE `fillings` (
  `id_filling` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Очистить таблицу перед добавлением данных `fillings`
--

TRUNCATE TABLE `fillings`;
--
-- Дамп данных таблицы `fillings`
--

INSERT INTO `fillings` (`id_filling`, `name`, `path`) VALUES
(1, 'ЯГОДНЫЙ', 'assets/img/fillings/berry.jpg'),
(2, 'ФИСТАШКОВЫЙ', 'assets/img/fillings/pistachio.jpg'),
(3, 'КОКОСОВЫЙ', 'assets/img/fillings/coconut.jpg'),
(4, 'ЛИМОННЫЙ', 'assets/img/fillings/citric.jpg'),
(5, 'ШОКОЛАДНЫЙ', 'assets/img/fillings/chocolate.jpg'),
(6, 'МЯТНЫЙ', 'assets/img/fillings/mint.jpg'),
(7, 'МЕДОВЫЙ', 'assets/img/fillings/honey.jpg'),
(8, 'ФРУКТОВЫЙ', 'assets/img/fillings/fruit.jpg'),
(9, 'ОРЕХОВЫЙ', 'assets/img/fillings/walnut.jpg'),
(10, 'КОФЕЙНЫЙ', 'assets/img/fillings/coffee.jpg'),
(11, 'КАРАМЕЛЬНЫЙ', 'assets/img/fillings/caramel.jpg'),
(12, 'ЙОГУРТОВЫЙ', 'assets/img/fillings/yoghurt.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `index_page`
--

DROP TABLE IF EXISTS `index_page`;
CREATE TABLE `index_page` (
  `id_element` int(11) NOT NULL,
  `alias` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Очистить таблицу перед добавлением данных `index_page`
--

TRUNCATE TABLE `index_page`;
--
-- Дамп данных таблицы `index_page`
--

INSERT INTO `index_page` (`id_element`, `alias`, `content`) VALUES
(1, 'banner', '<section class=\"banner first\">\r\n    <div class=\"content\">\r\n      <h1>ДОМАШНЯЯ КОНДИТЕРСКАЯ</h1>\r\n      <h2>СДЕЛАНО С ЛЮБОВЬЮ...</h2>\r\n    </div>\r\n  </section>'),
(2, 'advantages', '<section class=\"advantages\">\r\n    <div class=\"content\">\r\n      <div class=\"advantage\">\r\n        <img src=\"assets/img/icons/icon_1.png\" alt=\"Натуральные продукты\">\r\n        <p> Только<br> натуральные продукты </p>\r\n      </div>\r\n      <div class=\"advantage\">\r\n        <img src=\"assets/img/icons/icon_2.png\" alt=\"Без консервантов\">\r\n        <p> Без консервантов,<br> срок хранения 48 часов </p>\r\n      </div>\r\n      <div class=\"advantage\">\r\n        <img src=\"assets/img/icons/icon_3.png\" alt=\"Индивидуальный дизайн\">\r\n        <p> Индивидуальный дизайн </p>\r\n      </div>\r\n      <div class=\"advantage\">\r\n        <img src=\"assets/img/icons/icon_4.png\" alt=\"Разные вкусы\">\r\n        <p> Разнообразие<br> вкусов в одном торте </p>\r\n      </div>\r\n    </div>\r\n  </section>'),
(3, 'select', '<section class=\"select\">\r\n    <div class=\"content\">\r\n      <h2>А ЧЕГО ЖЕЛАЕТЕ ВЫ?</h2>\r\n      <div class=\"select__cakes\">\r\n        <div class=\"select__cake\">\r\n          <img src=\"assets/img/select-cake-1.jpg\" alt=\"Выбрать готовый торт\">\r\n          <a href=\"cakes.php\">ВЫБРАТЬ ГОТОВЫЙ</a>\r\n        </div>\r\n        <div class=\"select__cake\">\r\n          <img src=\"assets/img/select-cake-2.jpg\" alt=\"Заказать свой торт\">\r\n          <a href=\"index.php#feedback\">СОЗДАТЬ СВОЙ</a>\r\n        </div>\r\n      </div>\r\n    </div>\r\n  </section>'),
(4, 'adout', '<section class=\"about\">\r\n    <div class=\"about__wrap\">\r\n      <div class=\"about__text\">\r\n        <p>\r\n          Меня зовут Екатерина - я профессиональный кондитер и хозяйка Моей\r\n          Сладости.\r\n        </p>\r\n        <p>Мой девиз - честная и открытая работа, индивидуальный подход.</p>\r\n        <p>\r\n          В моих тортиках только натуральные ингредиенты и качественные\r\n          продукты.\r\n        </p>\r\n      </div>\r\n    </div>\r\n  </section>'),
(5, 'reviews', '<section class=\"reviews\">\r\n    <div class=\"content\">\r\n      <div class=\"review\">\r\n        <img src=\"/assets/img/reviews/review-1.jpg\" alt=\"review-1\">\r\n        <div class=\"review__text\">\r\n          <blockquote>\r\n            Внимательное отношение к клиенту, вкуснющий торт и некусачие цены! Огромное спасибо за удовольствие! Екатерине творческих успехов!\r\n          </blockquote>\r\n        </div>\r\n      </div>\r\n      <div class=\"review\">\r\n        <img src=\"/assets/img/reviews/review-2.jpg\" alt=\"review-2\">\r\n        <div class=\"review__text\">\r\n          <blockquote>\r\n            Торт натуральный, выполнен искусно и подложка лишь подчеркнула торжественное лакомство! Спасибо и успехов вашему бизнесу!\r\n          </blockquote>\r\n        </div>\r\n      </div>\r\n      <div class=\"review\">\r\n        <img src=\"/assets/img/reviews/review-3.jpg\" alt=\"review-3\">\r\n        <div class=\"review__text\">\r\n          <blockquote>\r\n            Изысканный вкус тортиков не оставил равнодушным ни одного сотрудника нашего Холдинга! Огромное спасибо за настроение, сладкую жизнь и превосходный вкус и дизайн Ваших тортов!\r\n          </blockquote>\r\n        </div>\r\n      </div>\r\n    </div>\r\n  </section>'),
(6, 'feedback', '<section class=\"feedback\" id=\"feedback\">\r\n    <div class=\"content\">\r\n      <h2>ГОТОВ К ЗАКАЗУ ИЛИ НЕ МОЖЕШЬ ОПРЕДЕЛИТЬСЯ? ЗВОНИ!</h2>\r\n      <a href=\"tel:+72223332255\">\r\n        <h3>+7 (222) 333-22-55</h3>\r\n      </a>\r\n      <h4>ИЛИ ОСТАВЬ НАМ СВОЙ НОМЕР И МЫ ПЕРЕЗВОНИМ САМИ</h4>\r\n\r\n\r\n      <form action=\"add_feedback.php\" method=\"post\" enctype=\"multipart/form-data\">\r\n        <div class=\"form__contact\">\r\n          <input type=\"text\" name=\"name\" placeholder=\"НАПИШИТЕ СВОЕ ИМЯ\" required pattern=\"[A-Za-zА-Яа-яЁё]{2,}\">\r\n          <input type=\"tel\" name=\"tel-number\" id=\"tel-number\" title=\" например, 89041041706\"\r\n            placeholder=\"НОМЕР ТЕЛЕФОНА\" required pattern=\"[0-9]{11}\">\r\n        </div>\r\n\r\n        <div class=\"form__download\">\r\n          ЕСТЬ ФОТО ПРИМЕРА?\r\n          <label for=\"download\">ЗАГРУЖАЙ ЗДЕСЬ</label>\r\n          <input type=\"file\" id=\"download\" name=\"download\" accept=\".png, .jpg, .jpeg\">\r\n        </div>\r\n\r\n        <div class=\"form__submit\">\r\n          <input type=\"checkbox\" name=\"personal\" id=\"personal\">\r\n          <label for=\"personal\">\r\n            Я СОГЛАСЕН НА ОБРАБОТКУ ПЕРСОНАЛЬНЫХ ДАННЫХ\r\n          </label>\r\n        </div>\r\n        <input type=\"submit\" value=\"ПООБЩАТЬСЯ\">\r\n      </form>\r\n    </div>\r\n  </section>'),
(7, 'map', '<section class=\"map\">\r\n    <div class=\"content\">\r\n      <h2>МЫ НА КАРТЕ</h2>\r\n      <iframe\r\n        src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1185.8234258591788!2d57.224571220779985!3d65.14509500553477!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4477316d89272b07%3A0x40a2e859a9bf1d6c!2z0JHRgNC40YHRgtC-0LvRjA!5e0!3m2!1sru!2sru!4v1644919434959!5m2!1sru!2sru\"\r\n         style=\"border: 0\" allowfullscreen=\"\" loading=\"lazy\"></iframe>\r\n      <h2>г.Печора, ул. Первомайская д.25</h2>\r\n    </div>\r\n  </section>');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cakes`
--
ALTER TABLE `cakes`
  ADD PRIMARY KEY (`id_cake`);

--
-- Индексы таблицы `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id_feedback`);

--
-- Индексы таблицы `fillings`
--
ALTER TABLE `fillings`
  ADD PRIMARY KEY (`id_filling`);

--
-- Индексы таблицы `index_page`
--
ALTER TABLE `index_page`
  ADD PRIMARY KEY (`id_element`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cakes`
--
ALTER TABLE `cakes`
  MODIFY `id_cake` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id_feedback` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `fillings`
--
ALTER TABLE `fillings`
  MODIFY `id_filling` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `index_page`
--
ALTER TABLE `index_page`
  MODIFY `id_element` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
