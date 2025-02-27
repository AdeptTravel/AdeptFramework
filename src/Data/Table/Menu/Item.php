<?php

namespace Adept\Data\Table\Menu;

defined('_ADEPT_INIT') or die();

use Adept\Data\Item\Menu;

class Item extends \Adept\Abstract\Data\Table
{
  protected string $table = 'MenuItem';
  protected array $ignore = ['recursiveLevel', 'menuTitle'];
  protected array $like = ['title'];
  protected array $joinInner = [
    'Menu'  => 'menuId'
  ];

  protected array $joinLeft = [
    'Route' => 'routeId',
    'Url'   => 'urlId',
    'Media' => 'imageId'
  ];

  protected array $joinColumnMap = [
    'menuTitle' => 'Menu.Title'
  ];

  protected array $recursiveSort = [
    'title',
    'sortOrder'
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
  public int    $sortOrder;

  public function __construct()
  {
    parent::__construct();
  }


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

  public function getData(bool $recursive = true): array
  {
    $items = parent::getData($recursive);

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

  public function getItem(int $id): \Adept\Data\Item\Route
  {
    $item = new \Adept\Data\Item\Route();
    $item->loadFromId($id);
    return $item;
  }
}
