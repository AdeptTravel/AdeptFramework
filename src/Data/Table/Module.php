<?php

namespace Adept\Data\Table;

defined('_ADEPT_INIT') or die();

class Module extends \Adept\Abstract\Data\Table
{
  protected string $table = 'Module';
  protected array $like = ['title', 'module'];

  public string $sort = 'sortOrder';

  public int    $id;
  public string $template;
  public string $area;
  public string $module;
  public string $title;
  public string $css;
  public object $params;
  public object $conditions;
  public string $status;         // ENUM('Active', 'Inactive', 'Trash') NOT NULL DEFAULT 'Active',
  public string $activeOn;
  public string $createdAt;
  public string $updatedAt;
  public int    $sortOrder;

  public function getItem(int $id): \Adept\Data\Item\Menu
  {
    $item = new \Adept\Data\Item\Menu($id);
    $item->loadFromId($id);
    return $item;
  }
}
