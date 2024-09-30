DROP TABLE IF EXISTS `Request`;
CREATE TABLE Request (
  `id`              INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `sessionId`       INT UNSIGNED NOT NULL,
  `ipaddressId`     INT UNSIGNED NOT NULL,
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
  FOREIGN KEY (`userAgentId`) REFERENCES `Useragent`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`routeId`) REFERENCES `Route`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`urlId`) REFERENCES `Url`(`id`) ON DELETE CASCADE
);