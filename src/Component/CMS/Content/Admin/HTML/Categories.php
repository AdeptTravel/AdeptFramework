<?php

namespace Adept\Component\CMS\Content\Admin\HTML;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;

class Categories extends \Adept\Abstract\Component\HTML\Items
{
  /**
   * Undocumented function
   */
  public function __construct()
  {
    parent::__construct();

    Application::getInstance()->html->head->meta->title = 'Categories';

    // Component controls
    $this->conf->controls->delete     = true;
    $this->conf->controls->duplicate  = false;
    $this->conf->controls->edit       = true;
    $this->conf->controls->new        = true;
    $this->conf->controls->publish    = false;
    $this->conf->controls->unpublish  = false;
  }

  public function getTable(): \Adept\Data\Table\Content\Category
  {
    $get   = Application::getInstance()->session->request->data->get;
    $table = new \Adept\Data\Table\Content\Category();

    $table->type = 'Category';

    if (!$get->isEmpty('parentId')) {
      $table->parentId = $get->getInt('parentId');
    }

    if (!$get->isEmpty('level')) {
      $table->recursiveLevel = $get->getInt('level');
    }

    if (!$get->isEmpty('routeId')) {
      $table->routeId = $get->getInt('routeId');
    }

    $table->title = $get->getString('title');

    if ($get->exists('status')) {
      $table->status = $get->getString('status', 'Active');
    }

    $table->sort = $get->getString('sort', 'sortOrder');
    $table->dir  = $get->getString('dir', 'asc');

    return $table;
  }
}
