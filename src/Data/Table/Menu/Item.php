<?php

namespace Adept\Data\Table\Menu;

defined('_ADEPT_INIT') or die();

use Adept\Data\Item\Menu;

class Item extends \Adept\Abstract\Data\Table
{
  protected string $table = 'MenuItem';
  protected array $ignore = ['menuTitle'];
  protected array $like = ['title'];
  protected array $joinInner = [
    'Menu' => 'menuId'
  ];

  protected array $joinLeft = [
    'Route'      => 'routeId',
    'Url'        => 'urlId',
    'MediaImage' => 'imageId'
  ];

  public string $sort = '';

  public string $menuTitle;

  public int    $menuId;
  public int    $parentId;
  public int    $routeId;
  public int    $urlId;
  public int    $imageId;
  public string $itemType;
  public string $title;
  public string $css;
  public string $params;
  public string $activeOn;
  public int    $displayOrder;

  protected function getFilterData(): array
  {
    if (!empty($this->menuTitle)) {
      $menu = new Menu();
      $menu->loadFromIndex($this->menuTitle);

      if (!empty($menu->id)) {
        $this->menuId = $menu->id;
      }
    }

    return parent::getFilterData();
  }

  public function getData(bool $recursive = false): array
  {
    //$items = parent::getData($recursive);
    $items = parent::getData(true);

    for ($i = 0; $i < count($items); $i++) {
      if ($items[$i]->type == 'Route') {
        $items[$i]->link = $items[$i]->routeRoute;
      } else if ($items[$i]->type == 'Url') {
        $items[$i]->link = $items[$i]->urlUrl;
      } else {
        $items[$i]->link = '';
      }
    }

    return $items;
  }

  protected function getItem(int $id): \Adept\Data\Item\Route
  {
    $item = new \Adept\Data\Item\Route();
    $item->loadFromId($id);
    return $item;
  }
}
