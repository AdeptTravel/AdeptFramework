<?php

namespace Adept\Component\System\User\Admin\HTML;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;

class Users extends \Adept\Abstract\Component\HTML\Items
{
  /**
   * Undocumented function
   */
  public function __construct()
  {
    parent::__construct();

    Application::getInstance()->html->head->meta->title = 'Routes';

    // Component controls
    $this->conf->controls->delete     = true;
    $this->conf->controls->duplicate  = false;
    $this->conf->controls->edit       = true;
    $this->conf->controls->new        = true;
    $this->conf->controls->publish    = false;
    $this->conf->controls->unpublish  = false;
  }

  public function getTable(): \Adept\Data\Table\User
  {
    $get  = Application::getInstance()->session->request->data->get;
    $data = new \Adept\Data\Table\User();

    //$table->status = $get->getInt('status', 99);
    if ($get->exists('status')) {
      $data->status = $get->getString('status', 'Active');
    }
    /*
DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
  `id`              INT UNSIGNED AUTO_INCREMENT,
  `username`        VARCHAR(128) NOT NULL,
  `password`        VARCHAR(255) NOT NULL,
  `firstName`       VARCHAR(64) NOT NULL,
  `middleName`      VARCHAR(64),
  `lastName`        VARCHAR(64) NOT NULL,
  `dob`             DATE DEFAULT NULL,
  `status`          ENUM('Active', 'Block', 'Inactive', 'Locked') NOT NULL DEFAULT 'Inactive',
  `createdAt`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `verifiedOn`      TIMESTAMP DEFAULT NULL,
  `validatedOn`     TIMESTAMP DEFAULT NULL,
  INDEX idx_status (`status`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

    */
    //$data->verified = $get->getBool('verified');
    //$data->validated = $get->getBool('validated');

    return $data;
  }
}
