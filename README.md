# Basalt Cheats (education version)

Многостраничная витрина магазина приватных читов: **PHP + MySQL**, RU/EN, покупка по цепочке **игры → модули → планы → checkout**.

**Stack:** PHP 8.x (XAMPP / Apache), MariaDB/MySQL, vanilla JS (точечно), CSS.

**Мобильные устройства:** витрина и практики под `site/` редиректят на [`site/mobile-blocked.php`](site/mobile-blocked.php). Для локальной проверки с эмуляцией телефона в DevTools: `?desktop=1` (cookie на 24 ч).

**DevTools:** client-side guard в [`site/assets/js/devtools-guard.js`](site/assets/js/devtools-guard.js) (горячие клавиши, ПКМ, overlay при открытой панели). Полная блокировка в браузере невозможна при отключённом JS.

---

## Quick start (XAMPP)

1. DocumentRoot или alias на [`site/`](site/) (типичный URL: `http://localhost/new-age/official/site/`).
2. Импорт SQL (phpMyAdmin или CLI):
   - **Всё сразу:** [`site/database/FULL.sql`](site/database/FULL.sql) — `basalt_cheats` + практики (рекомендуется)
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

### Практики (меню «⋯»)

- ПР10: `practice10.php`, Sweetty 7.9 / 7.10
- ПР11: `practice11.php`, `practice11-cities.php` (мини-сайт «День Победы»)
- ПР12: `practice12-async.php` (async «Моя сладость»)
- ПР13: `practice13.php`, `practice13-music/*` (группы / альбомы / треки)

---

## Docker

Из корня `official/`:

```bash
docker compose up -d --build
make import-sql
```

---

## Commands

```bash
make lint
```

`php -l` для всех `site/**/*.php`.

---

## Project layout

```
official/
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
│   └── practice13-music/
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
