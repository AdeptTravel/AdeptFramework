DROP TABLE IF EXISTS `Media`;
CREATE TABLE `Media` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `type`        ENUM('Image', 'Video', 'Audio'),
  `path`        VARCHAR(256) NOT NULL,
  `file`        VARCHAR(256) NOT NULL UNIQUE,
  `alias`       VARCHAR(256) NOT NULL UNIQUE,
  `mime`        VARCHAR(32) NOT NULL,
  `extension`   VARCHAR(5) NOT NULL,
  `width`       INT DEFAULT 0,
  `height`      INT DEFAULT 0,
  `duration`    INT DEFAULT 0,
  `size`        INT DEFAULT 0,
  `title`       VARCHAR(128) DEFAULT '',
  `caption`     TEXT DEFAULT '',
  `summary`     TEXT DEFAULT '',
  `status`      TINYINT DEFAULT 1,
  `created`     DATETIME DEFAULT NOW(),
  `modified`    DATETIME DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;
