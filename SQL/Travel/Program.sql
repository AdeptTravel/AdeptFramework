DROP TABLE IF EXISTS `LoyaltyProgram`;
CREATE TABLE `LoyaltyProgram` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` ENUM('Airline', 'Car Rental', 'Cruise', 'Hotel', 'Membership') NOT NULL,
  `title` VARCHAR(32) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;