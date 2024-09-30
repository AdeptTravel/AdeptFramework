<?php

namespace Adept\Data\Table;

defined('_ADEPT_INIT') or die();

class Menu extends \Adept\Abstract\Data\Table
{
  protected string $table = 'Menu';
  protected array $like = ['title'];

  public string $sort = 'title';

  public string $title;
  public string $css;
  public bool   $isSecure;

  protected function getItem(int $id): \Adept\Data\Item\Menu
  {
    $item = new \Adept\Data\Item\Menu($id);
    $item->loadFromId($id);
    return $item;
  }
}
