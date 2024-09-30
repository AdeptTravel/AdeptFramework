DROP TABLE IF EXISTS `Session`;
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