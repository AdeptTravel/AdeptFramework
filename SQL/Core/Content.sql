DROP TABLE IF EXISTS `Content`;
CREATE TABLE `Content` (
  `id`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent`   INT UNSIGNED DEFAULT 0,
  `route`    INT UNSIGNED DEFAULT 0,
  `type`     ENUM('Article', 'Category', 'Component', 'Tag'),
  `subtype`  ENUM('', 'Blog', 'News', 'Video') DEFAULT '',
  `title`    VARCHAR(128) NOT NULL,
  `summary`  TEXT DEFAULT '',
  `content`  TEXT DEFAULT '',
  `image`    INT UNSIGNED DEFAULT 0,
  `seo`      TEXT DEFAULT '{}',
  `media`    TEXT DEFAULT '{}',
  `params`   TEXT DEFAULT '{}',
  `status`   TINYINT DEFAULT 1,
  `publish`  DATETIME DEFAULT NOW(),
  `archive`  DATETIME DEFAULT NOW(),
  `created`  DATETIME DEFAULT NOW(),
  `modified` DATETIME DEFAULT NOW(),
  `order`    INT DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

INSERT INTO Content (`parent`, `type`, `title`, `order`) VALUES
(0, 'Category', 'Blog', 1),
(0, 'Category', 'News', 2),
(0, 'Category', 'Company', 3),
(3, 'Category', 'Awards &amp; Honors', 1),
(3, 'Category', 'News', 2),
(3, 'Category', 'Media', 3),
(6, 'Category', 'Images &amp; Video', 1),
(6, 'Category', 'Press Release', 2),
(3, 'Category', 'Our Team', 4);

DROP TABLE IF EXISTS `ContentTag`;
CREATE TABLE `ContentTag` (
  `article` INT UNSIGNED NOT NULL,
  `tag`     INT UNSIGNED NOT NULL,
  PRIMARY KEY (`content`, `tag`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `ContentTags`;
CREATE TABLE `ContentTags` (
  `content` INT UNSIGNED NOT NULL,
  `tag`     VARCHAR(128) NOT NULL UNIQUE,
  PRIMARY KEY (`content`, `tag`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;
