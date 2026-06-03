# Basalt Cheats — разбор решений (практики 3–9)

Ниже собрал, что именно реализовано, где находится, и какие строки смотреть.
Формат: **задача → файл → строки → кодовый блок**.

---

## Практика 3 — База данных и структура данных

### 3.1. Создание основных таблиц
- **Файл:** `site/database/basalt_store.sql`
- **Строки:** `22`, `35`, `50`, `117`

```sql
CREATE TABLE `games` (
CREATE TABLE `cheats` (
CREATE TABLE `key_plans` (
CREATE TABLE `payment_requests` (
```

### 3.2. Начальное заполнение и расширение каталога
- **Файл:** `site/database/basalt_store.sql`
- **Строки:** `142`, `153`, `176`, `197`

```sql
INSERT INTO `games` (...) VALUES
INSERT INTO `cheats` (...) VALUES
INSERT INTO `key_plans` (...) VALUES
-- Extra plans for extended cheat list
```

### 3.3. Подключение к БД в приложении
- **Файл:** `site/connect.php`
- **Строки:** `11`, `25`

```php
$mysqli = @new mysqli(...);
$mysqli->set_charset('utf8mb4');
```

---

## Практика 4 — Каталог и цепочка покупки

### 4.1. Поиск по карточкам игр (не по читам)
- **Файл:** `site/games.php`
- **Строки:** `45`, `47`, `65`

```php
<input id="catalog-search" type="search" ...>
<article class="... ws-game-card" data-search="<?= h($blob) ?>">
```

### 4.2. Переход игра → читы → тарифы
- **Файл:** `site/cheats.php`
- **Строки:** `70`
- **Файл:** `site/plans.php`
- **Строки:** `58`

```php
url_to('plans.php', ['game' => $gameSlug, 'cheat' => $c['slug']])
url_to('checkout.php', ['game' => $gameSlug, 'cheat' => $cheatSlug, 'plan' => ...])
```

### 4.3. Checkout с уникальным payload
- **Файл:** `site/checkout.php`
- **Строки:** `36`, `89`

```php
$payload = 'BASALT-PAY:' . ... . $_SESSION['pay_qr'];
<form ... action="<?= h(asset('actions/payment.php')) ?>">
```

---

## Практика 5 — Навигация, локализация, структура страниц

### 5.1. Мультиязык и конфиг
- **Файл:** `site/bootstrap.php`
- **Строки:** `17`, `119`, `140`

```php
function site_config(): array
function fix_db_hrefs(string $html): string
function nav_active_id(): string
```

### 5.2. Логотип в navbar (без текста) и footer-бренд
- **Файл:** `site/components/header.php`
- **Строки:** `42-43`
- **Файл:** `site/components/footer.php`
- **Строки:** `23-26`

```php
<a class="brand-mark" ...>
  <img class="brand-logo" ...>
</a>

<a class="footer-logo footer-logo-link" href="<?= h(asset('index.php')) ?>">
  <img class="brand-logo sm" ...>
  <strong>Basalt Cheats</strong>
</a>
```

### 5.3. Breadcrumbs для блога
- **Файл:** `site/chronicle.php`
- **Строки:** блок breadcrumbs в начале контейнера

```php
$breadcrumbs = [
    ['label' => __('nav.blog'), 'href' => null],
];
require COMPONENTS_PATH . '/breadcrumbs.php';
```

---

## Практика 6 — Формы и UX формы

### 6.1. Фиксированный размер textarea
- **Файл:** `site/assets/css/forms.css`
- **Строки:** блоки `textarea { resize: none; }`

```css
.form-grid textarea {
  resize: none;
}

.validation-lab textarea {
  resize: none;
}
```

### 6.2. Форма оплаты и поля подтверждения
- **Файл:** `site/checkout.php`
- **Строки:** форма `form-grid` + `paid_ack`

```php
<form class="card card-hover form-grid" ...>
...
<input type="checkbox" name="paid_ack" value="1" id="paid_ack">
```

---

## Практика 7 — JS-интерактив

### 7.1. Drawer в navbar
- **Файл:** `site/assets/js/main.js`
- **Строки:** `1-15`

```js
const btn = document.getElementById("nav-more");
const drawer = document.getElementById("nav-drawer");
```

### 7.2. Live-поиск карточек
- **Файл:** `site/assets/js/main.js`
- **Строки:** `17-35`

```js
const input = document.getElementById("catalog-search");
const grid = document.getElementById("catalog-grid");
el.classList.toggle("ws-hidden", !show);
```

### 7.3. FAQ: только один открытый пункт
- **Файл:** `site/assets/js/main.js`
- **Строки:** `38`, `42`

```js
const accordion = document.getElementById("faq-accordion");
el.addEventListener("toggle", () => {
  if (!el.open) return;
  details.forEach((other) => { if (other !== el) other.open = false; });
});
```

### 7.4. Ограничения на копирование через UI
- **Файл:** `site/assets/js/main.js`
- **Строки:** `52-53`

```js
document.addEventListener("contextmenu", (e) => e.preventDefault(), { capture: true });
document.addEventListener("dragstart", ...);
```

---

## Практика 8 — CSS/визуал/интерактивные блоки

### 8.1. Неоновые outline-кнопки (hover без ресайза)
- **Файл:** `site/assets/css/components.css`
- **Строки:** `61`, `68`

```css
.btn-primary { ... outline-style ... }
.btn-primary:hover { ... мягкая подсветка ... }
```

### 8.2. Шире карточки галереи
- **Файл:** `site/assets/css/components.css`
- **Строки:** `142`

```css
.gallery-grid {
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
}
```

