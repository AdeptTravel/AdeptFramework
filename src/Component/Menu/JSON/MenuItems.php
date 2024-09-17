<?php

namespace Adept\Component\Menu\JSON;

defined('_ADEPT_INIT') or die('No Access');

class MenuItems extends \Adept\Abstract\Component\JSON\Items
{
  public function getTable(): \Adept\Data\Table\Menu\Item
  {
    return new \Adept\Data\Table\Menu\Item();
  }

  public function getItem(int $id): \Adept\Data\Item\Menu\Item
  {
    $item = new \Adept\Data\Item\Menu\Item();
    $item->loadFromID($id);
    return $item;
  }
}
