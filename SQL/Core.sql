# Has forign key
DROP TABLE IF EXISTS `Content`;
DROP TABLE IF EXISTS `MenuItem`;
DROP TABLE IF EXISTS `LogAuth`;
DROP TABLE IF EXISTS `Request`;
DROP TABLE IF EXISTS `Session`;


# No forign key
DROP TABLE IF EXISTS `IPAddress`;
DROP TABLE IF EXISTS `LogAuth`;
DROP TABLE IF EXISTS `Media`;
DROP TABLE IF EXISTS `Menu`;
DROP TABLE IF EXISTS `Redirect`;
DROP TABLE IF EXISTS `Route`;
DROP TABLE IF EXISTS `Url`;
DROP TABLE IF EXISTS `User`;
DROP TABLE IF EXISTS `Useragent`;

##
## Core tables with no forign key
##

CREATE TABLE `IPAddress` (
  `id`              INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `ipAddress`       VARCHAR(45) NOT NULL UNIQUE,
  `encoded`         VARBINARY(16) NOT NULL,
  `status`          ENUM('Active', 'Block') NOT NULL DEFAULT 'Active',
  `createdOn`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedOn`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idxEncoded (`encoded`),
  INDEX idxStatus (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;


CREATE TABLE `Media` (
  `id`              INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `type`            ENUM('Image', 'Video', 'Audio'),
  `path`            VARCHAR(256) NOT NULL,
  `file`            VARCHAR(256) NOT NULL UNIQUE,
  `alias`           VARCHAR(256) NOT NULL UNIQUE,
  `mime`            VARCHAR(32) NOT NULL,
  `extension`       VARCHAR(5) NOT NULL,
  `width`           INT DEFAULT 0,
  `height`          INT DEFAULT 0,
  `duration`        INT DEFAULT 0,
  `size`            INT DEFAULT 0,
  `title`           VARCHAR(128) DEFAULT '',
  `caption`         TEXT DEFAULT '',
  `summary`         TEXT DEFAULT '',
  `status`          ENUM('Active', 'Archive', 'Inactive', 'Trash') NOT NULL DEFAULT 'Active',
  `createdOn`       DATETIME DEFAULT NOW(),
  `updatedOn`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idxPath (`path`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `Menu` (
  `id`              INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `title`           VARCHAR(64) NOT NULL,
  `css`             VARCHAR(64) DEFAULT NULL,
  `isSecure`        BOOLEAN DEFAULT FALSE,
  `status`          ENUM('Active', 'Inactive', 'Trash') NOT NULL DEFAULT 'Active',
  `createdOn`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedOn`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Redirect (
  `id`              INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `route`           VARCHAR(512) NOT NULL UNIQUE,
  `redirect`        VARCHAR(512) NOT NULL,
  `code`            SMALLINT UNSIGNED NOT NULL DEFAULT 301,
  `status`          ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
  `createdOn`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedOn`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

CREATE TABLE Route (
  `id`             INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `route`           VARCHAR(512) NOT NULL UNIQUE,
  `component`       VARCHAR(64) DEFAULT NULL,
  `view`            VARCHAR(64) DEFAULT NULL,
  `template`        VARCHAR(64) DEFAULT NULL,
  -- Formats
  `html`            BOOLEAN DEFAULT FALSE,
  `json`            BOOLEAN DEFAULT FALSE,
  `xml`             BOOLEAN DEFAULT FALSE,
  `csv`             BOOLEAN DEFAULT FALSE,
  `pdf`             BOOLEAN DEFAULT FALSE,
  `zip`             BOOLEAN DEFAULT FALSE,
  -- Include in Sitemap
  `sitemap`         BOOLEAN DEFAULT FALSE,
  -- Security Access
  `allowGet`        BOOLEAN DEFAULT FALSE,
  `allowPost`       BOOLEAN DEFAULT FALSE,
  `allowEmail`      BOOLEAN DEFAULT FALSE,
  `isSecure`        BOOLEAN DEFAULT FALSE,
  `isCacheable`     BOOLEAN DEFAULT FALSE,
  -- General
  `status`          ENUM('Active', 'Block', 'Inactive', 'Trash') NOT NULL DEFAULT 'Active',
  `createdOn`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedOn`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_component (`component`),
  INDEX idx_status (`status`)
);

CREATE TABLE `Url` (
  `id`              INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `url`             TEXT NOT NULL UNIQUE,
  `scheme`          ENUM('http', 'https') NOT NULL DEFAULT 'https',
  `host`            VARCHAR(256) NOT NULL,
  `path`            TEXT,
  `parts`           JSON CHECK (JSON_VALID(`parts`)),
  `file`            VARCHAR(512) NOT NULL,
  `extension`       ENUM('aac', 'avi', 'bmp', 'css', 'csv', 'doc', 'docx', 'eml', 'flac', 'gif', 'gltf', 'gz', 'htm', 'html', 'ico', 'iges', 'igs', 'jpeg', 'jpg', 'js', 'json', 'jsonld', 'md', 'mesh', 'mime', 'mov', 'mp3', 'mp4', 'mpeg', 'mpg', 'msh', 'obj', 'ogg', 'ogv', 'otf', 'pdf', 'png', 'ppt', 'pptx', 'svg', 'swf', 'tif', 'tiff', 'ttf', 'txt', 'vrml', 'wav', 'weba', 'webm', 'webp', 'woff', 'woff2', 'wrl', 'xls', 'xlsx', 'xml', 'zip' ) NOT NULL,
  `type`            ENUM('Archive', 'Audio', 'CSS', 'CSV', 'Font', 'HTML', 'Image', 'JSON', 'JavaScript', 'PDF', 'Text', 'Video', 'XML') NOT NULL, `mime`        ENUM( 'text/plain', 'text/html', 'text/css', 'text/javascript', 'text/xml', 'text/csv', 'text/markdown', 'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'image/bmp', 'image/tiff', 'image/vnd.microsoft.icon', 'audio/mpeg', 'audio/ogg', 'audio/wav', 'audio/webm', 'audio/aac', 'audio/flac', 'video/mp4', 'video/mpeg', 'video/ogg', 'video/webm', 'video/avi', 'video/quicktime', 'application/javascript', 'application/json', 'application/xml', 'application/pdf', 'application/zip', 'application/gzip', 'application/x-www-form-urlencoded', 'application/octet-stream', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/x-shockwave-flash', 'application/ld+json', 'application/vnd.api+json', 'multipart/form-data', 'multipart/byteranges', 'multipart/alternative', 'multipart/mixed', 'font/ttf', 'font/otf', 'font/woff', 'font/woff2', 'model/iges', 'model/mesh', 'model/vrml', 'model/gltf+json', 'model/obj', 'message/http', 'message/imdn+xml', 'message/partial', 'message/rfc822') NOT NULL,
  `status`          ENUM('Active', 'Block') NOT NULL DEFAULT 'Active',
  `createdOn`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedOn`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idxHost (`host`),
  INDEX idxPath (`path`),
  INDEX idxMime (`mime`),
  INDEX idxStatus (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `User` (
  `id`              INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `username`        VARCHAR(128) NOT NULL,
  `password`        VARCHAR(255) NOT NULL,
  `firstName`       VARCHAR(64) NOT NULL,
  `middleName`      VARCHAR(64),
  `lastName`        VARCHAR(64) NOT NULL,
  `dob`             DATE DEFAULT NULL,
  `status`          ENUM('Active', 'Block', 'Inactive', 'Locked') NOT NULL DEFAULT 'Inactive',
  `createdOn`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedOn`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `verifiedAt`      TIMESTAMP DEFAULT NULL,
  `validatedAt`     TIMESTAMP DEFAULT NULL,
  INDEX idx_status (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `Useragent` (
  `id`           INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `useragent`    VARCHAR(512) NOT NULL UNIQUE,
  `friendly`     VARCHAR(128) NOT NULL UNIQUE,
  `browser`      VARCHAR(64),
  `os`           VARCHAR(64),
  `device`       VARCHAR(64),
  `type`         VARCHAR(32),
  `isDetected`   TINYINT(1) NOT NULL DEFAULT 0,
  `status`       ENUM('Allow', 'Block') NOT NULL DEFAULT 'Allow',
  `createdOn`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedOn`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

##
## Has forign key
##

CREATE TABLE `Content` (
  `id`              INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `parentId`        INT UNSIGNED DEFAULT 0,
  `routeId`         INT UNSIGNED,
  `imageId`         INT UNSIGNED DEFAULT NULL,
  `type`            ENUM('Article', 'Category', 'Component', 'Tag') NOT NULL,
  `subtype`         ENUM('', 'Blog', 'News', 'Video') DEFAULT '',
  `title`           VARCHAR(128) NOT NULL,
  `summary`         TEXT DEFAULT NULL,
  `content`         TEXT DEFAULT NULL,
  `seo`             JSON CHECK (JSON_VALID(`seo`)),
  `media`           JSON CHECK (JSON_VALID(`media`)),
  `params`          JSON CHECK (JSON_VALID(`params`)),
  `status`          ENUM('Active', 'Archive', 'Inactive', 'Trash') NOT NULL DEFAULT 'Active',
  `activeOn`        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `archiveOn`       TIMESTAMP DEFAULT NULL,
  `createdOn`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedOn`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `displayOrder`    INT DEFAULT 0,
  FOREIGN KEY (`routeId`) REFERENCES `Route`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`imageId`) REFERENCES `Media`(`id`) ON DELETE SET NULL,
  INDEX idxType (`type`),
  INDEX idxStatus (`status`),
  INDEX idxPublishAt (`activeOn`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `MenuItem` (
  `id`              INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `menuId`          INT UNSIGNED DEFAULT NULL,
  `parentId`        INT UNSIGNED DEFAULT NULL,
  `routeId`         INT UNSIGNED DEFAULT NULL,
  `url`             VARCHAR(256) DEFAULT NULL,
  `title`           VARCHAR(128) NOT NULL,
  `image`           VARCHAR(256) DEFAULT NULL,
  `fa`              VARCHAR(64) DEFAULT NULL,
  `css`             VARCHAR(64) DEFAULT NULL,
  `params`          JSON DEFAULT NULL,
  `status`          ENUM('Active', 'Inactive', 'Trash') NOT NULL DEFAULT 'Active',
  `activeOn`       DATETIME DEFAULT CURRENT_TIMESTAMP,
  `createdOn`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedOn`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `displayOrder`    INT DEFAULT 0,
  FOREIGN KEY (`menuId`)   REFERENCES `Menu`(`id`)     ON DELETE CASCADE,
  FOREIGN KEY (`parentId`) REFERENCES `MenuItem`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`routeId`)  REFERENCES `Route`(`id`)    ON DELETE SET NULL,
  INDEX `idxMenuId` (`menuId`),
  INDEX `idxStatus` (`status`),
  INDEX `idxDisplayOrder` (`displayOrder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `Session` (
  `id`          INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `userId`      INT UNSIGNED DEFAULT NULL,
  `token`       VARCHAR(64) NOT NULL UNIQUE,
  `status`      enum('Active', 'Block', 'Inactive') NOT NULL DEFAULT 'Active',
  `createdOn`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_userId (`userId`),
  INDEX idx_status (`status`),
  FOREIGN KEY (userId) REFERENCES User(id) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

CREATE TABLE Request (
  `id`              INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `sessionId`       INT UNSIGNED NOT NULL,
  `ipAddressId`     INT UNSIGNED NOT NULL,
  `useragentId`     INT UNSIGNED NOT NULL,
  `routeId`         INT UNSIGNED,
  `redirectId`      INT UNSIGNED,
  `urlId`           INT UNSIGNED NOT NULL,
  `code`            SMALLINT NOT NULL,
  `status`          ENUM('Active', 'Block', 'Error') NOT NULL DEFAULT 'Active',
  `createdOn`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idxSessionId` (`sessionId`),
  INDEX `idxIPAddressId` (`ipAddressId`),
  INDEX `idxUseragentId` (`userAgentId`),
  INDEX `idxRouteId` (`routeId`),
  INDEX `idxUrlId` (`urlId`),
  FOREIGN KEY (`sessionId`) REFERENCES `Session`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`ipAddressId`) REFERENCES `IPAddress`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`useragentId`) REFERENCES `Useragent`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`routeId`) REFERENCES `Route`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`urlId`) REFERENCES `Url`(`id`) ON DELETE CASCADE
);

CREATE TABLE `LogAuth` (
  `sessionId`   INT UNSIGNED NOT NULL,
  `requestId`   INT UNSIGNED NOT NULL,
  `useragentId` INT UNSIGNED NOT NULL,
  `ipAddressId` INT UNSIGNED NOT NULL,
  `username`    VARCHAR(128) NOT NULL,
  `result`      ENUM('Success', 'Fail', 'Delay'),
  `reason`      ENUM('', 'Deactivated', 'Nonexistent', 'Password', 'Verified', 'Validated'),
  `createdOn`   DATETIME NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`username`, `createdOn`),
  FOREIGN KEY (`sessionId`) REFERENCES `Session`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`requestId`) REFERENCES `Request`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`useragentId`) REFERENCES `Useragent`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`ipAddressId`) REFERENCES `IPAddress`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;


##
## Default Data
##

INSERT INTO `Menu` (`title`, `isSecure`) VALUES ('Main Menu', TRUE), ('Social Menu', FALSE);

INSERT INTO `Redirect` (`route`, `redirect`) VALUES
('', 'dashboard');

INSERT INTO `Route`
(`route`, `component`, `view`, `template`, `html`, `json`, `xml`, `csv`, `pdf`, `zip`, `sitemap`, `allowGet`, `allowPost`, `allowEmail`, `isSecure`, `status`)
VALUES
('login',                 'Auth',      'Login',          'Minimal', 1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 'Active'),
('logout',                'Auth',      'Logout',         'Minimal', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 'Active'),
('forgot',                'Auth',      'Forgot',         'Minimal', 1, 1, 0, 0, 0, 0, 0, 1, 1, 1, 0, 'Active'),
('verify',                'Auth',      'Verify',         'Minimal', 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 0, 'Active'),
('signup',                'User',      'Signup',         'Minimal', 1, 1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 'Active'),
('dashboard',             'Dashboard', 'Dashboard',      '',        1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 'Active'),
('route',                 'Route',     'Routes',         '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('route/edit',            'Route',     'Route',          '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('route/notfound',        'Route',     'NotFound',       '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('route/redirect',        'Route',     'Redirect',       '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('menu/',                 'Menu',      'Menus',          '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('menu/edit',             'Menu',      'Menu',           '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 1, 1, 'Active'),
('menu/item',             'Menu',      'MenuItems',      '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('menu/item/edit',        'Menu',      'MenuItem',       '',        1, 1, 0, 0, 0, 0, 0, 1, 1, 1, 1, 'Active'),
('media/image',           'Media',     'Images',         '',        1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('media/image/edit',      'Media',     'Image',          '',        1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('content/article',       'Content',   'Articles',       '',        1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('content/article/edit',  'Content',   'Article',        '',        1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active'),
('content/category',      'Content',   'Categories',     '',        1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 'Active'),
('content/category/edit', 'Content',   'Category',       '',        1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 'Active');



INSERT INTO `MenuItem` (`menuId`, `parentId`, `routeId`, `displayOrder`, `title`, `fa`) VALUES
(1,  NULL,     6, 1, 'Dashboard', 'fa-solid fa-chart-column'),
(1,  NULL,  NULL, 2, 'Content', 'fa-solid fa-file-lines'),
(1,     2,     17, 1, 'Articles', ''),
(1,     2,    19, 2, 'Categories', ''),
(1,  NULL,  NULL, 3, 'Media', 'fa-solid fa-photo-film'),
(1,     6,    15, 1, 'Images', ''),
(1,  NULL,  NULL, 4, 'Menu', 'fa-solid fa-table-list'),
(1,     8,    11, 1, 'Menus', ''),
(1,     8,    13, 2, 'Menu Items', ''),
(1,  NULL,  NULL, 5, 'System', 'fa-solid fa-gear'),
(1,    11,     7, 1, 'Routes', '');


INSERT INTO `User` (`username`, `password`, `firstname`, `middlename`, `lastname`, `status`, `dob`, `createdOn`, `verifiedAt`, `validatedAt`) VALUES (
  'brandon@adept.travel',
  '$2y$10$BqTlFw582n78e7EUwFFRR.Q9mzTkjCObqzu95Aj5Q0s6FtYEeo7bG',
  'Brandon',
  'Joseph',
  'Yaniz',
  'Active',
  '1979-08-08 00:00:00',
  '2023-11-02 15:43:10',
  '2023-11-02 20:50:55',
  '2023-11-02 20:50:55'
);