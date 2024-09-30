DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
  `id`           INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `username`     VARCHAR(128) NOT NULL,
  `password`     VARCHAR(255) NOT NULL,
  `firstName`    VARCHAR(64) NOT NULL,
  `middleName`   VARCHAR(64),
  `lastName`     VARCHAR(64) NOT NULL,
  `dob`          DATE DEFAULT NULL,
  `status`       ENUM('Active', 'Block', 'Inactive', 'Locked') NOT NULL DEFAULT 'Inactive',
  `createdOn`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedOn`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `verifiedAt`   TIMESTAMP DEFAULT NULL,
  `validatedAt`  TIMESTAMP DEFAULT NULL,
  INDEX idx_status (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

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

DROP TABLE IF EXISTS `LogAuth`;
CREATE TABLE `LogAuth` (
  `username`  VARCHAR(128) NOT NULL,
  `useragent` INT UNSIGNED NOT NULL,
  `ipaddress` INT UNSIGNED NOT NULL,
  `result`    ENUM('Success', 'Fail', 'Delay'),
  `reason`    ENUM('', 'Deactivated', 'Nonexistent', 'Password', 'Verified', 'Validated'),
  `createdOn`   DATETIME NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`username`, `created`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username`    VARCHAR(128) NOT NULL,
  `password`    VARCHAR(255) NOT NULL,
  `firstname`   VARCHAR(32) NOT NULL,
  `middlename`  VARCHAR(32),
  `lastname`    VARCHAR(32) NOT NULL,
  `dob`         DATETIME DEFAULT '0000-00-00 00:00:00',
  `created`     DATETIME NOT NULL DEFAULT NOW(),
  `verified`    DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `validated`   DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status`      TINYINT(3) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;



DROP TABLE IF EXISTS `UserToken`;
CREATE TABLE `UserToken` (
  `user` INT UNSIGNED NOT NULL,
  `type` ENUM('Verify', 'Validate', 'Forgot', 'Security'),
  `token` VARCHAR(64) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT NOW(),
  `expires` DATETIME NOT NULL DEFAULT DATE_ADD(NOW(), INTERVAL 1 DAY),
  PRIMARY KEY (`user`, `token`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `UserPasskey`;
CREATE TABLE `UserPasskey` (
    `user`       INT UNSIGNED NOT NULL,
    `credential` VARCHAR(255) NOT NULL,
    `publickey`  TEXT NOT NULL,
    `count`      INT UNSIGNED NOT NULL,
    `source`     ENUM('direct', 'indirect', 'none'),
    `created`    DATETIME NOT NULL DEFAULT NOW(),
    `lastused`   DATETIME NOT NULL DEFAULT NOW(),
    PRIMARY KEY (`user`, `credential`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `UserACL`;
CREATE TABLE `UserACL` (
  `user` INT UNSIGNED NOT NULL,
  `acl` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`user`, `acl`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `UserLocation`;
CREATE TABLE `UserLocation` (
  `user` INT UNSIGNED NOT NULL,
  `location` INT UNSIGNED NOT NULL,
  `name` VARCHAR(128) DEFAULT '',
  `primary` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`user`, `address`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `UserPhone`;
CREATE TABLE `UserPhone` (
  `user` INT UNSIGNED NOT NULL,
  `phone` INT UNSIGNED NOT NULL,
  `name` VARCHAR(32) DEFAULT '',
  `primary` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`user`, `phone`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `UserCreditCard`;
CREATE TABLE `UserCreditCard` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user` INT UNSIGNED NOT NULL,
  `address` INT UNSIGNED NOT NULL,
  `bin` MEDIUMINT UNSIGNED NOT NULL,
  `last4` SMALLINT UNSIGNED NOT NULL,
  `name` VARCHAR(32),
  `expire` DATETIME NOT NULL,
  `data` TEXT,
  `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `UserIdentification`;
CREATE TABLE `UserIdentification` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user` INT UNSIGNED NOT NULL,
  `address` INT UNSIGNED NOT NULL,
  `type` VARCHAR(16) NOT NULL,
  `location` VARCHAR(2),
  `firstname` VARCHAR(128) NOT NULL,
  `middlename` VARCHAR(128) NOT NULL,
  `lastname` VARCHAR(128) NOT NULL,
  `number` VARCHAR(32) UNIQUE,
  `gender` CHAR(1),
  `issued` DATETIME DEFAULT '0000-00-00 00:00:00',
  `expire` DATETIME DEFAULT '0000-00-00 00:00:00',
  `status` TINYINT(3) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `UserLoyalty`;
CREATE TABLE `UserLoyalty` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user` INT UNSIGNED NOT NULL,
  `program` INT UNSIGNED NOT NULL,
  `number` VARCHAR(64) UNIQUE,
  `expire` DATETIME DEFAULT '0000-00-00 00:00:00',
  `status` TINYINT(3) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;
