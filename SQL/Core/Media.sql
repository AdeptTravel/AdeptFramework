DROP TABLE IF EXISTS `Media`;
CREATE TABLE `Media` (
  `id`            INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `type`          ENUM('Image', 'Video', 'Audio'),
  `path`          VARCHAR(256) NOT NULL,
  `file`          VARCHAR(256) NOT NULL UNIQUE,
  `alias`         VARCHAR(256) NOT NULL UNIQUE,
  `mime`          VARCHAR(32) NOT NULL,
  `extension`     VARCHAR(5) NOT NULL,
  `width`         INT DEFAULT 0,
  `height`        INT DEFAULT 0,
  `duration`      INT DEFAULT 0,
  `size`          INT DEFAULT 0,
  `title`         VARCHAR(128) DEFAULT '',
  `caption`       TEXT DEFAULT '',
  `summary`       TEXT DEFAULT '',
  `status`        ENUM('Publish', 'Unpublish', 'Archive', 'Trash') NOT NULL DEFAULT 'Publish',
  `createdOn`     DATETIME DEFAULT NOW(),
  `modifiedAt`    DATETIME DEFAULT '0000-00-00 00:00:00',
  INDEX idxPath (`path`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;
