# Соответствие `/official` материалам «Задания практик»

Корень материалов: [`c:\xampp\htdocs\Задания практик`](../Задания%20практик).  
Упрощённая учебная копия с отдельной сверкой: [`new-age/edu/tasks.md`](../edu/tasks.md).

**Резервная копия** этой ветки на момент работ: `c:\xampp\htdocs\official-backup-2026-04-21_0-52` (копия каталога `official/`, без удаления оригинала).

---

## Примечания

1. **ПР3 в папке не найдена** — в методичке карта Google встречается в **ПР9** (дамп `db_sweetty.sql`, блок `map` в `index_page`).
2. **Тема проекта** — Basalt Cheats, не торты и не «сайт о Печоре»; учебные идеи перенесены на витрину читов.
3. **Живая главная** [`site/index.php`](site/index.php) — кастомная вёрстка + запросы к `games` / `os` и т.д. Таблица [`index_page`](site/database/basalt_cheats.sql) хранит HTML-блоки по смыслу ПР9; чтобы **не дублировать** тот же контент на `index.php` и в БД одновременно на одной странице, полный вывод строк `index_page` вынесен на отдельную страницу [`site/index-db-blocks.php`](site/index-db-blocks.php) (меню «⋯» → «Блоки главной (БД)»).

---

## ПР6 — HTML-валидация формы

| | |
|--|--|
| **Источник** | [`ПР 6/2.40 Валидация формы/validate.html`](../Задания%20практик/ПР%206/2.40%20Валидация%20формы/validate.html) |
| **Суть** | `pattern`, `required`, `min`/`max`, radio/select по умолчанию, `reset`/`submit`. |
| **Решение в official** | [`site/validation-lab.php`](site/validation-lab.php) — отдельная лаборатория; ссылка в выпадающем меню ([`site/components/header.php`](site/components/header.php), `#nav-drawer`). |
| **Эквивалент** | Тексты полей локализованы (RU/EN), логика совпадает с эталоном. |

**Фрагмент (пример строк 18–40):**

```18:40:c:\xampp\htdocs\official\site\validation-lab.php
    <form action="#" method="get" onsubmit="return false;">
        <h2><?= $ru ? 'Представьтесь' : 'Introduce yourself' ?></h2>
        <label for="name"><?= $ru
            ? 'Имя (кириллица, пробел, дефис; 2–10 символов)'
            : 'Name (Cyrillic, space, hyphen; 2–10 chars)' ?></label>
        <input id="name" type="text" required pattern="[а-яА-ЯёЁ\-\s]{2,10}" placeholder="Иван">
        ...
        <label><input type="radio" name="sex" value="f" checked> <?= $ru ? 'женский' : 'female' ?></label>
```

---

## ПР8 — примеры PHP / JS

| | |
|--|--|
| **Источник** | [`ПР 8/7.2 Примеры кода/.../example_1.php` … `example_4.php`](../Задания%20практик/ПР%208/7.2%20Примеры%20кода/7.2%20Примеры%20кода/) |
| **Суть** | Вставка PHP (`echo`, `print`, комментарии) и клиентского JS. |
| **Решение** | [`site/runtime-demo.php`](site/runtime-demo.php) — объединённое демо; пароли из эталона **не** копируются, вместо них нейтральные `login`/`role`. |

**Фрагмент (строки 34–48):**

```34:48:c:\xampp\htdocs\official\site\runtime-demo.php
    <div class="panel">
        <h2><?= $ru ? 'Серверный PHP' : 'Server PHP' ?></h2>
        <?php
        echo '<p>Hello, User!</p>';
        print 'print() without parens<br>';
        ...
        echo '<p><strong>' . ($ru ? 'Сессия' : 'Session') . '</strong><br>'
            . ($ru ? 'Логин' : 'Login') . ': ' . htmlspecialchars($login, ENT_QUOTES, 'UTF-8')
```

---

