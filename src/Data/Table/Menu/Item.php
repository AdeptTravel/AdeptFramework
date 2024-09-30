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
    'Route' => 'routeId'
  ];

  public string $sort = 'order';

  public string $menuTitle;

  public int    $menuId;
  public int    $parentId;
  public int    $routeId;
  public string $url;
  public string $title;
  public string $image;
  public string $fa;
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

  protected function getItem(int $id): \Adept\Data\Item\Route
  {
    $item = new \Adept\Data\Item\Route();
    $item->loadFromId($id);
    return $item;
  }
}
