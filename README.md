# Basalt Cheats – Deployments

Бинарные установщики и архивы плагинов для развёртывания практик.
Сами файлы в репозитории не хранятся (LFS / внешний диск) – этот README
описывает, что и зачем нужно.

---

## Файлы

| Файл | Назначение | Куда ставить |
|------|------------|--------------|
| `rustup-init.exe` | Установщик Rust toolchain (rustup) | Windows x64 |
| `wordpress-7.1-ru_RU.zip` | WordPress 7.1, русская сборка | `c:\xampp\htdocs\wp-lab\` |
| `node-v24.21.0-win-x64.zip` | Node.js 24 LTS (portable) | любая папка / `%PATH%` |
| `woocommerce.11.1.2.zip` | Плагин WooCommerce | WP → Плагины → Загрузить |
| `woocommerce-pdf-invoices-packing-slips.5.16.3.zip` | PDF-инвойсы для WooCommerce | WP → Плагины → Загрузить |
| `bbpress.2.6.19.zip` | Плагин bbPress (форум) | WP → Плагины → Загрузить |

---

## Для каких практик

- **ПР14, ПР15** (Node.js) → `node-v24.21.0-win-x64.zip`
- **WP1–WP5** (WordPress) → `wordpress-7.1-ru_RU.zip` + плагины:
  - WP3, WP4 → `woocommerce.11.1.2.zip`
  - WP4 → `woocommerce-pdf-invoices-packing-slips.5.16.3.zip`
  - WP5 → `bbpress.2.6.19.zip`
- **Rust** → `rustup-init.exe` (запускать один раз, далее `rustup` сам обновляет toolchain)

---

## Быстрая установка

### Node.js (ПР14, ПР15)

1. Распаковать `node-v24.21.0-win-x64.zip` в удобное место (например `c:\node\`).
2. Добавить `c:\node\` в `PATH`.
3. Проверить:
   ```bash
   node -v
   npm -v
   ```

### WordPress (WP1–WP5)

1. В phpMyAdmin создать БД:
   ```sql
   CREATE DATABASE wp_lab CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
2. Распаковать `wordpress-7.1-ru_RU.zip` в `c:\xampp\htdocs\wp-lab\`.
3. Открыть `http://localhost/wp-lab/`, пройти мастер установки (БД `wp_lab`,
MySQL-пользователь `root` без пароля).
4. Настройки → Постоянные ссылки → «Название записи».
5. Установить плагины из zip через Плагины → Добавить новый → Загрузить плагин.

---

## Примечания

Актуальные версии всегда можно скачать по этим ссылкам:

- **Node.js** – https://nodejs.org/
- **WordPress** – https://ru.wordpress.org/download/
- **WooCommerce** – https://wordpress.org/plugins/woocommerce/
- **PDF Invoices** – https://wordpress.org/plugins/woocommerce-pdf-invoices-packing-slips/
- **bbPress** – https://wordpress.org/plugins/bbpress/
- **Rust** – https://rustup.rs/