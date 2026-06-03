# Изображения (полный перечень)

Все пути **относительно каталога `site/`**, если не указано иное. В SQL и PHP используйте пути без ведущего `/`.

**Конвенция:** обложки каталога — **WebP**; альбомы на странице покупки (`cheat.php`) — только **PNG 1920×1080**, соотношение **16:9** (`assets/img/cheats/albums/{slug}-01.png` … `-03.png`). Файлы могут отсутствовать в репозитории до загрузки дизайнером.

---

## Фон, бренд, UI

| Файл | Назначение |
|------|------------|
| `assets/img/components/background.webp` | Фон страницы (`.bg-photo` в `base.css`) |
| `assets/img/components/logo.png` | Логотип (`site.yaml` → `logo_path`) |
| `assets/img/components/favicon.ico` | Favicon (`site.yaml` → `favicon_path`) |
| `assets/img/components/icon.ico` | Запасная иконка (если используется) |
| `assets/img/components/account-icon.svg` | Иконка аккаунта в шапке |
| `assets/img/components/telegram-logo.svg` | Иконка Telegram в шапке и подвале |

---

## Игры (12 обложек, `INSERT games`)

| Файл |
|------|
| `assets/img/games/rust.webp` |
| `assets/img/games/eft.webp` |
| `assets/img/games/fortnite.webp` |
| `assets/img/games/pubg.webp` |
| `assets/img/games/apex.webp` |
| `assets/img/games/dayz.webp` |
| `assets/img/games/r6s.webp` |
| `assets/img/games/cod.webp` |
| `assets/img/games/cs2.webp` |
| `assets/img/games/genshin-impact.webp` |
| `assets/img/games/gta-v.webp` |
| `assets/img/games/war-thunder.webp` |

---

## OS-категории (2 обложки)

| Файл |
|------|
| `assets/img/games/hwid-spoofer.webp` |
| `assets/img/games/nfa-accounts.webp` |

---

## Обложки модулей (`assets/img/cheats/*.webp`)

| Файл | Примеры slug в БД |
|------|-------------------|
| `assets/img/cheats/rust-pro.webp` | basalt-pro, basalt-radar, … |
| `assets/img/cheats/rust-lite.webp` | basalt-lite, basalt-stealth, … |
| `assets/img/cheats/eft-raid.webp` | edge-raid, night-ops, … |
| `assets/img/cheats/eft-econ.webp` | silent-econ, map-room, … |
| `assets/img/cheats/fn-storm.webp` | storm-build, match-pro, … |
| `assets/img/cheats/fn-nimbus.webp` | nimbus-build, match-lite, … |
| `assets/img/cheats/hwid-core.webp` | spoofer-core |
| `assets/img/cheats/hwid-pro.webp` | spoofer-pro |
| `assets/img/cheats/nfa-cs2.webp` | nfa-cs2 |
| `assets/img/cheats/nfa-fortnite.webp` | nfa-fortnite |
| `assets/img/cheats/nfa-rust.webp` | nfa-rust |
| `assets/img/cheats/nfa-eft.webp` | nfa-eft |
| `assets/img/cheats/genshin-core.webp` | genshin-core |
| `assets/img/cheats/genshin-pro.webp` | genshin-pro |
| `assets/img/cheats/gta-core.webp` | gta-core |
| `assets/img/cheats/gta-pro.webp` | gta-pro |
| `assets/img/cheats/wt-core.webp` | wt-core |
| `assets/img/cheats/wt-pro.webp` | wt-pro |

---

## Альбомы покупки (3× PNG на каждый `cheats.slug`)

Для **каждого** slug из таблицы `cheats` (игры, OS, NFA):

`assets/img/cheats/albums/{slug}-01.png`  
`assets/img/cheats/albums/{slug}-02.png`  
`assets/img/cheats/albums/{slug}-03.png`

### Rust (`game_id = 1`)

basalt-pro, basalt-lite, basalt-stealth, basalt-radar, basalt-vision, basalt-solo, basalt-squad, basalt-raid, basalt-streamsafe, basalt-ultra, rust-nova, rust-shadowline, rust-labs, rust-sentinel, rust-quant, rust-horizon, rust-vector

### Escape from Tarkov (`game_id = 2`)

edge-raid, silent-econ, night-ops, raid-radar, map-room, clean-sight, stash-control, squad-link, stealth-raid, raid-ultra, eft-scout, eft-vector, eft-corex, eft-marathon, eft-prism, eft-zenith, eft-axis

### Fortnite (`game_id = 3`)

storm-build, nimbus-build, fn-volt, fn-stride, fn-aether, fn-sigma, fn-echo, fn-orbit, fn-glide, fn-proxima

### PUBG (`game_id = 4`)

match-pro, match-lite, pubg-arc, pubg-axis, pubg-vanguard, pubg-signal, pubg-nightline, pubg-proto, pubg-omega, pubg-liteplus

