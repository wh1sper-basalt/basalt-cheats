# Ход разработки

## Структура репозитория

- В **`site/`** — исполняемый PHP-проект (DocumentRoot).
- В **корне** — Docker, Makefile, CI, markdown-доки, `deploy/`.

## Визуал

- Референс — сохраненный снимок WRONGCHEATS (`.mhtml` в родительской папке): `min-h-screen`, плавающие орбы, фиксированная шапка с pill-навигацией, hero в две колонки.
- Палитра **монохром** (ч/б, серые уровни), без цветного неона; акцент — белый/светло-серый и тени.
- Анимации: `animations.css` (fade-in-up, float-orb), длительные `transition` на карточках и кнопках, выраженный `:hover` (подъем, тень, border).

## Данные и оплата

- Магазин: `games` → `cheats` → `key_plans`; оплата только на `checkout.php` с валидной тройкой в query.
- `payment_requests` хранит `game_id`, `cheat_id`, `key_plan_id` плюс контакты и QR payload.
- Карта и «не скам» — только на **`faq.php`** в раскрывающемся `<details>` «Скам?»; реквизиты из `site/config/site.yaml`.

## Вспомогательное

- `fix_db_hrefs()` в `bootstrap.php` — подмена `href="games.php"` и т.п. на пути с учетом `web_base` при выводе главной из БД.

## Практики WordPress (WP1–WP5)

В отличие от практик 6–15, где решение живет внутри `site/`, WordPress-практики
разворачиваются **отдельной установкой** вне репозитория.

### Архитектура

- WordPress: `c:\xampp\htdocs\wp-lab\` (свой DocumentRoot, свой `wp-config.php`).
- БД: `wp_lab` — **не входит** в `site/database/FULL.sql`, создается вручную.
- Дочерняя тема: `wp-content/themes/my-child-theme/` — в ней `style.css` и
  `functions.php` со всеми хуками WooCommerce и bbPress.
- Обертки в проекте: `site/practice-wp1.php` … `site/practice-wp5.php` —
  те же табы-панели, что в `practice13.php` (`btn btn-primary` / `btn btn-ghost`,
  `role="tab"`, `data-wp-panel` / `data-wp-src`), но iframe ведет на
  `http://localhost/wp-lab/`.

### Почему не внутри `site/`

WordPress и PHP-оболочка Basalt сосуществуют на одном Apache, но в разных
папках, чтобы:
1. Не смешивать CMS-роутинг (`/wp-admin/`, `/shop/`, `/forums/`) с классическими
   скриптами `site/*.php`.
2. Не тянуть WordPress-зависимости (wp-core, WooCommerce, bbPress) в репозиторий.
3. Сохранить совместимость с Docker Compose — образ остается PHP-минимальным.

### Локализация

Ключи в `site/lang/ru.php` и `site/lang/en.php` с префиксом `practice_wp1.*` ...
`practice_wp5.*` + `nav.practice_wp1` ... `nav.practice_wp5`.