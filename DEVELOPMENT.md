# Ход разработки

## Структура репозитория

- В **`site/`** — исполняемый PHP-проект (DocumentRoot).
- В **корне** — Docker, Makefile, CI, markdown-доки, `deploy/`.

## Визуал

- Референс — сохранённый снимок WRONGCHEATS (`.mhtml` в родительской папке): `min-h-screen`, плавающие орбы, фиксированная шапка с pill-навигацией, hero в две колонки.
- Палитра **строго монохром** (ч/б, серые уровни), без цветного неона; акцент — белый/светло-серый и тени.
- Анимации: `animations.css` (fade-in-up, float-orb), длительные `transition` на карточках и кнопках, выраженный `:hover` (подъём, тень, border).

## Данные и оплата

- Магазин: `games` → `cheats` → `key_plans`; оплата только на `checkout.php` с валидной тройкой в query.
- `payment_requests` хранит `game_id`, `cheat_id`, `key_plan_id` плюс контакты и QR payload.
- Карта и «не скам» — только на **`faq.php`** в раскрывающемся `<details>` «Скам?»; реквизиты из `site/config/site.yaml`.

## Вспомогательное

- `fix_db_hrefs()` в `bootstrap.php` — подмена `href="games.php"` и т.п. на пути с учётом `web_base` при выводе главной из БД.
