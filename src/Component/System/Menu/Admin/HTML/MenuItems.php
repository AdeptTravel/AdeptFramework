<?php

namespace Adept\Component\System\Menu\Admin\HTML;

use Adept\Application;

defined('_ADEPT_INIT') or die('No Access');

class MenuItems extends \Adept\Abstract\Component\HTML\Items
{
  /**
   * Undocumented function
   */
  public function __construct()
  {
    parent::__construct();

    Application::getInstance()->html->head->meta->title = 'Menu Items';

    // Component controls
    $this->conf->controls->delete     = true;
    $this->conf->controls->duplicate  = false;
    $this->conf->controls->edit       = false;
    $this->conf->controls->new        = true;
    $this->conf->controls->publish    = true;
    $this->conf->controls->unpublish  = true;
  }

  public function getTable(): \Adept\Data\Table\Menu\Item
  {
    $get  = Application::getInstance()->session->request->data->get;
    $table = new \Adept\Data\Table\Menu\Item();

    if (!$get->isEmpty('menu')) {
      $table->menuId = $get->getInt('menu');
    }

    if (!$get->isEmpty('parent')) {
      $table->parentId = $get->getInt('parent');
    }

    if (!$get->isEmpty('level')) {
      $table->recursiveLevel = $get->getInt('level');
    }

    if (!$get->isEmpty('routeId')) {
      $table->routeId = $get->getInt('routeId');
    }

    $table->title = $get->getString('title');

    if (!$get->isEmpty('status')) {
      $table->status = $get->getInt('status', 1);
    }

    $table->sort = $get->getString('sort', 'sortOrder');
    $table->dir  = $get->getString('dir', 'asc');

    return $table;
  }
}
