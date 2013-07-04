-- MySQL
DROP TABLE IF EXISTS `track`;
CREATE TABLE `track` (
  `id` INT UNSIGNED NOT NULL,
  `mbid` BINARY(16) NULL,
  `number` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `artist` varchar(64) NOT NULL,
  `title` varchar(64) NOT NULL,
  `date` INT UNSIGNED NOT NULL,
  `is_resolved` bit DEFAULT NULL,
  `is_uploaded` bit DEFAULT NULL,
  `url` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mbid` (`mbid`),
  KEY `number` (`number`),
  KEY `date` (`date`)
) CHARSET=utf8;

-- SQLite
CREATE TABLE `track` (
  `id` binary(16) NOT NULL,
  `artist` varchar(64) NOT NULL,
  `title` varchar(64) NOT NULL,
  `date` int(10) NOT NULL,
  `is_resolved` bit(1) DEFAULT NULL,
  `is_uploaded` bit(1) DEFAULT NULL,
  `url` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
CREATE INDEX `idx_track_date` ON `track` (`date` DESC);