### 8.3. UNDETECT badge в карточках читов
- **Файл:** `site/cheats.php`
- **Строки:** `72`
- **Файл:** `site/assets/css/components.css`
- **Строки:** `88`, `106`

```php
<span class="undetect-badge"><i></i>UNDETECT</span>
```

```css
.undetect-badge { ... }
.undetect-badge i { ... }
```

### 8.4. Главная: 4 hero-карточки, trust-pill, бегущая статистика
- **Файл:** `site/index.php`
- **Строки:** `25-41`, `56-85`
- **Файл:** `site/assets/css/pages.css`
- **Строки:** `510`, `546`, `575`, `606`

```php
<div class="home-trust-row">...</div>
<div class="hero-quad hero-quad-links">...</div>
<div class="stats-loop-track">...</div>
```

```css
.home-trust-row { ... }
.hero-quad-card { ... }
.hero-quad-card:hover { ... }
.stats-loop-item strong { ... }
```

### 8.5. Отзывы: скролл внутри текста + тег/дата внизу
- **Файл:** `site/reviews.php`
- **Строки:** блоки `review-body`, `review-foot`
- **Файл:** `site/assets/css/pages.css`
- **Строки:** `749`, `755`

```php
<div class="review-body"><p>...</p></div>
<div class="review-foot"><span class="review-tag">...</span><span class="muted">...</span></div>
```

```css
.review-body { max-height: 120px; overflow: auto; }
.review-foot { ... }
```

---

## Практика 9 — Интеграция и системные элементы

### 9.1. FAQ: блок легитности (компания + адрес + карта)
- **Файл:** `site/faq.php`
- **Строки:** `12-14`, `42`, `64`, `71`, `78`

```php
$name = ... company_name ...
$inn = ... company_inn ...
$addr = ... company_address ...
<div class="ws-accordion" id="faq-accordion">
<span>Scam?</span>
<div class="company-block">...</div>
<div class="map-frame map-frame-faq"><iframe ...></iframe></div>
```

### 9.2. Footer: GitHub рядом с соцсетями
- **Файл:** `site/components/footer.php`
- **Строки:** `9`, `55`

```php
$gh = trim((string) ($c['github_url'] ?? ''));
<li><a href="<?= h($gh) ?>" ...>GitHub</a></li>
```

### 9.3. Конфиг для GitHub/логотипа
- **Файл:** `site/config/site.yaml`
- **Строки:** ключи `logo_path`, `github_url`

```yaml
logo_path: assets/img/logo.png
github_url:
```

### 9.4. Глобальные UX-ограничения + кастомный скроллбар
- **Файл:** `site/assets/css/base.css`
- **Строки:** `12`, `22-37`
- **Файл:** `site/assets/css/reset.css`
- **Строки:** `15`, `17`

```css
body { user-select: none; }
::-webkit-scrollbar { ... }
::-webkit-scrollbar-thumb { ... }
img { -webkit-user-drag: none; pointer-events: none; }
```

---

## Практика 10 — БД (изолированная + Sweetty)

### 10.1. Чтение/запись на странице Basalt
- **Файл:** `site/practice10.php`, `site/actions/practice10-save.php`
- **БД:** `basalt_practice10` (`site/database/practice10.sql`, `site/connect_practice.php`)

### 10.2. Sweetty 7.10 — вывод из БД
- **Обёртка:** `site/practice10-sweetty-710.php` (iframe)
- **Сайт:** `site/practice10-sweetty/index.php` → `index_page`
- **БД:** `db_sweetty` (`site/database/db_sweetty.sql`, `site/config/database_sweetty.php`)

### 10.3. Sweetty 7.9 — запись в БД
- **Обёртка:** `site/practice10-sweetty-79.php`
- **Сайт:** `site/practice10-sweetty-79/` (форма → `feedback`)
- **БД:** та же `db_sweetty`

---

## Практика 11 — «День Победы» (побочный мини-сайт)

Тематика не встроена в витрину читов.

| Задача | Файл |
|--------|------|
| Главная: navbar, баннер, история, карусель, герой, превью городов | `site/practice11-victory/index.php` |
| Все города-герои (сетка 2 кол.) | `site/practice11-victory/hero-city.php` |
| Данные слайдов и городов | `site/practice11-victory/data.php` |
| Обёртки Basalt | `site/practice11.php`, `site/practice11-cities.php` |
| Стили | `site/practice11-victory/assets/style/style.css` |

---

## Практика 12 — Async «Моя сладость» (побочный мини-сайт)

| Задача | Файл |
|--------|------|
| SPA-оболочка + fetch фрагментов | `site/practice12-async/index.html`, `assets/script/async.js` |
| Исправление URL фрагмента | `fetch(\`async/${alias}/index.html\`)` |
| Обёртка Basalt | `site/practice12-async.php` |

---

## Практика 13 — Музыкальный каталог (побочный мини-сайт + БД)

| Задача | Файл |
|--------|------|
| Схема и seed | `site/database/practice13.sql` |
| Подключение | `site/connect_practice13.php`, `site/config/database_practice13.php` |
| Группы | `site/practice13-music/index.php` |
| Альбомы (все / по группе) | `site/practice13-music/albums.php?group=` |
| Треки (все / по альбому) | `site/practice13-music/tracks.php?album=` |
| Хаб | `site/practice13.php` |

---

## Примечание для защиты

Если преподаватель просит «почему так», основной аргумент:
- проект собран по цепочке **каталог → модуль → срок → checkout**;
- данные хранятся в нормализованной схеме (`games`, `cheats`, `key_plans`, `payment_requests`);
- UI реализует UX-требования: фильтрация, single-open FAQ, расширенные отзывы, fallback/конфиг;
- все изменения привязаны к конкретным файлам и строкам выше.
