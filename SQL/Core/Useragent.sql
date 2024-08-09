DROP TABLE IF EXISTS `Useragent`;
CREATE TABLE `Useragent` (
  `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `useragent` VARCHAR(512) NOT NULL UNIQUE,
  `friendly`  VARCHAR(96) NOT NULL UNIQUE,
  `browser`   VARCHAR(32),
  `os`        VARCHAR(16),
  `device`    VARCHAR(32),
  `type`      VARCHAR(16),
  `detected`  TINYINT(1) NOT NULL,
  `block`     TINYINT(1) NOT NULL DEFAULT 0,
  `created`   DATETIME DEFAULT NOW(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;
