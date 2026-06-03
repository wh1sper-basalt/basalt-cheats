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
