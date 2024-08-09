<?php

namespace Adept\Component\Route\JSON;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Abstract\Document;
use \Adept\Application;

class Route extends \Adept\Abstract\Component\JSON
{
  public function getItem(int $id = 0)
  {
    return new \Adept\Data\Item\Route($this->app->db, $id);
  }
}