## ПР8 — несколько `iframe` (лабораторная сетка)

| | |
|--|--|
| **Источник** | идея разнесённых фреймов в курсе / консоль |
| **Решение** | [`site/support-console.php`](site/support-console.php) + [`site/frames/*.php`](site/frames) — четыре статичных `iframe` с микро-страницами поддержки. |

**Фрагмент (строки 18–41):**

```18:41:c:\xampp\htdocs\official\site\support-console.php
<div class="container console-grid">
    <div>
        ...
            <iframe title="docs" src="<?= h($base) ?>/docs.php?lang=<?= h(current_lang()) ?>"></iframe>
```

---

## ПР8 — `city-today.html` (ссылки `target` → именованный `iframe`)

| | |
|--|--|
| **Источник** | [`.../Сайт о Печоре v-3/city-today.html`](../Задания%20практик/ПР%208/7.4%20Самостоятельная%20работа/Сайт%20о%20Печоре%20v-3/city-today.html) |
| **Суть** | Клик по ссылке с `target` подменяет контент во фрейме с тем же `name`. |
| **Решение** | [`site/iframe-target-lab.php`](site/iframe-target-lab.php) + [`site/frames/target-lab.php`](site/frames/target-lab.php). Ссылка в `#nav-drawer`. |

**Фрагмент (строки 18–28):**

```18:28:c:\xampp\htdocs\official\site\iframe-target-lab.php
    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:14px;">
        <a class="btn btn-ghost" href="<?= h($urlA) ?>" target="basalt_city_iframe"><?= $ru ? 'Загрузить слот A' : 'Load slot A' ?></a>
        <a class="btn btn-ghost" href="<?= h($urlB) ?>" target="basalt_city_iframe"><?= $ru ? 'Загрузить слот B' : 'Load slot B' ?></a>
    </div>
    <iframe
        ...
        name="basalt_city_iframe"
        src="<?= h($urlA) ?>"
```

---

## ПР9 — БД, `connect.php`, вывод контента

| | |
|--|--|
| **Источник** | [`ПР9/7.6/.../info.txt`](../Задания%20практик/ПР9/7.6%20Вывод%20данных%20из%20базы.%20Страница%20index.php/info.txt), эталон [`sweetty/index.php`](../Задания%20практик/ПР9/7.6%20Вывод%20данных%20из%20базы.%20Страница%20index.php/sweetty/index.php) |
| **Суть** | Таблица с HTML, подключение, вывод на странице. |
| **Подключение** | [`site/connect.php`](site/connect.php) (`mysqli`) |
| **Вывод всех блоков `index_page`** | [`site/index-db-blocks.php`](site/index-db-blocks.php) — цикл по `id_element`, поле `content_ru` / `content_en` + [`fix_db_hrefs()`](site/bootstrap.php) для подпапок. |

**Фрагмент запроса и вывода (строки 19–37):**

```19:37:c:\xampp\htdocs\official\site\index-db-blocks.php
$res = $mysqli->query('SELECT `id_element`, `alias`, `' . $col . '` AS body FROM `index_page` ORDER BY `id_element` ASC');
...
        <article class="index-db-block" data-alias="<?= h((string) ($block['alias'] ?? '')) ?>">
            <?= fix_db_hrefs((string) ($block['body'] ?? '')) ?>
        </article>
```

### Карта Google (как в дампе курса, блок `map`)

| | |
|--|--|
| **Источник** | `index_page` + `alias='map'` в [`db_sweetty.sql`](../Задания%20практик/ПР9/7.6%20Вывод%20данных%20из%20базы.%20Страница%20index.php/sweetty/!!dump/db_sweetty.sql) |
| **В official** | Строка `id_element=6`, `alias=map` в [`site/database/basalt_cheats.sql`](site/database/basalt_cheats.sql); отображается на [`index-db-blocks.php`](site/index-db-blocks.php). |
| **Дополнительно** | Полноэкранная карта/контекст — [`site/faq.php`](site/faq.php) (отдельно от `index_page`). |

