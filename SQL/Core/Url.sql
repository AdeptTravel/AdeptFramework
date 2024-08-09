DROP TABLE IF EXISTS `Url`;
CREATE TABLE `Url` (
  `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `url`       VARCHAR(512) NOT NULL UNIQUE,
  `scheme`    VARCHAR(5) NOT NULL,
  `host`      VARCHAR(256) NOT NULL,
  `path`      VARCHAR(256),
  `parts`     TEXT,
  `file`      VARCHAR(256) NOT NULL,
  `extension` VARCHAR(5) NOT NULL,
  `type`      VARCHAR(16) NOT NULL,
  `mime`      VARCHAR(32) NOT NULL,
  `block`     TINYINT(3) NOT NULL DEFAULT 0,
  `created`   TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;
