-- =============================================================================
-- FULL.sql — basalt_cheats (main storefront)
-- One-shot import: main store + practice databases (PR10, Sweetty, PR13).
-- phpMyAdmin: SQL tab, paste/run entire file. CLI: mysql -u root < FULL.sql
-- Partial updates: individual .sql files in this folder.
-- =============================================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';

DROP DATABASE IF EXISTS `basalt_cheats`;
DROP DATABASE IF EXISTS `basalt_practice10`;
DROP DATABASE IF EXISTS `db_sweetty`;
DROP DATABASE IF EXISTS `basalt_practice13`;

-- =============================================================================
-- SECTION: basalt_cheats (main storefront)
-- Source: site/database/basalt_cheats.sql
-- =============================================================================

-- Basalt Cheats — schema + seed (cheat shop flow: game -> cheat -> key plan -> checkout)
--
-- Полный сброс всех БД проекта: импортируйте site/database/FULL.sql.
-- Только эта база: повторный импорт этого файла (DROP TABLE ниже сбрасывает таблицы в basalt_cheats).
--
SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE DATABASE IF NOT EXISTS `basalt_cheats` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `basalt_cheats`;

DROP TABLE IF EXISTS `payment_requests`;
DROP TABLE IF EXISTS `admin_change_logs`;
DROP TABLE IF EXISTS `admin_access_tokens`;
DROP TABLE IF EXISTS `license_keys`;
DROP TABLE IF EXISTS `purchase_orders`;
DROP TABLE IF EXISTS `cheat_media`;
DROP TABLE IF EXISTS `cheat_features`;
DROP TABLE IF EXISTS `cheat_requirements`;
DROP TABLE IF EXISTS `deleted_accounts`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `contact_messages`;
DROP TABLE IF EXISTS `gallery_items`;
DROP TABLE IF EXISTS `team_members`;
DROP TABLE IF EXISTS `news_articles`;
DROP TABLE IF EXISTS `index_page`;
DROP TABLE IF EXISTS `key_plans`;
DROP TABLE IF EXISTS `cheats`;
DROP TABLE IF EXISTS `os`;
DROP TABLE IF EXISTS `games`;

