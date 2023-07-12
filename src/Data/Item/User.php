<?php

namespace AdeptCMS\Model\Item;

defined('_ADEPT_INIT') or die('No Access');

class User extends \AdeptCMS\Base\Data\Item
{
  /**
   * 
   * 
   * @var string
   */
  public $password;

  /**
   * The ID of this data in the database
   *
   * @var int
   */
  public $id = 0;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $username = '';

  /**
   * Undocumented variable
   *
   * @var boolean
   */
  public $active = false;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $created = '0000-00-00 00:00:00';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $verified = '0000-00-00 00:00:00';

  public function __construct(\AdeptCMS\Application\Database $db, int $id = 0)
  {
    $this->table = 'user';
    parent::__construct($db, $id);
  }

  public function setPassword(string $password)
  {
    $this->password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
  }
}
