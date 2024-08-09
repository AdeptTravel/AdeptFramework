DROP TABLE IF EXISTS `Journal`;
CREATE TABLE `Journal` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user` INT UNSIGNED NOT NULL,
  `trip` INT UNSIGNED NOT NULL,
  `title` VARCHAR(128) NOT NULL,
  `summary` TEXT DEFAULT '',
  `start` DATETIME DEFAULT '0000-00-00 00:00:00',
  `end` DATETIME DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `JournalEntry`;
CREATE TABLE `JournalEntry` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent` INT UNSIGNED DEFAULT 0,
  `route` INT UNSIGNED DEFAULT 0,
  `version` INT UNSIGNED DEFAULT 0,
  `type` ENUM('Article', 'Category', 'Tag'),
  `subtype` ENUM('', 'Blog', 'News', 'Video') DEFAULT '',
  `title` VARCHAR(128) NOT NULL,
  `summary` TEXT DEFAULT '',
  `content` TEXT DEFAULT '',
  `seo` TEXT DEFAULT '{}',
  `media` TEXT DEFAULT '{}',
  `params` TEXT DEFAULT '{}',
  `status` TINYINT DEFAULT 1,
  `publish` DATETIME DEFAULT NOW(),
  `archive` DATETIME DEFAULT '2256-03-09 00:00:00',
  `created` DATETIME DEFAULT NOW(),
  `modified` DATETIME DEFAULT NOW(),
  `order` INT DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `JournalMedia`;
CREATE TABLE `JournalMedia` (
  `content` INT UNSIGNED NOT NULL,
  `media` INT UNSIGNED NOT NULL,
  `type`  ENUM('', 'Intro', 'Full') DEFAULT '',
  `status` TINYINT DEFAULT 1,
  `order` INT DEFAULT 0,
  PRIMARY KEY (`content`, `media`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `JournalShare`;
CREATE TABLE `JournalShare` (
  `content` INT UNSIGNED NOT NULL,
  `media` INT UNSIGNED NOT NULL,
  `type`  ENUM('', 'Intro', 'Full') DEFAULT '',
  `status` TINYINT DEFAULT 1,
  `order` INT DEFAULT 0,
  PRIMARY KEY (`content`, `media`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;
