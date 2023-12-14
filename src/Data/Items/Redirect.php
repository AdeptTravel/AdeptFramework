<?php

namespace Adept\Data\Items;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Application\Database;

class Redirect extends \Adept\Abstract\Data\Items
{
  public string $table = 'route';

  /**
   * Undocumented function
   *
   * @param  \Adept\Application\Database $db
   * @param  object|null|null            $obj
   */
  public function __construct(Database &$db, array $filter = [])
  {
    $this->db = $db;
    $this->filter = $filter;
    $this->addFilterNotEqual('redirect', '');
  }
}
