# Basalt Cheats (official)

Многостраничная витрина магазина приватных читов: **PHP + MySQL**, RU/EN, покупка по цепочке **игры → модули → планы → checkout**.

**Stack:** PHP 8.x (XAMPP / Apache), MariaDB/MySQL, vanilla JS (точечно), CSS.

**Мобильные устройства:** витрина и практики под `site/` редиректят на [`site/mobile-blocked.php`](site/mobile-blocked.php). Для локальной проверки с эмуляцией телефона в DevTools: `?desktop=1` (cookie на 24 ч).

**DevTools:** client-side guard в [`site/assets/js/devtools-guard.js`](site/assets/js/devtools-guard.js) (горячие клавиши, ПКМ, overlay при открытой панели). Полная блокировка в браузере невозможна при отключённом JS.

---

## Quick start (XAMPP)

1. DocumentRoot или alias на [`site/`](site/) (типичный URL: `http://localhost/new-age/official/site/`).
2. Импорт SQL (phpMyAdmin или CLI): [`basalt_cheats.sql`](site/database/basalt_cheats.sql)
3. Настройки подключения: [`site/config/database.php`](site/config/database.php) и при необходимости `DB_*` в окружении.
4. Base path для подпапки Apache: [`site/config/site.yaml`](site/config/site.yaml) → `web_base`.

### Базы данных

| БД | Назначение | Config |
|----|------------|--------|
| `basalt_cheats` | Каталог, заказы, контент | `database.php` |

---

## Docker

Из корня `github/`:

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
├── docker-compose.yml
├── Makefile
├── tasks.md
└── IMAGES.md
```

---

## Documentation

- [`IMAGES.md`](IMAGES.md) — пути к изображениям
- `DEVELOPMENT.md`, `WORK_PLAN.md`, `CHANGELOG.md`

---

## License

MIT — см. `LICENSE`.
