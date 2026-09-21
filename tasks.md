# Соответствие `/official` материалам «Задания практик»

Корень материалов: [`c:\xampp\htdocs\Задания практик`](../Задания%20практик).

**Резервная копия** этой ветки на момент работ: `c:\xampp\htdocs\official-backup-2026-04-21_0-52` (копия каталога `official/`, без удаления оригинала).

## ПР6 — HTML-валидация формы

| | |
|--|--|
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
| **В official** | Побочный мини-сайт: [`site/practice11-victory/`](site/practice11-victory/), обёртки [`practice11.php`](site/practice11.php), [`practice11-cities.php`](site/practice11-cities.php) |
| **На витрину** | Не переносится (другая тематика) |

---

## ПР12 — Async «Моя сладость» (7.13)

| | |
|--|--|
| **В official** | [`site/practice12-async/`](site/practice12-async/), [`practice12-async.php`](site/practice12-async.php) |
| **На витрину** | Не переносится |

---

## ПР13 — Группы / альбомы / треки (MySQL)

| | |
|--|--|
| **В official** | БД `basalt_practice13`, [`site/practice13-music/`](site/practice13-music/), [`practice13.php`](site/practice13.php) |
| **На витрину** | Не переносится; отдельная БД |

---

## ПР13 — Группы / альбомы / треки (MySQL)

| | |
|--|--|
| **В official** | [`site/practice14-express/`](site/practice14-express/), [`practice14-express.php`](site/practice14-express.php) |
| **На витрину** | Не переносится |

---

## Навигация «⋯» (актуально)

ПР8–ПР9 лаборатории + ПР10–ПР14 — см. [`site/components/header.php`](site/components/header.php) (`.nav-secondary-links`).