### Apex (`game_id = 5`)

rank-core, rank-edge

### DayZ (`game_id = 6`)

survival-kit, survival-pro

### Rainbow Six (`game_id = 7`)

tactics-core, tactics-ops

### Call of Duty (`game_id = 8`)

mp-core, mp-pro

### CS2 (`game_id = 9`)

arena-core, arena-pro

### Genshin Impact (`game_id = 10`)

genshin-core, genshin-pro

### GTA V (`game_id = 11`)

gta-core, gta-pro

### War Thunder (`game_id = 12`)

wt-core, wt-pro

### HWID Spoofer (`os_id = 1`)

spoofer-core, spoofer-pro

### NFA Accounts (`os_id = 2`)

nfa-cs2, nfa-fortnite, nfa-rust, nfa-eft

---

## Галерея витрины

| Файл |
|------|
| `assets/img/gallery/line-1.webp` |
| `assets/img/gallery/line-2.webp` |
| `assets/img/gallery/line-3.webp` |
| `assets/img/gallery/line-4.webp` |
| `assets/img/gallery/line-5.webp` |
| `assets/img/gallery/line-6.webp` |

---

## Команда

| Файл |
|------|
| `assets/img/team/voron.webp` |
| `assets/img/team/key.webp` |
| `assets/img/team/token.webp` |
| `assets/img/team/link.webp` |

---

## SVG (`assets/svg/`)

| Файл | Назначение |
|------|------------|
| `chevron-left.svg` | Слайдер чита — назад |
| `chevron-right.svg` | Слайдер чита — вперёд |
| `chevron-down.svg` | Custom select, раскрытие |
| `close.svg` | Закрытие модалок |
| `search.svg` | Поиск в каталоге |
| `arrow-up.svg` | Кнопка «наверх» |
| `arrow-left.svg` | Навигация / хлебные крошки |
| `arrow-right.svg` | Карточки, ссылки |
| `more-horizontal.svg` | Меню «⋯» практик |

---

## Практика 10 — Sweetty (`db_sweetty`)

| Путь | Назначение |
|------|------------|
| `practice10-sweetty/assets/img/logo.svg` | Логотип учебного сайта |
| `practice10-sweetty/upload/*` | Загрузки формы (7.10) |
| `practice10-sweetty-79/upload/*` | Загрузки формы 7.9 |

Импорт: `site/database/db_sweetty.sql`

---

## Практика 11 — День Победы

| Путь | Назначение |
|------|------------|
| `practice11-victory/assets/media/banner.jpg` | Баннер |
| `practice11-victory/assets/media/hero.jpg` | Блок «Герой моей семьи» |
| `practice11-victory/assets/media/carousel/moscow.jpg` | Карусель (и остальные 18 слайдов по методичке) |
| `practice11-victory/assets/media/carousel/leningrad.jpg` | … |
| `practice11-victory/assets/media/hero-city/brest.jpg` | Карточки городов |
| `practice11-victory/assets/media/hero-city/kerch.jpg` | … |

До добавления файлов — серый fallback в CSS.

---

## Практика 12 — Async «Моя сладость»

| Путь | Назначение |
|------|------------|
| `practice12-async/assets/img/logo.svg` | Логотип |
| `practice12-async/img/logo.svg` | Дубликат логотипа в корне async |
| `practice12-async/assets/img/icons/icon_1.png` | Иконки блока преимуществ |
| `practice12-async/assets/img/icons/icon_2.png` | |
| `practice12-async/assets/img/icons/icon_3.png` | |
| `practice12-async/assets/img/icons/icon_4.png` | |
| `practice12-async/assets/img/icons/icon_5.png` | |
| `practice12-async/assets/img/icons/icon_6.png` | |
| `practice12-async/assets/img/select-cake-main.jpg` | Блок выбора торта |
| `practice12-async/assets/img/select-cake-alt.jpg` | |
| `practice12-async/assets/img/cakes/cake-1.jpg` | Каталог тортов (по комплекту курса) |
| `practice12-async/assets/img/fillings/filling-1.jpg` | Начинки |
| `practice12-async/assets/img/reviews/review-1.jpg` | Отзывы |

Недостающие JPG скопируйте из полного архива `Задания практик 12/async_load`.

---

## Практика 13 — Музыкальный каталог

| Путь | Назначение |
|------|------------|
| `practice13-music/assets/covers/dark-side.jpg` | Обложка альбома |
| `practice13-music/assets/covers/wish-you-were-here.jpg` | |
| `practice13-music/assets/covers/aerosmith-hits.jpg` | |
| `practice13-music/assets/covers/abbey-road.jpg` | |

Пути в `site/database/practice13.sql` (`cover_path`).

---

## QR (внешний сервис)

Генерация в `checkout.php` через `https://api.qrserver.com/...` — локальный файл не нужен.
