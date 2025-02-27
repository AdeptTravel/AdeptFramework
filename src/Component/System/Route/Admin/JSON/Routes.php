<?php

namespace Adept\Component\Route\JSON;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;

class Routes extends \Adept\Abstract\Component\JSON\Items
{

  public function getTable(): \Adept\Data\Table\Route
  {
    return new \Adept\Data\Table\Route();
  }

  public function getItem(int $id): \Adept\Data\Item\Route
  {
    $item = new \Adept\Data\Item\Route();
    $item->loadFromID($id);
    return $item;
  }
}