CREATE TABLE `games` (
  `id` int NOT NULL AUTO_INCREMENT,
  `slug` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_ru` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_en` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tagline_ru` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tagline_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `os` (
  `id` int NOT NULL AUTO_INCREMENT,
  `slug` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_ru` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_en` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tagline_ru` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tagline_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cheats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `game_id` int DEFAULT NULL,
  `os_id` int DEFAULT NULL,
  `slug` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_ru` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_en` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_ru` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_en` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `game_slug` (`game_id`,`slug`),
  UNIQUE KEY `os_slug` (`os_id`,`slug`),
  CONSTRAINT `fk_cheats_game` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cheats_os` FOREIGN KEY (`os_id`) REFERENCES `os` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chk_cheats_scope` CHECK ((`game_id` IS NOT NULL AND `os_id` IS NULL) OR (`game_id` IS NULL AND `os_id` IS NOT NULL))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `games`
  ADD COLUMN `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  ADD COLUMN `maintenance_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL;

ALTER TABLE `os`
  ADD COLUMN `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  ADD COLUMN `maintenance_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL;

ALTER TABLE `cheats`
  ADD COLUMN `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  ADD COLUMN `maintenance_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL;

CREATE TABLE `key_plans` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cheat_id` int NOT NULL,
  `label_ru` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label_en` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_days` int NOT NULL,
  `price_rub` int NOT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `cheat_id` (`cheat_id`),
  CONSTRAINT `fk_plans_cheat` FOREIGN KEY (`cheat_id`) REFERENCES `cheats` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `index_page` (
  `id_element` int NOT NULL AUTO_INCREMENT,
  `alias` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content_ru` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_en` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_element`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `news_articles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_ru` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt_ru` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt_en` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_ru` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_en` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `published_at` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `gallery_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption_ru` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `team_members` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name_ru` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_ru` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_en` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio_ru` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio_en` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `contact_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telegram` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `payment_requests` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `game_id` int DEFAULT NULL,
  `os_id` int DEFAULT NULL,
  `cheat_id` int NOT NULL,
  `key_plan_id` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telegram` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` mediumtext COLLATE utf8mb4_unicode_ci,
  `qr_payload` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid_ack` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_pay_game` (`game_id`),
  KEY `fk_pay_os` (`os_id`),
  KEY `fk_pay_cheat` (`cheat_id`),
  KEY `fk_pay_plan` (`key_plan_id`),
  CONSTRAINT `fk_pay_game_ref` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_pay_os_ref` FOREIGN KEY (`os_id`) REFERENCES `os` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_pay_cheat_ref` FOREIGN KEY (`cheat_id`) REFERENCES `cheats` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pay_plan_ref` FOREIGN KEY (`key_plan_id`) REFERENCES `key_plans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chk_payment_scope` CHECK ((`game_id` IS NOT NULL AND `os_id` IS NULL) OR (`game_id` IS NULL AND `os_id` IS NOT NULL))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nickname` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `deleted_accounts` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `blocked_until` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_deleted_accounts_email` (`email`),
  KEY `idx_deleted_accounts_blocked_until` (`blocked_until`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cheat_features` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `cheat_id` int NOT NULL,
  `feature_group` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `feature_name_ru` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `feature_name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_cheat_features_cheat` (`cheat_id`),
  CONSTRAINT `fk_cheat_features_cheat` FOREIGN KEY (`cheat_id`) REFERENCES `cheats` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cheat_media` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `cheat_id` int NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption_ru` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caption_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_cheat_media_cheat` (`cheat_id`),
  CONSTRAINT `fk_cheat_media_cheat` FOREIGN KEY (`cheat_id`) REFERENCES `cheats` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cheat_requirements` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `cheat_id` int NOT NULL,
  `req_group` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `req_name_ru` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `req_name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_cheat_requirements_cheat` (`cheat_id`),
  CONSTRAINT `fk_cheat_requirements_cheat` FOREIGN KEY (`cheat_id`) REFERENCES `cheats` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `purchase_orders` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL,
  `game_id` int DEFAULT NULL,
  `os_id` int DEFAULT NULL,
  `cheat_id` int NOT NULL,
  `key_plan_id` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telegram` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` mediumtext COLLATE utf8mb4_unicode_ci,
  `qr_payload` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_status` enum('created','paid_waiting_review','confirmed','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'created',
  `payment_status` enum('unpaid','reported_paid','approved','declined') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `paid_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_purchase_orders_user` (`user_id`),
  KEY `idx_purchase_orders_cheat` (`cheat_id`),
  KEY `idx_purchase_orders_plan` (`key_plan_id`),
  CONSTRAINT `fk_purchase_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_purchase_orders_game` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_purchase_orders_os` FOREIGN KEY (`os_id`) REFERENCES `os` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_purchase_orders_cheat` FOREIGN KEY (`cheat_id`) REFERENCES `cheats` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_purchase_orders_plan` FOREIGN KEY (`key_plan_id`) REFERENCES `key_plans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chk_purchase_scope` CHECK ((`game_id` IS NOT NULL AND `os_id` IS NULL) OR (`game_id` IS NULL AND `os_id` IS NOT NULL))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `license_keys` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `order_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `key_value` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','active','expired','frozen_manual','frozen_auto','disabled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `activated_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `frozen_until` timestamp NULL DEFAULT NULL,
  `freeze_used` tinyint(1) NOT NULL DEFAULT 0,
  `freeze_minutes_used` int NOT NULL DEFAULT 0,
  `auto_frozen_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_license_keys_value` (`key_value`),
  KEY `idx_license_keys_user` (`user_id`),
  KEY `idx_license_keys_order` (`order_id`),
  CONSTRAINT `fk_license_keys_order` FOREIGN KEY (`order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_license_keys_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `admin_access_tokens` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `token_hash` char(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_admin_token_hash` (`token_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `admin_change_logs` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `admin_scope` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `row_id` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` enum('create','update','delete') COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload_json` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `admin_access_tokens` (`token_hash`, `is_active`, `note`) VALUES
(SHA2('123456789012345678901234567890123456789012345', 256), 1, 'default seeded token, rotate in production');

INSERT INTO `index_page` (`id_element`, `alias`, `content_ru`, `content_en`) VALUES
(1, 'hero', '<section class="section hero-ws"><div class="container hero-grid-ws"><div class="hero-copy"><p class="eyebrow">PRIVATE SOFTWARE</p><h1 class="hero-title-ws">Приватные читы для топовых игр</h1><p class="lead">Подбор приватных сборок, быстрая выдача ключа после оплаты, поддержка в Telegram. Монохромный интерфейс — фокус на каталоге и прозрачных сроках.</p><div class="hero-badges"><span class="badge-pill"><span class="badge-dot"></span>Онлайн-выдача</span><span class="badge-pill"><span class="badge-dot"></span>Обновления</span><span class="badge-pill"><span class="badge-dot"></span>24/7</span></div><div class="hero-actions"><a class="btn btn-primary" href="games.php">В каталог игр</a><a class="btn btn-ghost" href="faq.php">FAQ</a></div></div><div class="hero-visual card-glass" aria-hidden="true"><div class="hero-visual-grid"><span></span><span></span><span></span><span></span></div></div></div></section>', '<section class="section hero-ws"><div class="container hero-grid-ws"><div class="hero-copy"><p class="eyebrow">PRIVATE SOFTWARE</p><h1 class="hero-title-ws">Private cheats for leading titles</h1><p class="lead">Curated private builds, fast key delivery after payment, Telegram support. Monochrome UI — focus on catalog and clear durations.</p><div class="hero-badges"><span class="badge-pill"><span class="badge-dot"></span>Online delivery</span><span class="badge-pill"><span class="badge-dot"></span>Updates</span><span class="badge-pill"><span class="badge-dot"></span>24/7</span></div><div class="hero-actions"><a class="btn btn-primary" href="games.php">Browse games</a><a class="btn btn-ghost" href="faq.php">FAQ</a></div></div><div class="hero-visual card-glass" aria-hidden="true"><div class="hero-visual-grid"><span></span><span></span><span></span><span></span></div></div></div></section>'),
(2, 'advantages', '<section class="section"><div class="container grid cols-4"><article class="card card-hover"><h3>Антидетект-профили</h3><p>Сборки проходят внутренние проверки перед публикацией в витрине.</p></article><article class="card card-hover"><h3>Поддержка</h3><p>Операторы в Telegram закрывают вопросы по ключу и обновлениям.</p></article><article class="card card-hover"><h3>Сроки</h3><p>Вы сами выбираете длительность лицензии перед оплатой.</p></article><article class="card card-hover"><h3>Прозрачность</h3><p>Оплата только после выбора игры, чита и срока действия.</p></article></div></section>', '<section class="section"><div class="container grid cols-4"><article class="card card-hover"><h3>Anti-detect profiles</h3><p>Builds pass internal checks before they hit the storefront.</p></article><article class="card card-hover"><h3>Support</h3><p>Telegram operators handle key and update questions.</p></article><article class="card card-hover"><h3>Durations</h3><p>You pick license length before paying.</p></article><article class="card card-hover"><h3>Transparency</h3><p>Checkout only after game, cheat, and term are chosen.</p></article></div></section>'),
(3, 'split-cta', '<section class="section"><div class="container grid cols-2"><div class="card card-hover stack"><h2>Каталог по играм</h2><p>Выберите игру, затем приватный модуль и срок подписки.</p><a class="btn btn-primary" href="games.php">Магазин</a></div><div class="card card-hover stack"><h2>Нужна помощь?</h2><p>FAQ, реквизиты и карта офиса — на отдельной странице.</p><a class="btn btn-ghost" href="faq.php">Открыть FAQ</a></div></div></section>', '<section class="section"><div class="container grid cols-2"><div class="card card-hover stack"><h2>Game catalog</h2><p>Pick a game, then a private module and subscription term.</p><a class="btn btn-primary" href="games.php">Store</a></div><div class="card card-hover stack"><h2>Need help?</h2><p>FAQ, legal details, and office map live on a dedicated page.</p><a class="btn btn-ghost" href="faq.php">Open FAQ</a></div></div></section>'),
(4, 'about', '<section class="section"><div class="container split"><div><h2>О Basalt</h2><p>Мы ведем витрину приватных модулей: оформление заказа, выдача ключа и сопровождение после покупки.</p><p>Без скрытых подписок — только выбранный срок и понятная цена на шаге оплаты.</p></div><div class="card card-hover"><h3>Безопасность сделки</h3><ul class="list"><li>Проверка связки игра / чит / план</li><li>Уникальный QR на сессию оплаты</li><li>Журнал заявок в базе</li></ul></div></div></section>', '<section class="section"><div class="container split"><div><h2>About Basalt</h2><p>We run a storefront for private modules: checkout, key delivery, and post-sale guidance.</p><p>No hidden subscriptions — only the term you pick and a clear price at payment.</p></div><div class="card card-hover"><h3>Transaction safety</h3><ul class="list"><li>Validated game / cheat / plan tuple</li><li>Unique QR per payment session</li><li>Request log in the database</li></ul></div></div></section>'),
(5, 'reviews', '<section class="section"><div class="container stack"><h2>Отзывы</h2><div class="grid cols-3"><blockquote class="card card-hover"><p>Ключ пришел за пару минут, без лишней бюрократии.</p><footer class="muted">— клиент NX-42</footer></blockquote><blockquote class="card card-hover"><p>Интерфейс спокойный, глаза не режет — удобно выбирать тариф.</p><footer class="muted">— frostline</footer></blockquote><blockquote class="card card-hover"><p>Срок выбрал на 90 дней, все завелось с первого входа.</p><footer class="muted">— signal9</footer></blockquote></div></div></section>', '<section class="section"><div class="container stack"><h2>Reviews</h2><div class="grid cols-3"><blockquote class="card card-hover"><p>Key arrived in minutes, no bureaucracy.</p><footer class="muted">— client NX-42</footer></blockquote><blockquote class="card card-hover"><p>Calm UI, easy to pick a tier.</p><footer class="muted">— frostline</footer></blockquote><blockquote class="card card-hover"><p>90-day plan, worked on first launch.</p><footer class="muted">— signal9</footer></blockquote></div></div></section>'),
(6, 'map', '<section class="section"><div class="container stack"><h2>Мы на карте</h2><p class="muted">Демо (ПР9): встраиваемая карта в контенте из таблицы <code>index_page</code>, по идее курса с тортами.</p><iframe title="Карта" loading="lazy" style="border:0;width:100%;max-width:900px;height:min(400px,50vh);border-radius:12px" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2243.5!2d37.6176355!3d55.755814!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54afc73d315b7%3A0x11a9d0b62e6275e!2z0JzQvtGB0LrQstCw!5e0!3m2!1sru!2sru!4v1700000000000!5m2!1sru!2sru" allowfullscreen></iframe><p class="muted">Полная карта и реквизиты также в разделе FAQ.</p></div></section>', '<section class="section"><div class="container stack"><h2>We on the map</h2><p class="muted">Demo (PR9): embedded map inside <code>index_page</code> row, same idea as the course bakery dump.</p><iframe title="Map" loading="lazy" style="border:0;width:100%;max-width:900px;height:min(400px,50vh);border-radius:12px" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2243.5!2d37.6176355!3d55.755814!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b54afc73d315b7%3A0x11a9d0b62e6275e!2z0JzQvtGB0LrQstCw!5e0!3m2!1sen!2sru!4v1700000000000!5m2!1sen!2sru" allowfullscreen></iframe><p class="muted">Full map and legal details are also on the FAQ page.</p></div></section>');

INSERT INTO `games` (`slug`, `title_ru`, `title_en`, `tagline_ru`, `tagline_en`, `image_path`, `sort_order`) VALUES
('rust', 'Rust', 'Rust', 'Приватные модули под выживание и PvP.', 'Private modules for survival and PvP.', 'assets/img/games/rust.webp', 1),
('eft', 'Escape from Tarkov', 'Escape from Tarkov', 'Сборки под рейды и экономику.', 'Builds tuned for raids and economy.', 'assets/img/games/eft.webp', 2),
('fortnite', 'Fortnite', 'Fortnite', 'Линейка для батл-рояля.', 'Battle royale lineup.', 'assets/img/games/fortnite.webp', 3),
('pubg', 'PUBG', 'PUBG', 'Стабильные сборки для матчей.', 'Stable builds for matches.', 'assets/img/games/pubg.webp', 4),
('apex', 'Apex Legends', 'Apex Legends', 'Подбор модулей под рейтинги.', 'Modules tuned for ranked.', 'assets/img/games/apex.webp', 5),
('dayz', 'DayZ', 'DayZ', 'Пакеты для выживания.', 'Survival packages.', 'assets/img/games/dayz.webp', 6),
('r6s', 'Rainbow Six Siege', 'Rainbow Six Siege', 'Сборки под тактику и матчмейкинг.', 'Tactical matchmaking builds.', 'assets/img/games/r6s.webp', 7),
('cod', 'Call of Duty', 'Call of Duty', 'Профили под мультиплеер.', 'Multiplayer profiles.', 'assets/img/games/cod.webp', 8),
('cs2', 'Counter-Strike 2', 'Counter-Strike 2', 'Линейка под соревновательный режим.', 'Competitive lineup.', 'assets/img/games/cs2.webp', 9),
('genshin-impact', 'Genshin Impact', 'Genshin Impact', 'Профили под кооператив и фарм.', 'Profiles for co-op and farming.', 'assets/img/games/genshin-impact.webp', 10),
('gta-v', 'GTA V', 'GTA V', 'Сборки под сессии и миссии.', 'Builds for sessions and missions.', 'assets/img/games/gta-v.webp', 11),
('war-thunder', 'War Thunder', 'War Thunder', 'Профили для авиации и наземных боёв.', 'Profiles for air and ground battles.', 'assets/img/games/war-thunder.webp', 12);

INSERT INTO `os` (`slug`, `title_ru`, `title_en`, `tagline_ru`, `tagline_en`, `image_path`, `sort_order`) VALUES
('hwid-spoofer', 'HWID Spoofer', 'HWID Spoofer', 'Системная утилита для сброса аппаратных идентификаторов.', 'System utility for hardware identifier reset.', 'assets/img/games/hwid-spoofer.webp', 1),
('nfa-accounts', 'NFA-аккаунты', 'NFA Accounts', 'Каталог NFA-аккаунтов по популярным играм.', 'NFA account catalog for popular games.', 'assets/img/games/nfa-accounts.webp', 2);

INSERT INTO `cheats` (`game_id`, `os_id`, `slug`, `title_ru`, `title_en`, `description_ru`, `description_en`, `image_path`, `sort_order`) VALUES
(1, NULL, 'basalt-pro', 'Basalt Pro', 'Basalt Pro', 'Расширенный функционал, приоритетные обновления под патчи игры.', 'Full feature set, priority updates aligned with game patches.', 'assets/img/cheats/rust-pro.webp', 1),
(1, NULL, 'basalt-lite', 'Basalt Lite', 'Basalt Lite', 'Облегченный профиль для стабильного фреймрейта.', 'Lightweight profile focused on stable FPS.', 'assets/img/cheats/rust-lite.webp', 2),
(1, NULL, 'basalt-stealth', 'Basalt Stealth', 'Basalt Stealth', 'Упор на минимальный след и аккуратные профили.', 'Low-footprint mode with careful profiles.', 'assets/img/cheats/rust-lite.webp', 3),
(1, NULL, 'basalt-radar', 'Basalt Radar', 'Basalt Radar', 'Упор на интеллект‑слой и обзор сцены без лишней нагрузки.', 'Intel-first profile with minimal overhead.', 'assets/img/cheats/rust-pro.webp', 4),
(1, NULL, 'basalt-vision', 'Basalt Vision', 'Basalt Vision', 'Визуальные помощники, фильтры видимости и аккуратные пресеты.', 'Visual helpers, visibility filters, clean presets.', 'assets/img/cheats/rust-lite.webp', 5),
(1, NULL, 'basalt-solo', 'Basalt Solo', 'Basalt Solo', 'Профиль для соло‑рейдов и быстрых ситуаций.', 'Solo raids and quick situations profile.', 'assets/img/cheats/rust-pro.webp', 6),
(1, NULL, 'basalt-squad', 'Basalt Squad', 'Basalt Squad', 'Командные пресеты и инструменты под тимплей.', 'Teamplay presets and tools.', 'assets/img/cheats/rust-lite.webp', 7),
(1, NULL, 'basalt-raid', 'Basalt Raid', 'Basalt Raid', 'Лут‑маршруты, контейнеры и быстрая навигация по точкам.', 'Loot routes, containers, fast point navigation.', 'assets/img/cheats/rust-pro.webp', 8),
(1, NULL, 'basalt-streamsafe', 'Basalt StreamSafe', 'Basalt StreamSafe', 'Сценарии отображения под стрим/запись и безопасные режимы.', 'Display modes for streaming/recording and safe modes.', 'assets/img/cheats/rust-lite.webp', 9),
(1, NULL, 'basalt-ultra', 'Basalt Ultra', 'Basalt Ultra', 'Максимальный набор, быстрые обновления и расширенные настройки.', 'Max feature set, fast updates, extended tuning.', 'assets/img/cheats/rust-pro.webp', 10),
(2, NULL, 'edge-raid', 'Edge Raid', 'Edge Raid', 'Акцент на информации в рейде и лут-цикле.', 'Raid intel and loot-loop oriented package.', 'assets/img/cheats/eft-raid.webp', 1),
(2, NULL, 'silent-econ', 'Silent Econ', 'Silent Econ', 'Минимальный след, упор на экономику и трейдинг.', 'Low-footprint, economy and trading focused.', 'assets/img/cheats/eft-econ.webp', 2),
(2, NULL, 'night-ops', 'Night Ops', 'Night Ops', 'Профиль под ночные рейды и осторожный стиль.', 'Night raids oriented, careful style.', 'assets/img/cheats/eft-raid.webp', 3),
(2, NULL, 'raid-radar', 'Raid Radar', 'Raid Radar', 'Расширенный обзор лута/контейнеров и точек интереса.', 'Extended loot/containers/POI overview.', 'assets/img/cheats/eft-raid.webp', 4),
(2, NULL, 'map-room', 'Map Room', 'Map Room', 'Навигация по ключевым точкам и быстрые пресеты.', 'Navigation to key points and quick presets.', 'assets/img/cheats/eft-econ.webp', 5),
(2, NULL, 'clean-sight', 'Clean Sight', 'Clean Sight', 'Фильтры, читаемость и минимальная нагрузка.', 'Filters, readability, minimal overhead.', 'assets/img/cheats/eft-raid.webp', 6),
(2, NULL, 'stash-control', 'Stash Control', 'Stash Control', 'Инструменты под экономику и контроль лут‑цикла.', 'Economy tools and loot-loop control.', 'assets/img/cheats/eft-econ.webp', 7),
(2, NULL, 'squad-link', 'Squad Link', 'Squad Link', 'Командные пресеты и взаимодействие.', 'Team presets and coordination.', 'assets/img/cheats/eft-raid.webp', 8),
(2, NULL, 'stealth-raid', 'Stealth Raid', 'Stealth Raid', 'Сдержанные режимы и аккуратные профили.', 'Conservative modes and careful profiles.', 'assets/img/cheats/eft-econ.webp', 9),
(2, NULL, 'raid-ultra', 'Raid Ultra', 'Raid Ultra', 'Максимальный набор и приоритетные обновления.', 'Max kit and priority updates.', 'assets/img/cheats/eft-raid.webp', 10),
(3, NULL, 'storm-build', 'Storm Build', 'Storm Build', 'Сбалансированный набор для соревновательных матчмейкингов.', 'Balanced kit for competitive matchmaking.', 'assets/img/cheats/fn-storm.webp', 1),
(3, NULL, 'nimbus-build', 'Nimbus Build', 'Nimbus Build', 'Упор на визуальную чистоту и читаемость сцены.', 'Visual clarity and clean scene read.', 'assets/img/cheats/fn-nimbus.webp', 2),
(4, NULL, 'match-pro', 'Match Pro', 'Match Pro', 'Стабильный профиль для пабликов и рейтинга.', 'Stable profile for public and ranked.', 'assets/img/cheats/fn-storm.webp', 1),
(4, NULL, 'match-lite', 'Match Lite', 'Match Lite', 'Лёгкий модуль без лишней нагрузки.', 'Light module, low overhead.', 'assets/img/cheats/fn-nimbus.webp', 2),
(5, NULL, 'rank-core', 'Rank Core', 'Rank Core', 'Ядро для рейтинговых режимов.', 'Core for ranked modes.', 'assets/img/cheats/rust-pro.webp', 1),
(5, NULL, 'rank-edge', 'Rank Edge', 'Rank Edge', 'Дополнительные инструменты под тимплей.', 'Extra tools for teamplay.', 'assets/img/cheats/rust-lite.webp', 2),
(6, NULL, 'survival-kit', 'Survival Kit', 'Survival Kit', 'Набор под выживание и лут.', 'Survival and loot oriented.', 'assets/img/cheats/eft-econ.webp', 1),
(6, NULL, 'survival-pro', 'Survival Pro', 'Survival Pro', 'Расширенный набор с приоритетными обновлениями.', 'Extended kit with priority updates.', 'assets/img/cheats/eft-raid.webp', 2),
(7, NULL, 'tactics-core', 'Tactics Core', 'Tactics Core', 'Тактический профиль под матчи.', 'Tactical profile for matches.', 'assets/img/cheats/fn-storm.webp', 1),
(7, NULL, 'tactics-ops', 'Tactics Ops', 'Tactics Ops', 'Операционный набор под командную игру.', 'Ops set for teamplay.', 'assets/img/cheats/fn-nimbus.webp', 2),
(8, NULL, 'mp-core', 'MP Core', 'MP Core', 'Профиль под мультиплеерные режимы.', 'Profile for multiplayer.', 'assets/img/cheats/rust-lite.webp', 1),
(8, NULL, 'mp-pro', 'MP Pro', 'MP Pro', 'Расширенный набор функций.', 'Extended feature set.', 'assets/img/cheats/rust-pro.webp', 2),
(9, NULL, 'arena-core', 'Arena Core', 'Arena Core', 'Соревновательный профиль под арены.', 'Competitive arena profile.', 'assets/img/cheats/eft-econ.webp', 1),
(9, NULL, 'arena-pro', 'Arena Pro', 'Arena Pro', 'Приоритетные обновления и расширенные профили.', 'Priority updates and expanded profiles.', 'assets/img/cheats/eft-raid.webp', 2),
(NULL, 1, 'spoofer-core', 'Spoofer Core', 'Spoofer Core', 'Базовый HWID Spoofer для быстрой смены отпечатка системы.', 'Base HWID spoofer for quick system fingerprint reset.', 'assets/img/cheats/hwid-core.webp', 1),
(NULL, 1, 'spoofer-pro', 'Spoofer Pro', 'Spoofer Pro', 'Расширенный набор профилей и приоритетные обновления.', 'Extended profile pack with priority updates.', 'assets/img/cheats/hwid-pro.webp', 2),
(NULL, 2, 'nfa-cs2', 'NFA CS2', 'NFA CS2', 'NFA-аккаунты Counter-Strike 2.', 'Counter-Strike 2 NFA accounts.', 'assets/img/cheats/nfa-cs2.webp', 1),
(NULL, 2, 'nfa-fortnite', 'NFA Fortnite', 'NFA Fortnite', 'NFA-аккаунты Fortnite.', 'Fortnite NFA accounts.', 'assets/img/cheats/nfa-fortnite.webp', 2),
(NULL, 2, 'nfa-rust', 'NFA Rust', 'NFA Rust', 'NFA-аккаунты Rust.', 'Rust NFA accounts.', 'assets/img/cheats/nfa-rust.webp', 3),
(NULL, 2, 'nfa-eft', 'NFA EFT', 'NFA EFT', 'NFA-аккаунты Escape from Tarkov.', 'Escape from Tarkov NFA accounts.', 'assets/img/cheats/nfa-eft.webp', 4),
(10, NULL, 'genshin-core', 'Genshin Core', 'Genshin Core', 'Стабильный профиль для ежедневных задач.', 'Stable profile for daily activities.', 'assets/img/cheats/genshin-core.webp', 1),
(10, NULL, 'genshin-pro', 'Genshin Pro', 'Genshin Pro', 'Расширенный набор с приоритетными обновлениями.', 'Extended kit with priority updates.', 'assets/img/cheats/genshin-pro.webp', 2),
(11, NULL, 'gta-core', 'GTA Core', 'GTA Core', 'Базовый профиль для сессий и контрактов.', 'Base profile for sessions and contracts.', 'assets/img/cheats/gta-core.webp', 1),
(11, NULL, 'gta-pro', 'GTA Pro', 'GTA Pro', 'Расширенный профиль для длительных сессий.', 'Extended profile for long sessions.', 'assets/img/cheats/gta-pro.webp', 2),
(12, NULL, 'wt-core', 'WT Core', 'WT Core', 'Профиль для стандартных боёв.', 'Profile for standard battles.', 'assets/img/cheats/wt-core.webp', 1),
(12, NULL, 'wt-pro', 'WT Pro', 'WT Pro', 'Расширенный профиль для рейтинговых боёв.', 'Extended profile for ranked battles.', 'assets/img/cheats/wt-pro.webp', 2);

-- Plans are generated by cheat type to avoid brittle numeric cheat_id references.
-- Normal cheats / utilities: 30 / 90 / 365 days + Lifetime
INSERT INTO `key_plans` (`cheat_id`, `label_ru`, `label_en`, `duration_days`, `price_rub`, `sort_order`)
SELECT c.id, p.label_ru, p.label_en, p.duration_days,
  CASE p.duration_days
    WHEN 30 THEN 1990
    WHEN 90 THEN 4490
    WHEN 365 THEN 9990
    ELSE 14990
  END AS price_rub,
  p.sort_order
FROM cheats c
JOIN (
  SELECT '30 дней' AS label_ru, '30 days' AS label_en, 30 AS duration_days, 1 AS sort_order
  UNION ALL SELECT '90 дней', '90 days', 90, 2
  UNION ALL SELECT '365 дней', '365 days', 365, 3
  UNION ALL SELECT 'Навсегда', 'Lifetime', 0, 4
) p
WHERE c.os_id IS NULL OR c.os_id = 1;

-- NFA accounts: duration_days stores quantity
INSERT INTO `key_plans` (`cheat_id`, `label_ru`, `label_en`, `duration_days`, `price_rub`, `sort_order`)
SELECT c.id, p.label_ru, p.label_en, p.qty,
  CASE p.qty
    WHEN 1 THEN 290
    WHEN 3 THEN 790
    WHEN 5 THEN 1290
    ELSE 2390
  END AS price_rub,
  p.sort_order
FROM cheats c
JOIN (
  SELECT '1 аккаунт' AS label_ru, '1 account' AS label_en, 1 AS qty, 1 AS sort_order
  UNION ALL SELECT '3 аккаунта', '3 accounts', 3, 2
  UNION ALL SELECT '5 аккаунтов', '5 accounts', 5, 3
  UNION ALL SELECT '10 аккаунтов', '10 accounts', 10, 4
) p
WHERE c.os_id = 2;

-- Album slides: 1920×1080 PNG, 16:9 (paths relative to site/)
INSERT INTO `cheat_media` (`cheat_id`, `image_path`, `caption_ru`, `caption_en`, `sort_order`)
SELECT c.id, CONCAT('assets/img/cheats/albums/', c.slug, '-01.png'), '', '', 1 FROM `cheats` c
UNION ALL
SELECT c.id, CONCAT('assets/img/cheats/albums/', c.slug, '-02.png'), '', '', 2 FROM `cheats` c
UNION ALL
SELECT c.id, CONCAT('assets/img/cheats/albums/', c.slug, '-03.png'), '', '', 3 FROM `cheats` c;

INSERT INTO `cheat_features` (`cheat_id`, `feature_group`, `feature_name_ru`, `feature_name_en`, `sort_order`) VALUES
(1, 'ESP', 'Игроки, лут, контейнеры', 'Players, loot, containers', 1),
(1, 'ESP', 'Distance / visibility filters', 'Distance / visibility filters', 2),
(1, 'AIM', 'Настраиваемый aimbot FOV', 'Configurable aimbot FOV', 3),
(1, 'AIM', 'Сглаживание и silent options', 'Smoothing and silent options', 4),
(1, 'MISC', 'No recoil / no sway', 'No recoil / no sway', 5),
(1, 'MISC', 'Конфиг-профили и hotkeys', 'Config profiles and hotkeys', 6),
(2, 'ESP', 'Базовые ESP-метки', 'Basic ESP markers', 1),
(2, 'MISC', 'Стабильный lightweight режим', 'Stable lightweight mode', 2),
(21, 'SYSTEM', 'Смена HWID-профиля', 'HWID profile switch', 1),
(21, 'SYSTEM', 'Быстрый rollback профиля', 'Fast profile rollback', 2);

INSERT INTO `news_articles` (`slug`, `title_ru`, `title_en`, `excerpt_ru`, `excerpt_en`, `body_ru`, `body_en`, `published_at`) VALUES
('signal-boost', 'Усиление выдачи ключей', 'Faster key delivery', 'Перестроили очередь выдачи.', 'We reshaped the issuance queue.', '<p>Среднее время сократилось.</p>', '<p>Average time dropped.</p>', '2026-01-12'),
('mono-ui', 'Монохром 2.0', 'Monochrome 2.0', 'Новый визуальный контур.', 'New visual shell.', '<p>Серые панели и плавные hover.</p>', '<p>Grey panels and smooth hovers.</p>', '2026-02-03'),
('ledger-patch', 'Журнал заявок', 'Request ledger', 'Все оплаты пишутся в MySQL.', 'Payments are logged in MySQL.', '<p>Можно отследить статус.</p>', '<p>Status traceable.</p>', '2026-02-21'),
('route-health', 'Статус маршрутов', 'Route health', 'Пинг инфраструктуры каждую минуту.', 'Infra ping every minute.', '<p>Алерты в Telegram.</p>', '<p>Telegram alerts.</p>', '2026-03-02'),
('bundle-lab', 'Конструктор заказа', 'Order builder', 'Игра - чит - срок - оплата.', 'Game - cheat - term - pay.', '<p>Жесткая последовательность.</p>', '<p>Strict sequence.</p>', '2026-03-18'),
('qr-randomizer', 'QR-сессии', 'QR sessions', 'Уникальный payload на оплату.', 'Unique payload per checkout.', '<p>Защита от повторов.</p>', '<p>Replay-safe.</p>', '2026-04-01'),
('frame-docs', 'Микро-гайды', 'Micro guides', 'IFRAME-консоль.', 'IFRAME console.', '<p>Быстрые инструкции.</p>', '<p>Quick instructions.</p>', '2026-04-05'),
('gallery-sync', 'Медиа CDN', 'Media CDN', 'Галерея на CDN.', 'Gallery on CDN.', '<p>Lazy-load кадров.</p>', '<p>Lazy-loaded frames.</p>', '2026-04-09'),
('night-ops', 'Ночная смена', 'Night shift', 'Расширенное окно поддержки.', 'Extended support window.', '<p>Меньше очереди ночью.</p>', '<p>Shorter queues at night.</p>', '2026-04-14');

INSERT INTO `gallery_items` (`image_path`, `caption_ru`, `caption_en`, `sort_order`) VALUES
('assets/img/gallery/line-1.webp', 'Контроль сборки', 'Build control', 1),
('assets/img/gallery/line-2.webp', 'Тестовый стенд', 'Test rig', 2),
('assets/img/gallery/line-3.webp', 'Мониторинг', 'Monitoring', 3),
('assets/img/gallery/line-4.webp', 'Панель статусов', 'Status board', 4),
('assets/img/gallery/line-5.webp', 'Логистика ключей', 'Key logistics', 5),
('assets/img/gallery/line-6.webp', 'Ночной NOC', 'Night NOC', 6);

INSERT INTO `team_members` (`name_ru`, `name_en`, `role_ru`, `role_en`, `bio_ru`, `bio_en`, `photo_path`, `sort_order`) VALUES
('Алексей Ворон', 'Alex Voron', 'Ведущий инженер выдачи', 'Lead release engineer', 'Отвечает за конвейер ключей и целостность билдов.', 'Owns the key pipeline and build integrity.', 'assets/img/team/voron.webp', 1),
('Мария Кей', 'Maria Key', 'Операции', 'Operations', 'SLA и коммуникации с клиентами.', 'SLAs and client comms.', 'assets/img/team/key.webp', 2),
('Илья Токен', 'Ilya Token', 'Безопасность', 'Security', 'Ротация секретов и журнал инцидентов.', 'Secret rotation and incident log.', 'assets/img/team/token.webp', 3),
('София Линк', 'Sofia Link', 'Поддержка', 'Support', 'Первая линия в Telegram.', 'First line in Telegram.', 'assets/img/team/link.webp', 4);

-- --------------------------------------------------------
-- Extended DB layer (new-age enhancement)
-- --------------------------------------------------------

ALTER TABLE `cheat_features`
  ADD COLUMN `feature_tier` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'core',
  ADD COLUMN `risk_level` enum('low','medium','high') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'low',
  ADD COLUMN `cpu_impact` tinyint NOT NULL DEFAULT 1,
  ADD COLUMN `patch_note_ru` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  ADD COLUMN `patch_note_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL;

-- Additional cheats to approach 10+ per game
INSERT INTO `cheats` (`game_id`, `os_id`, `slug`, `title_ru`, `title_en`, `description_ru`, `description_en`, `image_path`, `sort_order`) VALUES
(1, NULL, 'rust-nova', 'Rust Nova', 'Rust Nova', 'Сбалансированный набор для PvP и фарма.', 'Balanced set for PvP and farming.', 'assets/img/cheats/rust-pro.webp', 4),
(1, NULL, 'rust-shadowline', 'Rust Shadowline', 'Rust Shadowline', 'Профиль с акцентом на визуальный контроль.', 'Visual control focused profile.', 'assets/img/cheats/rust-lite.webp', 5),
(1, NULL, 'rust-labs', 'Rust Labs', 'Rust Labs', 'Экспериментальный пакет с частыми апдейтами.', 'Experimental package with frequent updates.', 'assets/img/cheats/rust-pro.webp', 6),
(1, NULL, 'rust-sentinel', 'Rust Sentinel', 'Rust Sentinel', 'Пакет для долгих сессий и рейдов.', 'Package for long sessions and raids.', 'assets/img/cheats/rust-lite.webp', 7),
(1, NULL, 'rust-quant', 'Rust Quant', 'Rust Quant', 'Улучшенный информационный модуль.', 'Enhanced intel module.', 'assets/img/cheats/rust-pro.webp', 8),
(1, NULL, 'rust-horizon', 'Rust Horizon', 'Rust Horizon', 'Универсальная сборка для стандартных сценариев.', 'Universal build for standard scenarios.', 'assets/img/cheats/rust-lite.webp', 9),
(1, NULL, 'rust-vector', 'Rust Vector', 'Rust Vector', 'Профиль с ускоренным циклом обновлений.', 'Profile with accelerated update cycle.', 'assets/img/cheats/rust-pro.webp', 10),
(2, NULL, 'eft-scout', 'EFT Scout', 'EFT Scout', 'Оптимизированный набор для соло-рейдов.', 'Optimized set for solo raids.', 'assets/img/cheats/eft-raid.webp', 4),
(2, NULL, 'eft-vector', 'EFT Vector', 'EFT Vector', 'Профиль с упором на мобильность и экономику.', 'Profile focused on mobility and economy.', 'assets/img/cheats/eft-econ.webp', 5),
(2, NULL, 'eft-corex', 'EFT CoreX', 'EFT CoreX', 'Стабильный универсальный модуль.', 'Stable universal module.', 'assets/img/cheats/eft-raid.webp', 6),
(2, NULL, 'eft-marathon', 'EFT Marathon', 'EFT Marathon', 'Подход для длительных рейд-сессий.', 'Approach for long raid sessions.', 'assets/img/cheats/eft-econ.webp', 7),
(2, NULL, 'eft-prism', 'EFT Prism', 'EFT Prism', 'Расширенная аналитика окружения.', 'Extended environment analytics.', 'assets/img/cheats/eft-raid.webp', 8),
(2, NULL, 'eft-zenith', 'EFT Zenith', 'EFT Zenith', 'Премиум-профиль с приоритетными патчами.', 'Premium profile with priority patches.', 'assets/img/cheats/eft-econ.webp', 9),
(2, NULL, 'eft-axis', 'EFT Axis', 'EFT Axis', 'Минималистичный профиль под стабильный FPS.', 'Minimal profile for stable FPS.', 'assets/img/cheats/eft-raid.webp', 10),
(3, NULL, 'fn-volt', 'FN Volt', 'FN Volt', 'Профиль для агрессивного темпа игры.', 'Profile for aggressive pace.', 'assets/img/cheats/fn-storm.webp', 3),
(3, NULL, 'fn-stride', 'FN Stride', 'FN Stride', 'Сборка с мягкими настройками.', 'Build with smooth settings.', 'assets/img/cheats/fn-nimbus.webp', 4),
(3, NULL, 'fn-aether', 'FN Aether', 'FN Aether', 'Расширенный анализ боевой сцены.', 'Expanded battle-scene analysis.', 'assets/img/cheats/fn-storm.webp', 5),
(3, NULL, 'fn-sigma', 'FN Sigma', 'FN Sigma', 'Стабильный профиль для рейтинговых лобби.', 'Stable profile for ranked lobbies.', 'assets/img/cheats/fn-nimbus.webp', 6),
(3, NULL, 'fn-echo', 'FN Echo', 'FN Echo', 'Улучшенная фильтрация объектов.', 'Enhanced object filtering.', 'assets/img/cheats/fn-storm.webp', 7),
(3, NULL, 'fn-orbit', 'FN Orbit', 'FN Orbit', 'Сборка для долгих турнирных сессий.', 'Build for long tournament sessions.', 'assets/img/cheats/fn-nimbus.webp', 8),
(3, NULL, 'fn-glide', 'FN Glide', 'FN Glide', 'Лёгкий профиль с быстрой настройкой.', 'Light profile with quick setup.', 'assets/img/cheats/fn-storm.webp', 9),
(3, NULL, 'fn-proxima', 'FN Proxima', 'FN Proxima', 'Пакет с приоритетной поддержкой.', 'Package with priority support.', 'assets/img/cheats/fn-nimbus.webp', 10),
(4, NULL, 'pubg-arc', 'PUBG Arc', 'PUBG Arc', 'Универсальный матчевый профиль.', 'Universal match profile.', 'assets/img/cheats/fn-storm.webp', 3),
(4, NULL, 'pubg-axis', 'PUBG Axis', 'PUBG Axis', 'Сбалансированный пакет под ранк.', 'Balanced package for ranked.', 'assets/img/cheats/fn-nimbus.webp', 4),
(4, NULL, 'pubg-vanguard', 'PUBG Vanguard', 'PUBG Vanguard', 'Расширенный набор с мониторингом патчей.', 'Extended set with patch monitoring.', 'assets/img/cheats/fn-storm.webp', 5),
(4, NULL, 'pubg-signal', 'PUBG Signal', 'PUBG Signal', 'Профиль под командные сценарии.', 'Profile for team scenarios.', 'assets/img/cheats/fn-nimbus.webp', 6),
(4, NULL, 'pubg-nightline', 'PUBG Nightline', 'PUBG Nightline', 'Стабильная ночная конфигурация.', 'Stable night configuration.', 'assets/img/cheats/fn-storm.webp', 7),
(4, NULL, 'pubg-proto', 'PUBG Proto', 'PUBG Proto', 'Лабораторный профиль с быстрыми обновлениями.', 'Lab profile with fast updates.', 'assets/img/cheats/fn-nimbus.webp', 8),
(4, NULL, 'pubg-omega', 'PUBG Omega', 'PUBG Omega', 'Премиум-сборка с расширенным функционалом.', 'Premium build with expanded features.', 'assets/img/cheats/fn-storm.webp', 9),
(4, NULL, 'pubg-liteplus', 'PUBG Lite+', 'PUBG Lite+', 'Лёгкий вариант с улучшенным контролем.', 'Light variant with improved control.', 'assets/img/cheats/fn-nimbus.webp', 10);

-- Standard plans for newly added cheats (all non-account products missing plans)
INSERT INTO `key_plans` (`cheat_id`, `label_ru`, `label_en`, `duration_days`, `price_rub`, `sort_order`)
SELECT c.id, '30 дней', '30 days', 30, 1990, 1
FROM `cheats` c
LEFT JOIN `key_plans` p ON p.cheat_id = c.id
WHERE c.id > 26 AND c.os_id IS NULL AND p.id IS NULL;

INSERT INTO `key_plans` (`cheat_id`, `label_ru`, `label_en`, `duration_days`, `price_rub`, `sort_order`)
SELECT c.id, '90 дней', '90 days', 90, 4490, 2
FROM `cheats` c
LEFT JOIN `key_plans` p ON p.cheat_id = c.id AND p.sort_order = 2
WHERE c.id > 26 AND c.os_id IS NULL AND p.id IS NULL;

INSERT INTO `key_plans` (`cheat_id`, `label_ru`, `label_en`, `duration_days`, `price_rub`, `sort_order`)
SELECT c.id, '365 дней', '365 days', 365, 9990, 3
FROM `cheats` c
LEFT JOIN `key_plans` p ON p.cheat_id = c.id AND p.sort_order = 3
WHERE c.id > 26 AND c.os_id IS NULL AND p.id IS NULL;

-- Rich feature seed for all cheats without sufficient rows
INSERT INTO `cheat_features`
(`cheat_id`, `feature_group`, `feature_name_ru`, `feature_name_en`, `sort_order`, `feature_tier`, `risk_level`, `cpu_impact`, `patch_note_ru`, `patch_note_en`)
SELECT c.id, 'ESP', 'Расширенная телеметрия объектов', 'Extended object telemetry', 1, 'core', 'low', 2, 'Обновлено под текущий патч.', 'Updated for current patch.'
FROM `cheats` c
WHERE NOT EXISTS (SELECT 1 FROM `cheat_features` f WHERE f.cheat_id = c.id AND f.sort_order = 1);

INSERT INTO `cheat_features`
(`cheat_id`, `feature_group`, `feature_name_ru`, `feature_name_en`, `sort_order`, `feature_tier`, `risk_level`, `cpu_impact`, `patch_note_ru`, `patch_note_en`)
SELECT c.id, 'AIM', 'Адаптивные профили наведения', 'Adaptive aiming profiles', 2, 'advanced', 'medium', 3, 'Добавлена тонкая калибровка.', 'Fine-tuning was added.'
FROM `cheats` c
WHERE NOT EXISTS (SELECT 1 FROM `cheat_features` f WHERE f.cheat_id = c.id AND f.sort_order = 2);

INSERT INTO `cheat_features`
(`cheat_id`, `feature_group`, `feature_name_ru`, `feature_name_en`, `sort_order`, `feature_tier`, `risk_level`, `cpu_impact`, `patch_note_ru`, `patch_note_en`)
SELECT c.id, 'SYSTEM', 'Проверка стабильности профиля', 'Profile stability checks', 3, 'core', 'low', 1, 'Улучшена совместимость с драйверами.', 'Driver compatibility improved.'
FROM `cheats` c
WHERE NOT EXISTS (SELECT 1 FROM `cheat_features` f WHERE f.cheat_id = c.id AND f.sort_order = 3);

-- Realistic price refresh for standard and NFA plans
UPDATE `key_plans` kp
INNER JOIN `cheats` c ON c.id = kp.cheat_id
SET kp.price_rub = CASE
  WHEN c.os_id = 2 THEN CASE kp.duration_days WHEN 1 THEN 290 WHEN 3 THEN 790 WHEN 5 THEN 1290 ELSE 2390 END
  WHEN kp.duration_days = 30 THEN 1990
  WHEN kp.duration_days = 90 THEN 4490
  WHEN kp.duration_days = 365 THEN 9990
  WHEN kp.duration_days = 0 THEN 14990
  ELSE kp.price_rub
END;

-- Compatibility matrix for every cheat
INSERT INTO `cheat_requirements` (`cheat_id`, `req_group`, `req_name_ru`, `req_name_en`, `sort_order`)
SELECT c.id, v.req_group, v.req_name_ru, v.req_name_en, v.sort_order
FROM `cheats` c
CROSS JOIN (
  SELECT 'windows' AS req_group, 'Windows 10 (1903–22H2)' AS req_name_ru, 'Windows 10 (1903–22H2)' AS req_name_en, 1 AS sort_order
  UNION ALL SELECT 'windows', 'Windows 11 (21H2–24H2)', 'Windows 11 (21H2–24H2)', 2
  UNION ALL SELECT 'usb', 'USB-флешка: не требуется', 'USB flash drive: not required', 3
  UNION ALL SELECT 'cpu', 'Процессор: Intel / AMD', 'CPU: Intel / AMD', 4
  UNION ALL SELECT 'gpu', 'Видеокарта: NVIDIA / AMD', 'GPU: NVIDIA / AMD', 5
) v
WHERE NOT EXISTS (SELECT 1 FROM `cheat_requirements` r WHERE r.cheat_id = c.id LIMIT 1);

INSERT INTO `cheat_features` (`cheat_id`, `feature_group`, `feature_name_ru`, `feature_name_en`, `sort_order`)
SELECT c.id, v.feature_group, v.feature_name_ru, v.feature_name_en, v.sort_order
FROM `cheats` c
CROSS JOIN (
  SELECT 'ESP' AS feature_group, 'Подсветка игроков и лута' AS feature_name_ru, 'Player and loot highlight' AS feature_name_en, 10 AS sort_order
  UNION ALL SELECT 'ESP', 'Фильтры дистанции и видимости', 'Distance and visibility filters', 11
  UNION ALL SELECT 'ESP', 'Контейнеры и объекты мира', 'Containers and world objects', 12
  UNION ALL SELECT 'ESP', 'Скелет и боксы', 'Skeleton and boxes', 13
  UNION ALL SELECT 'ESP', 'Настраиваемые цвета и слои', 'Configurable colors and layers', 14
  UNION ALL SELECT 'AIM', 'Aimbot с настройкой FOV', 'Aimbot with FOV tuning', 20
  UNION ALL SELECT 'AIM', 'Сглаживание и humanize', 'Smoothing and humanize', 21
  UNION ALL SELECT 'AIM', 'Приоритет целей', 'Target priority', 22
  UNION ALL SELECT 'AIM', 'Silent / legit режимы', 'Silent / legit modes', 23
  UNION ALL SELECT 'MISC', 'No recoil / no sway', 'No recoil / no sway', 30
  UNION ALL SELECT 'MISC', 'Быстрые конфиг-профили', 'Fast config profiles', 31
  UNION ALL SELECT 'MISC', 'Горячие клавиши', 'Hotkeys', 32
  UNION ALL SELECT 'MISC', 'Анти-AFK и QoL', 'Anti-AFK and QoL', 33
) v
WHERE NOT EXISTS (
  SELECT 1 FROM `cheat_features` f
  WHERE f.cheat_id = c.id AND f.feature_group = v.feature_group AND f.sort_order = v.sort_order
);

-- =============================================================================
-- SECTION: basalt_practice10 (PR10)
-- Source: site/database/practice10.sql
-- =============================================================================

-- Practice 10 isolated database (official)
CREATE DATABASE IF NOT EXISTS `basalt_practice10` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `basalt_practice10`;

DROP TABLE IF EXISTS `practice10_feedback`;
CREATE TABLE `practice10_feedback` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- SECTION: db_sweetty (PR10 Sweetty)
-- Source: site/database/db_sweetty.sql
-- =============================================================================

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 18 2023 г., 13:58
-- Версия сервера: 5.7.39
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `db_sweetty`
--
CREATE DATABASE IF NOT EXISTS `db_sweetty` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_sweetty`;

-- --------------------------------------------------------

--
-- Структура таблицы `cakes`
--

DROP TABLE IF EXISTS `cakes`;
CREATE TABLE `cakes` (
  `id_cake` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `information` text COLLATE utf8mb4_unicode_ci,
  `path` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Очистить таблицу перед добавлением данных `cakes`
--

TRUNCATE TABLE `cakes`;
--
-- Дамп данных таблицы `cakes`
--

INSERT INTO `cakes` (`id_cake`, `title`, `information`, `path`) VALUES
(1, 'НЕЖНЫЙ БАРХАТ', 'Бисквитный торт ручной работы со сливочным кремом. Несмотря на простоту, имеет очень нарядный внешний вид. Идеально подходит для свадьбы или Дня Святого Валентина.', 'assets/img/cakes/gentle-velvet.jpg'),
(2, 'ИЗУМРУД', 'Очень яркий во вкусе и цвете торт! За счет контраста красного и зеленого выглядит празднично и поистине как драгоценный камень! Прекрасный подарок!', 'assets/img/cakes/emerald.jpg'),
(3, 'ПТИЧЬЕ МОЛОКО', 'Классика кондитерского искусства советских, да и российских времен. Воздушный, легкий и белоснежный!', 'assets/img/cakes/birds-milk.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
  `id_feedback` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` bigint(12) NOT NULL,
  `path` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Очистить таблицу перед добавлением данных `feedback`
--

TRUNCATE TABLE `feedback`;
-- --------------------------------------------------------

--
-- Структура таблицы `fillings`
--

DROP TABLE IF EXISTS `fillings`;
CREATE TABLE `fillings` (
  `id_filling` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Очистить таблицу перед добавлением данных `fillings`
--

TRUNCATE TABLE `fillings`;
--
-- Дамп данных таблицы `fillings`
--

INSERT INTO `fillings` (`id_filling`, `name`, `path`) VALUES
(1, 'ЯГОДНЫЙ', 'assets/img/fillings/berry.jpg'),
(2, 'ФИСТАШКОВЫЙ', 'assets/img/fillings/pistachio.jpg'),
(3, 'КОКОСОВЫЙ', 'assets/img/fillings/coconut.jpg'),
(4, 'ЛИМОННЫЙ', 'assets/img/fillings/citric.jpg'),
(5, 'ШОКОЛАДНЫЙ', 'assets/img/fillings/chocolate.jpg'),
(6, 'МЯТНЫЙ', 'assets/img/fillings/mint.jpg'),
(7, 'МЕДОВЫЙ', 'assets/img/fillings/honey.jpg'),
(8, 'ФРУКТОВЫЙ', 'assets/img/fillings/fruit.jpg'),
(9, 'ОРЕХОВЫЙ', 'assets/img/fillings/walnut.jpg'),
(10, 'КОФЕЙНЫЙ', 'assets/img/fillings/coffee.jpg'),
(11, 'КАРАМЕЛЬНЫЙ', 'assets/img/fillings/caramel.jpg'),
(12, 'ЙОГУРТОВЫЙ', 'assets/img/fillings/yoghurt.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `index_page`
--

DROP TABLE IF EXISTS `index_page`;
CREATE TABLE `index_page` (
  `id_element` int(11) NOT NULL,
  `alias` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Очистить таблицу перед добавлением данных `index_page`
--

TRUNCATE TABLE `index_page`;
--
-- Дамп данных таблицы `index_page`
--

INSERT INTO `index_page` (`id_element`, `alias`, `content`) VALUES
(1, 'banner', '<section class=\"banner first\">\r\n    <div class=\"content\">\r\n      <h1>ДОМАШНЯЯ КОНДИТЕРСКАЯ</h1>\r\n      <h2>СДЕЛАНО С ЛЮБОВЬЮ...</h2>\r\n    </div>\r\n  </section>'),
(2, 'advantages', '<section class=\"advantages\">\r\n    <div class=\"content\">\r\n      <div class=\"advantage\">\r\n        <img src=\"assets/img/icons/icon_1.png\" alt=\"Натуральные продукты\">\r\n        <p> Только<br> натуральные продукты </p>\r\n      </div>\r\n      <div class=\"advantage\">\r\n        <img src=\"assets/img/icons/icon_2.png\" alt=\"Без консервантов\">\r\n        <p> Без консервантов,<br> срок хранения 48 часов </p>\r\n      </div>\r\n      <div class=\"advantage\">\r\n        <img src=\"assets/img/icons/icon_3.png\" alt=\"Индивидуальный дизайн\">\r\n        <p> Индивидуальный дизайн </p>\r\n      </div>\r\n      <div class=\"advantage\">\r\n        <img src=\"assets/img/icons/icon_4.png\" alt=\"Разные вкусы\">\r\n        <p> Разнообразие<br> вкусов в одном торте </p>\r\n      </div>\r\n    </div>\r\n  </section>'),
(3, 'select', '<section class=\"select\">\r\n    <div class=\"content\">\r\n      <h2>А ЧЕГО ЖЕЛАЕТЕ ВЫ?</h2>\r\n      <div class=\"select__cakes\">\r\n        <div class=\"select__cake\">\r\n          <img src=\"assets/img/select-cake-1.jpg\" alt=\"Выбрать готовый торт\">\r\n          <a href=\"cakes.php\">ВЫБРАТЬ ГОТОВЫЙ</a>\r\n        </div>\r\n        <div class=\"select__cake\">\r\n          <img src=\"assets/img/select-cake-2.jpg\" alt=\"Заказать свой торт\">\r\n          <a href=\"index.php#feedback\">СОЗДАТЬ СВОЙ</a>\r\n        </div>\r\n      </div>\r\n    </div>\r\n  </section>'),
(4, 'adout', '<section class=\"about\">\r\n    <div class=\"about__wrap\">\r\n      <div class=\"about__text\">\r\n        <p>\r\n          Меня зовут Екатерина - я профессиональный кондитер и хозяйка Моей\r\n          Сладости.\r\n        </p>\r\n        <p>Мой девиз - честная и открытая работа, индивидуальный подход.</p>\r\n        <p>\r\n          В моих тортиках только натуральные ингредиенты и качественные\r\n          продукты.\r\n        </p>\r\n      </div>\r\n    </div>\r\n  </section>'),
(5, 'reviews', '<section class=\"reviews\">\r\n    <div class=\"content\">\r\n      <div class=\"review\">\r\n        <img src=\"/assets/img/reviews/review-1.jpg\" alt=\"review-1\">\r\n        <div class=\"review__text\">\r\n          <blockquote>\r\n            Внимательное отношение к клиенту, вкуснющий торт и некусачие цены! Огромное спасибо за удовольствие! Екатерине творческих успехов!\r\n          </blockquote>\r\n        </div>\r\n      </div>\r\n      <div class=\"review\">\r\n        <img src=\"/assets/img/reviews/review-2.jpg\" alt=\"review-2\">\r\n        <div class=\"review__text\">\r\n          <blockquote>\r\n            Торт натуральный, выполнен искусно и подложка лишь подчеркнула торжественное лакомство! Спасибо и успехов вашему бизнесу!\r\n          </blockquote>\r\n        </div>\r\n      </div>\r\n      <div class=\"review\">\r\n        <img src=\"/assets/img/reviews/review-3.jpg\" alt=\"review-3\">\r\n        <div class=\"review__text\">\r\n          <blockquote>\r\n            Изысканный вкус тортиков не оставил равнодушным ни одного сотрудника нашего Холдинга! Огромное спасибо за настроение, сладкую жизнь и превосходный вкус и дизайн Ваших тортов!\r\n          </blockquote>\r\n        </div>\r\n      </div>\r\n    </div>\r\n  </section>'),
(6, 'feedback', '<section class=\"feedback\" id=\"feedback\">\r\n    <div class=\"content\">\r\n      <h2>ГОТОВ К ЗАКАЗУ ИЛИ НЕ МОЖЕШЬ ОПРЕДЕЛИТЬСЯ? ЗВОНИ!</h2>\r\n      <a href=\"tel:+72223332255\">\r\n        <h3>+7 (222) 333-22-55</h3>\r\n      </a>\r\n      <h4>ИЛИ ОСТАВЬ НАМ СВОЙ НОМЕР И МЫ ПЕРЕЗВОНИМ САМИ</h4>\r\n\r\n\r\n      <form action=\"add_feedback.php\" method=\"post\" enctype=\"multipart/form-data\">\r\n        <div class=\"form__contact\">\r\n          <input type=\"text\" name=\"name\" placeholder=\"НАПИШИТЕ СВОЕ ИМЯ\" required pattern=\"[A-Za-zА-Яа-яЁё]{2,}\">\r\n          <input type=\"tel\" name=\"tel-number\" id=\"tel-number\" title=\" например, 89041041706\"\r\n            placeholder=\"НОМЕР ТЕЛЕФОНА\" required pattern=\"[0-9]{11}\">\r\n        </div>\r\n\r\n        <div class=\"form__download\">\r\n          ЕСТЬ ФОТО ПРИМЕРА?\r\n          <label for=\"download\">ЗАГРУЖАЙ ЗДЕСЬ</label>\r\n          <input type=\"file\" id=\"download\" name=\"download\" accept=\".png, .jpg, .jpeg\">\r\n        </div>\r\n\r\n        <div class=\"form__submit\">\r\n          <input type=\"checkbox\" name=\"personal\" id=\"personal\">\r\n          <label for=\"personal\">\r\n            Я СОГЛАСЕН НА ОБРАБОТКУ ПЕРСОНАЛЬНЫХ ДАННЫХ\r\n          </label>\r\n        </div>\r\n        <input type=\"submit\" value=\"ПООБЩАТЬСЯ\">\r\n      </form>\r\n    </div>\r\n  </section>'),
(7, 'map', '<section class=\"map\">\r\n    <div class=\"content\">\r\n      <h2>МЫ НА КАРТЕ</h2>\r\n      <iframe\r\n        src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1185.8234258591788!2d57.224571220779985!3d65.14509500553477!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4477316d89272b07%3A0x40a2e859a9bf1d6c!2z0JHRgNC40YHRgtC-0LvRjA!5e0!3m2!1sru!2sru!4v1644919434959!5m2!1sru!2sru\"\r\n         style=\"border: 0\" allowfullscreen=\"\" loading=\"lazy\"></iframe>\r\n      <h2>г.Печора, ул. Первомайская д.25</h2>\r\n    </div>\r\n  </section>');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cakes`
--
ALTER TABLE `cakes`
  ADD PRIMARY KEY (`id_cake`);

--
-- Индексы таблицы `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id_feedback`);

--
-- Индексы таблицы `fillings`
--
ALTER TABLE `fillings`
  ADD PRIMARY KEY (`id_filling`);

--
-- Индексы таблицы `index_page`
--
ALTER TABLE `index_page`
  ADD PRIMARY KEY (`id_element`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cakes`
--
ALTER TABLE `cakes`
  MODIFY `id_cake` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id_feedback` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `fillings`
--
ALTER TABLE `fillings`
  MODIFY `id_filling` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `index_page`
--
ALTER TABLE `index_page`
  MODIFY `id_element` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- =============================================================================
-- SECTION: basalt_practice13 (PR13)
-- Source: site/database/practice13.sql
-- =============================================================================

-- Practice 13 isolated database (music catalog)
CREATE DATABASE IF NOT EXISTS `basalt_practice13` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `basalt_practice13`;

DROP TABLE IF EXISTS `music_tracks`;
DROP TABLE IF EXISTS `music_albums`;
DROP TABLE IF EXISTS `music_groups`;

CREATE TABLE `music_groups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `founded_year` smallint unsigned NOT NULL,
  `style` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `music_albums` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int unsigned NOT NULL,
  `title` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `release_year` smallint unsigned NOT NULL,
  `release_country` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_group_id` (`group_id`),
  CONSTRAINT `fk_albums_group` FOREIGN KEY (`group_id`) REFERENCES `music_groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `music_tracks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `album_id` int unsigned NOT NULL,
  `title` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_album_id` (`album_id`),
  CONSTRAINT `fk_tracks_album` FOREIGN KEY (`album_id`) REFERENCES `music_albums` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `music_groups` (`slug`, `name`, `country`, `founded_year`, `style`) VALUES
('aerosmith', 'Aerosmith', 'США', 1970, 'хард-рок'),
('pink-floyd', 'Pink Floyd', 'Великобритания', 1965, 'психоделический-рок');

INSERT INTO `music_albums` (`group_id`, `title`, `release_year`, `release_country`, `cover_path`) VALUES
(2, 'The Dark Side of the Moon', 1973, 'Великобритания', 'assets/covers/dark-side.jpg'),
(2, 'Wish You Were Here', 1975, 'Великобритания', 'assets/covers/wish-you-were-here.jpg'),
(1, 'Greatest Hits', 1999, 'США', 'assets/covers/aerosmith-hits.jpg'),
(2, 'Abbey Road', 1969, 'Великобритания', 'assets/covers/abbey-road.jpg');

INSERT INTO `music_tracks` (`album_id`, `title`, `note`) VALUES
(3, 'Back in the Saddle', 'подробнее...'),
(3, 'Last Child', 'подробнее...'),
(3, 'Rats in the Cellar', 'подробнее...'),
(3, 'Combination', 'подробнее...'),
(3, 'Sick As a Dog', 'подробнее...'),
(2, 'Shine On You Crazy Diamond (Part One)', NULL),
(2, 'Welcome to the Machine', NULL),
(2, 'Have a Cigar', NULL),
(2, 'Wish You Were Here', NULL);

