<?php

namespace Adept\Document\HTML;

defined('_ADEPT_INIT') or die();

use \Adept\Data\Table\Menu as Menus;
use \Adept\Data\Table\Menu\Item as Items;

class Menu
{
  /**
   * @var \Adept\Data\Table\Menu\Item;
   */
  protected array $menu;

  public function __construct()
  {
    $menu = new Menus();
    $menus = $menu->getData();

    for ($i = 0; $i < count($menus); $i++) {
      $items = new Items();
      $items->menuTitle = $menus[$i]->title;
      $items->status = 'Active';
      $this->menu[$menus[$i]->title] = $items->getData();
    }
  }

  public function getMenu(string $title)
  {
    return (array_key_exists($title, $this->menu))
      ? $this->menu[$title]
      : [];
  }

  // TODO: Add cache
}
