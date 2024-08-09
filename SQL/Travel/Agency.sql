DROP TABLE IF EXISTS `TravelAgency`;
CREATE TABLE IF NOT EXISTS `TravelAgency` (
  `id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `owner` INT UNSIGNED DEFAULT 0,
  `name`  VARCHAR(128),
  `ein`   VARCHAR(9) DEFAULT '',
  `arc`   VARCHAR(8) DEFAULT '',
  `iata`  VARCHAR(8) DEFAULT '',
  `clia`  VARCHAR(8) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `TravelAdvisor`;
CREATE TABLE IF NOT EXISTS `TravelAdvisor` (
  `id`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user`    INT UNSIGNED DEFAULT 0,
  `agency`  INT UNSIGNED DEFAULT 0,
  `ssa`     VARCHAR(12) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

INSERT INTO `TravelAgency` (`owner`, `name`, `ein`, `clia`) VALUES (1, 'The Adept Traveler, Inc.', '', '00037586');
INSERT INTO `TravelAdvisor` (`user`, `agency`) VALUES (1, 1);
