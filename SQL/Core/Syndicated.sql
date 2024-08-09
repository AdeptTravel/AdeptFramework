DROP TABLE IF EXISTS `FeedChannel`;
CREATE TABLE `FeedChannel` (
  `id` INT(11) UNSIGNED NOT NULL,
  `url` VARCHAR(255) NOT NULL UNIQUE,
  `title` VARCHAR(32) NOT NULL,
  `status` TINYINT(3) NOT NULL DEFAULT 0,
  `created` TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `FeedItem`;
CREATE TABLE `FeedItem` (
  `id` INT(11) UNSIGNED NOT NULL,
  `channel` INT UNSIGNED NOT NULL,
  `category` INT UNSIGNED DEFAULT 1,
  `url` VARCHAR(255) NOT NULL UNIQUE,
  `title` VARCHAR(32) NOT NULL,
  `description` TEXT NOT NULL,
  `status` DATETIME NOT NULL,
  `created` TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `FeedCategory`;
CREATE TABLE `FeedCategory` (
  `id` INT(11) UNSIGNED NOT NULL,
  `title` INT UNSIGNED NOT NULL UNIQUE,
  `created` TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

INSERT INTO syndicated_feed
|  1 | http://www.travelpulse.com/rss/news.rss         | TravelPulse          | NULL  |         1 |
|  2 | https://feeds.feedburner.com/breakingtravelnews | Breaking Travel News | NULL  |         1 |
|  3 | https://www.traveldailymedia.com/feed           | Travel Daily         | NULL  |         1 |
|  4 | https://skift.com/feed                          | Skift                | NULL  |         1 |
|  6 | https://www.todaystraveller.net/feed/           | Todays Traveler      | NULL  |         1 |
