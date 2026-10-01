# Basalt Cheats (education version)

Многостраничная витрина магазина приватных читов: **PHP + MySQL**, RU/EN, покупка по цепочке **игры → модули → планы → checkout**.

**Stack:** PHP 8.x (XAMPP / Apache), MariaDB/MySQL, vanilla JS (точечно), CSS.

**Мобильные устройства:** витрина и практики под `site/` редиректят на [`site/mobile-blocked.php`](site/mobile-blocked.php). Для локальной проверки с эмуляцией телефона в DevTools: `?desktop=1` (cookie на 24 ч).

**DevTools:** client-side guard в [`site/assets/js/devtools-guard.js`](site/assets/js/devtools-guard.js) (горячие клавиши, ПКМ, overlay при открытой панели). Полная блокировка в браузере невозможна при отключенном JS.

---

## Quick start (XAMPP)

1. DocumentRoot или alias на [`site/`](site/) (типичный URL: `http://localhost/new-age/official/site/`).
2. Импорт SQL (phpMyAdmin или CLI):
   - **Все сразу:** [`site/database/FULL.sql`](site/database/FULL.sql) — `basalt_cheats` + практики (рекомендуется)
   - **Поштучно:** [`basalt_cheats.sql`](site/database/basalt_cheats.sql), [`practice10.sql`](site/database/practice10.sql), [`db_sweetty.sql`](site/database/db_sweetty.sql), [`practice13.sql`](site/database/practice13.sql)
3. Настройки подключения: [`site/config/database.php`](site/config/database.php) и при необходимости `DB_*` в окружении.
4. Base path для подпапки Apache: [`site/config/site.yaml`](site/config/site.yaml) → `web_base`.

### Базы данных

| БД | Назначение | Config |
|----|------------|--------|
| `basalt_cheats` | Каталог, заказы, контент | `database.php` |
| `basalt_practice10` | Страница `practice10.php` | `database_practice.php` |
| `db_sweetty` | iframe Sweetty 7.9 / 7.10 | `database_sweetty.php` |
| `basalt_practice13` | Музыкальный каталог ПР13 | `database_practice13.php` |
| `wp_lab` | Отдельная установка WordPress для практик WP1–WP5 | `wp-config.php` (вне репо) |

### Практики (меню «⋯»)

- ПР10: `practice10.php`, Sweetty 7.9 / 7.10
- ПР11: `practice11.php`, `practice11-cities.php` (мини-сайт «День Победы»)
- ПР12: `practice12-async.php` (async «Моя сладость»)
- ПР13: `practice13.php`, `practice13-music/*` (группы / альбомы / треки)
- ПР14: `practice14.php`, `practice14-express/*` (Node.js, GET & POST requests)
- ПР15: `practice15.php`, `practice15-express/*` (Node.js, register, login, dashboard)
- WP1: `practice-wp1.php` (WordPress: установка, контент, дочерняя тема)
- WP2: `practice-wp2.php` (Landing Page, якоря, smooth scroll)
- WP3: `practice-wp3.php` (WooCommerce: каталог, склад, цены «от X»)
- WP4: `practice-wp4.php` (Checkout: кастомное поле, PDF-инвойс)
- WP5: `practice-wp5.php` (bbPress: форум, мета-поле темы)

```text
WordPress для WP1–WP5 разворачивается отдельно: `c:\xampp\htdocs\wp-lab\`,
БД `wp_lab` (создать вручную), дочерняя тема `my-child-theme`.
Обертки — iframe на `http://localhost/wp-lab/`, как у ПР14/ПР15.
```

---

## Docker

Из корня `basalt-cheats/`:

```bash
docker compose up -d --build
make import-sql
```

---

## WordPress-практики (WP1–WP5)

Практики WP1–WP5 требуют **отдельной установки WordPress** вне репозитория.
Обертки `site/practice-wp{1..5}.php` используют iframe на локальный WP.

В директории basalt-cheats/wp-lab/ для ознакомления оставил только две директории:

| Директория | Значение |
|------------|----------|
| `basalt-cheats\wp-lab\wp-content\plugins\` | Оставил три директории основных плагинов (самих плагинов нет, только их названия и readme.txt для уменьшения объема проекта репозитория GitHub – для выполнения практических работ WP1-WP5 |
| `basalt-cheats\wp-lab\wp-content\themes\` | Оставил `...\my-child-theme\` (`functions.php`, `style.css`), необходимую для выполнения практических работ |

### 1. Создайте БД

В phpMyAdmin или через MySQL CLI:

```sql
CREATE DATABASE wp_lab CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Установите WordPress

* Скачать: https://ru.wordpress.org/download/
* Распаковать в c:\xampp\htdocs\wp-lab\
* Открыть http://localhost/wp-lab/, пройти мастер установки:
    * БД: wp_lab
    * Пользователь MySQL: root (пароль пустой — дефолт XAMPP)
    * Префикс таблиц: wp_
* Настройки → Постоянные ссылки → выбрать «Название записи» (/%postname%/).

### 3. Плагины

Активировать по порядку:
| Практика | Плагин |
|----------|--------|
| WP3, WP4 | WooCommerce |
| WP4 | PDF Invoices & Packing Slips for WooCommerce |
| WP5 | bbPress |

### 4. Дочерняя тема

Скопировать `my-child-theme/` из репозитория в
`wp-lab/wp-content/themes/my-child-theme/` и активировать в
**Внешний вид → Темы**.

### 5. Открыть обертки

- `http://localhost/site/practice-wp1.php` — WordPress Basics
- `http://localhost/site/practice-wp2.php` — Landing Page
- `http://localhost/site/practice-wp3.php` — WooCommerce
- `http://localhost/site/practice-wp4.php` — Checkout
- `http://localhost/site/practice-wp5.php` — bbPress

---

## Commands

```bash
make lint
```

`php -l` для всех `site/**/*.php`.

---

## Project layout

```
edu/
├── site/                   # DocumentRoot
│   ├── assets/
│   ├── components/
│   ├── actions/
│   ├── config/
│   ├── database/
│   ├── practice10-sweetty/
│   ├── practice10-sweetty-79/
│   ├── practice11-victory/
│   ├── practice12-async/
│   ├── practice13-music/
│   ├── practice13-express/
│   ├── practice14-express/
│   └── practice15-express/
├── docker-compose.yml
├── Makefile
├── tasks.md
├── PRACTICE_SOLUTIONS.md
└── IMAGES.md
```

---

## Documentation

- [`tasks.md`](tasks.md) — сверка с «Задания практик»
- [`PRACTICE_SOLUTIONS.md`](PRACTICE_SOLUTIONS.md) — где лежит код по практикам
- [`IMAGES.md`](IMAGES.md) — пути к изображениям
- `DEVELOPMENT.md`, `WORK_PLAN.md`, `CHANGELOG.md`

---

## License

MIT LICENSE.
