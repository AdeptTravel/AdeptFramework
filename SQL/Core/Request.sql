DROP TABLE IF EXISTS `Request`;
CREATE TABLE `Request` (
  `session` INT UNSIGNED NOT NULL,
  `ipaddress` INT NOT NULL,
  `useragent` INT NOT NULL,
  `route` INT UNSIGNED NOT NULL,
  `url` INT UNSIGNED NOT NULL,
  `code` SMALLINT NOT NULL,
  `block` TINYINT(3) NOT NULL DEFAULT 0,
  `created` DATETIME NOT NULL DEFAULT NOW(),
  `milisec` SMALLINT NOT NULL,
  PRIMARY KEY (`session`, `created`, `milisec`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;