**Существующая БД без строки `map`:** переимпортируйте [`site/database/FULL.sql`](site/database/FULL.sql) или [`basalt_cheats.sql`](site/database/basalt_cheats.sql).

---

## ПР9 — «торты и начинки» (роль данных)

| Курс | Official (эквивалент) |
|------|------------------------|
| Каталог тортов | [`games.php`](site/games.php) |
| Начинки / позиции | [`cheats.php`](site/cheats.php), [`plans.php`](site/plans.php) |
| Оформление | [`checkout.php`](site/checkout.php) |

[`cakes.txt`](../Задания%20практик/cakes.txt) в код не копировался; наполнение витрины — игры и модули в [`basalt_cheats.sql`](site/database/basalt_cheats.sql).

---

## ПР8 — многостраничный сайт «Печора» (полный HTML-набор)

| | |
|--|--|
| **Источник** | папка [`Сайт о Печоре v-3`](../Задания%20практик/ПР%208/7.4%20Самостоятельная%20работа/Сайт%20о%20Печоре%20v-3) |
| **В official** | **Нет** полного зеркала всех HTML-файлов. Эквивалент по охвату разделов: `reviews.php`, `chronicle.php`, `gallery.php`, `feedback.php`, `team.php`, `regions.php`, `operations.php`, `history.php`, плюс лаборатории в меню «⋯». |

---

## Навигация: выпадающий список («⋯»)

Ссылки задаются в [`site/components/header.php`](site/components/header.php) (`#nav-drawer`). Актуальный набор включает в том числе:

- Блоки главной (БД) → `index-db-blocks.php`
- ПР8 iframe → `iframe-target-lab.php`
- Валидация → `validation-lab.php`
- PHP-демо → `runtime-demo.php`
- Консоль → `support-console.php`
- плюс галерея, команда, регионы, операции, история

---

## Обновление БД после изменений в SQL

Полный импорт всего проекта: [`site/database/FULL.sql`](site/database/FULL.sql).  
Поштучно: `basalt_cheats.sql`, `practice10.sql`, `db_sweetty.sql`, `practice13.sql`.

---

## ПР11 — «День Победы» (Bootstrap)

| | |
|--|--|
| **Источник** | [`Задания практики 11/ЗАДАНИЕ.txt`](../Задания%20практик/Задания%20практики%2011/ЗАДАНИЕ.txt), контент карусели и городов в `media/` |
| **В official** | Побочный мини-сайт: [`site/practice11-victory/`](site/practice11-victory/), обёртки [`practice11.php`](site/practice11.php), [`practice11-cities.php`](site/practice11-cities.php) |
| **На витрину** | Не переносится (другая тематика) |

---

## ПР12 — Async «Моя сладость» (7.13)

| | |
|--|--|
| **Источник** | [`Задания практики 12/async_load/`](../Задания%20практик/Задания%20практики%2012/async_load), [`ЗАДАНИЕ.txt`](../Задания%20практик/Задания%20практики%2012/ЗАДАНИЕ.txt) |
| **В official** | [`site/practice12-async/`](site/practice12-async/), [`practice12-async.php`](site/practice12-async.php) |
| **На витрину** | Не переносится |

---

## ПР13 — Группы / альбомы / треки (MySQL)

| | |
|--|--|
| **Источник** | [`Задания практики 13/ЗАДАНИЕ.md`](../Задания%20практик/Задания%20практики%2013/ЗАДАНИЕ.md) |
| **В official** | БД `basalt_practice13`, [`site/practice13-music/`](site/practice13-music/), [`practice13.php`](site/practice13.php) |
| **На витрину** | Не переносится; отдельная БД |

---

## Навигация «⋯» (актуально)

ПР8–ПР9 лаборатории + ПР10–ПР13 — см. [`site/components/header.php`](site/components/header.php) (`.nav-secondary-links`).
