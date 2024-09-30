DROP TABLE IF EXISTS `Useragent`;
CREATE TABLE `Useragent` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `useragent`  VARCHAR(512) NOT NULL UNIQUE,
  `friendly`   VARCHAR(128) NOT NULL UNIQUE,
  `browser`    VARCHAR(64),
  `os`         VARCHAR(64),
  `device`     VARCHAR(64),
  `type     `  VARCHAR(32),
  `isDetected` TINYINT(1) NOT NULL DEFAULT 0,
  `status`     ENUM('Active', 'Block', 'Inactive') NOT NULL DEFAULT 'Active',
  `createdOn`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedOn`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;