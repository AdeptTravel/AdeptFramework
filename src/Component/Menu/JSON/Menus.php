<?php

namespace Adept\Component\Menu\JSON;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;

class Menus extends \Adept\Abstract\Component\JSON\Items
{
  public function getTable(): \Adept\Data\Table\Menu
  {
    return new \Adept\Data\Table\Menu();
  }

  public function getItem(int $id): \Adept\Data\Item\Menu
  {
    $item = new \Adept\Data\Item\Menu();
    $item->loadFromID($id);
    return $item;
  }
}
