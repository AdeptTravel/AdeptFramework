DROP TABLE IF EXISTS `Location`;
CREATE TABLE `Location` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `area` INT UNSIGNED NOT NULL,
  `title` VARCHAR(128),
  `street0` VARCHAR(64),
  `street1` VARCHAR(64),
  `street2` VARCHAR(64),
  `elevation` INT,
  `latitude` DECIMAL(10,8),
  `longitude` DECIMAL(11,8),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `LocationArea`;
CREATE TABLE `LocationArea` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `city` VARCHAR(32),
  `county` VARCHAR(64),
  `state` VARCHAR(32),
  `postalcode` VARCHAR(128) NOT NULL,
  `country` INT UNSIGNED,
  `timezone` VARCHAR(64),
  `latitude` DECIMAL(10,8),
  `longitude` DECIMAL(11,8),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `LocationCountry`;
CREATE TABLE `LocationCountry` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content` INT UNSIGNED,
  `country` VARCHAR(48) UNIQUE,
  `iso2` VARCHAR(3) UNIQUE,
  `iso3` VARCHAR(3) UNIQUE,
  `currency` VARCHAR(64),
  `currency_code` VARCHAR(4),
  `phone_code` VARCHAR(8),
  `region` VARCHAR(32),
  `subregion` VARCHAR(32),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;


SELECT 
  *
FROM
  `location_area`
GROUP BY 
  `city`,
  `county`,
  `state`,
  `postalcode`
HAVING 
  (COUNT(`city`) > 1) AND 
  (COUNT(`county`) > 1) AND 
  (COUNT(`state`) > 1) AND 
  (COUNT(`postalcode`) > 1);

  SELECT 
  `city`, COUNT(`city`),
  `county`, COUNT(`county`),
  `postalcode`, COUNT(`postalcode`)
FROM
  `location_area`
GROUP BY 
  `city`,
  `county`,
  `postalcode`
HAVING 
  (COUNT(`city`) > 1) AND 
  (COUNT(`county`) > 1) AND 
  (COUNT(`postalcode`) > 1);