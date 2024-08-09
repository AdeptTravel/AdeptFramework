DROP TABLE IF EXISTS `TravelTraveler`;
CREATE TABLE IF NOT EXISTS `TravelTraveler` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user` INT UNSIGNED DEFAULT 0,
  `name` VARCHAR(128),
  `dob` DATE,
  `tsapre` VARCHAR(16) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

INSERT INTO `traveler`  (`name`, `dob`, `tsapre`) VALUES ('Brandon Joseph Yaniz','1979-08-08', 'TT116YZY8');
INSERT INTO `traveler`  (`name`, `dob`, `tsapre`) VALUES ('Amanda Mary Yaniz','1983-11-19', 'TT116YZNJ');

DROP TABLE IF EXISTS `TravelTravelerId`;
CREATE TABLE IF NOT EXISTS `TravelTravelerId` (
  `traveler` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(32)NOT NULL,
  `first` VARCHAR(32) NOT NULL,
  `middle` VARCHAR(32) DEFAULT '',
  `last` VARCHAR(32) NOT NULL,
  `area` VARCHAR(16) NOT NULL,
  `number` VARCHAR(16) NOT NULL,
  `expires` DATE NOT NULL,
  PRIMARY KEY (`traveler`, `type`, `expires`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

INSERT INTO `traveler_id` (`traveler`, `type`, `first`, `middle`, `last`, `area`, `number`, `expires`)
VALUES
(1, 'Passport', 'Brandon', 'Joseph', 'Yaniz', 'USA', 'C09343239', '2024-08-08'),
(2, 'Passport', 'Amanda', 'Mary', 'Yaniz', 'USA', 'C09343239', '2024-08-08');

DROP TABLE IF EXISTS `TravelTravelerLoyalty`;
CREATE TABLE IF NOT EXISTS `TravelTravelerLoyalty` (
  `traveler` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `airline` INT UNSIGNED DEFAULT 0,
  `hotel` INT UNSIGNED DEFAULT 0,
  `number` VARCHAR(64) DEFAULT '',
  PRIMARY KEY (`traveler`, `airline`, `hotel`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

INSERT INTO `traveler_loyalty` (`traveler`,`airline`, `number`) VALUES
(1, 1,'29L0FF0'), (2, 1, '34F7MF2');

DROP TABLE IF EXISTS `TravelTrip`;
CREATE TABLE IF NOT EXISTS `TravelTrip` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `route` INT UNSIGNED NOT NULL,
  `ref` VARCHAR(6),
  `title` VARCHAR(64) NOT NULL,
  `depart` DATETIME,
  `return` DATETIME,
  `status` TINYINT(3) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

INSERT INTO `trip` (`route`, `ref`, `title`, `depart`, `return`, `status`)
VALUES (43, 'P86ZST', 'New Orleans', '2024-06-01 07:00:00', '2024-06-05 17:00:00', 1);

INSERT INTO `route` (`type`, `area`, `route`, `params`, `sitemap`) VALUES
('Component', 'Site',  'trip/p86zst.html',        '{"component":"Trip",  "option": "Itinerary", "template":""}', 0);


DROP TABLE IF EXISTS `TravelTripTraveler`;
CREATE TABLE IF NOT EXISTS `TravelTripTraveler` (
  `trip` INT UNSIGNED NOT NULL,
  `traveler` VARCHAR(64) NOT NULL,
  `primary` TINYINT(3) DEFAULT 0,
  PRIMARY KEY (`trip`, `traveler`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

INSERT INTO `trip_traveler` (`trip`, `traveler`, `primary`) VALUES
(1,1,1), (1,2,0);

DROP TABLE IF EXISTS `TravelTripFlight`;
CREATE TABLE IF NOT EXISTS `TravelTripFlight` (
  `trip` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `airline` INT UNSIGNED DEFAULT 0,
  `number` INT NOT NULL,
  `reservation` VARCHAR(32) NOT NULL,
  `depart_airport` INT UNSIGNED DEFAULT 0,
  `depart_time` DATETIME NOT NULL,
  `arrive_airport` INT UNSIGNED DEFAULT 0,
  `arrive_time` DATETIME NOT NULL,
  `duration` VARCHAR(8),
  PRIMARY KEY (`trip`, `airline`, `depart_time`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

INSERT INTO trip_flight (`trip`, `airline`, `number`, `reservation`, `depart_airport`, `depart_time`, `arrive_airport`, `arrive_time`, `duration`) VALUES
(1,1, 2613, '3HWR5Q', 1, '2024-06-01 10:05', 2, '2024-06-01 12:25', '02h 20m'),
(1,1, 2613, '3HWR5Q', 2, '2024-06-05 13:22', 1, '2024-06-05 16:00', '02h 38m');

DROP TABLE IF EXISTS `TravelTripHotel`;
CREATE TABLE IF NOT EXISTS `TravelTripHotel` (
  `trip` INT UNSIGNED NOT NULL,
  `hotel` INT UNSIGNED NOT NULL,
  `reservation` VARCHAR(32) NOT NULL,
  `checkin` DATETIME NOT NULL,
  `checkout` DATETIME NOT NULL,
  `details` TEXT DEFAULT '',
  `price` DECIMAL(6, 2) DEFAULT 0.00,
  PRIMARY KEY (`trip`, `hotel`, `checkin`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

INSERT INTO trip_hotel (trip, hotel, reservation, checkin, checkout) VALUES
(1, 1, '80215ED194760', '2024-06-01 15:00:00', '2024-06-05 12:00:00');

DROP TABLE IF EXISTS `TravelHotel`;
CREATE TABLE IF NOT EXISTS `hotel` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `chain` INT UNSIGNED DEFAULT 0,
  `hotel` VARCHAR(64) NOT NULL,
  `ident` VARCHAR(64),
  `street0` VARCHAR(64),
  `street1` VARCHAR(64),
  `street2` VARCHAR(64),
  `city` VARCHAR(64) NOT NULL,
  `state` VARCHAR(64),
  `postalcode` VARCHAR(64),
  `country` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

INSERT INTO `hotel` (`chain`, `hotel`, `ident`, `street0`, `city`, `state`, `postalcode`, `country`) VALUES
(1, 'Wyndham Garden',  'Baronne Plaza', '201 Baronne St', 'New Orleans', 'Louisiana', '70112', 'United States');

DROP TABLE IF EXISTS `TravelTripHotelChain`;
CREATE TABLE IF NOT EXISTS `TravelTripHotelChain` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

INSERT INTO `hotel_chain` (`title`) VALUES ('Wyndham');

DROP TABLE IF EXISTS `airline`;
CREATE TABLE IF NOT EXISTS `airline` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `airline` VARCHAR(64) NOT NULL,
  `code` VARCHAR(64) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

INSERT INTO `airline` (`airline`, `code`) VALUES
('American Airlines', 'AA');

DROP TABLE IF EXISTS `TravelAirport`;
CREATE TABLE `airport` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `iata` VARCHAR(8) NOT NULL UNIQUE,
  `airport` VARCHAR(64) NOT NULL,
  `country` VARCHAR(255),
  `state` VARCHAR(255),
  `city` VARCHAR(255),
  `type` VARCHAR(32),
  `elevation` INT,
  `latitude` DECIMAL(10,8),
  `longitude` DECIMAL(11,8),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

INSERT INTO airport (`iata`, `airport`, `country`, `state`, `city`) VALUES
('ORD', 'O\'Hare International Airport', 'United States', 'Illinois', 'Chicago'),
('MSY', 'Louis Armstrong New Orleans International Airport', 'United States', 'Louisiana', 'New Orleans');
