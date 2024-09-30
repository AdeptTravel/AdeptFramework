DROP TABLE IF EXISTS `Menu`;
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

INSERT INTO `Menu` (`title`, `isSecure`) VALUES ('Main Menu', TRUE), ('Social Menu', FALSE);

DROP TABLE IF EXISTS `MenuItem`;
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
  `status`          ENUM('active', 'inactive', 'block') NOT NULL DEFAULT 'active',
  `publishAt`       DATETIME DEFAULT CURRENT_TIMESTAMP,
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



(1, 0, 29, 2, 'Routes', 'fa-solid fa-route'),
(1, 0, 0,  3, 'Menus', 'fa-solid fa-list'),
(1, 3, 36, 1, 'Menus', ''),
(1, 3, 37, 2, 'Menu Items', ''),
(1, 0, 0,  4, 'Content', 'fa-solid fa-file-lines'),
(1, 6, 15, 1, 'Articles', ''),
(1, 6, 16, 1, 'Blogs', ''),
(1, 6, 17, 2, 'Categories', ''),
(1, 6, 18, 2, 'News', ''),
(1, 6, 19, 3, 'Tags', '');

INSERT INTO `MenuItem` (`menu`, `parent`, `route`, `order`, `title`, `fa`) VALUES
(1, 0, 7, 1, 'Dashboard', 'fa-solid fa-house'),
(1, 0,  8, 2, 'My Trips', 'fa-solid fa-map-location-dot'),
(1, 0,  9, 3, 'My Profile', 'fa-solid fa-user'),
(1, 0, 13, 4, 'Programs', 'fa-solid fa-star'),kte 
(1, 0, 12, 5, 'Identification', 'fa-solid fa-passport'),
(1, 0, 11, 6, 'Credit Cards', 'fa-solid fa-credit-card');

INSERT INTO `MenuItem` (`menu`, `parent`, `route`, `order`, `title`) VALUES
(1,  0,  1,  1, 'Home'),
(1,  0,  2,  2, 'Travel Types'),
(1,  0,  3,  3, 'Destinations'),
(1,  0,  4,  4, 'Suppliers'),
(1,  0,  5,  5, 'Travel Explained'),
(1,  0,  6,  6, 'Today in Travel'),
(1,  0,  7,  7, 'Blogs'),
(1,  0,  8,  8, 'Podcasts'),

(3,  0,  9,  9, 'Accessibility Policy'),
(3,  0, 10, 10, 'Cookie Policy'),
(3,  0, 11, 11, 'Privacy Policy'),
(3,  0, 12, 12, 'Terms &amp; Conditions');

INSERT INTO `menu_item` (`menu`, `order`, `url`, `image`, `image_alt`) VALUES
(2, 1, 'https://youtube.com/@AdeptTraveler', '/img/icon/fa/brands/youtube.svg', 'YouTube'),
(2, 2, 'https://www.facebook.com/adepttraveler', '/img/icon/fa/brands/facebook.svg', 'Facebook'),
(2, 3, 'https://twitter.com/AdeptTraveler', '/img/icon/fa/brands/twitter.svg', 'Twitter'),
(2, 4, 'https://www.linkedin.com/company/adepttraveler', '/img/icon/fa/brands/linkedin.svg', 'LinkedIn'),
(2, 5, 'https://www.instagram.com/adepttraveler/', '/img/icon/fa/brands/instagram.svg', 'Instagram'),
(2, 6, 'https://www.tiktok.com/@theadepttraveler?lang=en', '/img/icon/fa/brands/tiktok.svg', 'TikTok');
