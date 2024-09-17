<?php

namespace Adept\Component\Menu\HTML;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;

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
    $this->conf->controls->delete     = false;
    $this->conf->controls->duplicate  = false;
    $this->conf->controls->edit       = false;
    $this->conf->controls->new        = true;
    $this->conf->controls->publish    = false;
    $this->conf->controls->unpublish  = false;
  }

  public function getTable(): \Adept\Data\Table\Menu\Item
  {
    $get  = Application::getInstance()->session->request->data->get;
    $data = new \Adept\Data\Table\Menu\Item();

    if ($get->exists('menu')) {
      $data->menu = $get->getInt('menu');
    }

    if ($get->exists('parent')) {
      $data->parent = $get->getInt('parent');
    }

    if ($get->exists('route')) {
      $data->route = $get->getInt('route');
    }

    $data->title = $get->getString('title');

    if ($get->exists('status')) {
      $data->status = $get->getInt('status', 1);
    }

    $data->sort = $get->getString('sort', 'route');
    $data->dir  = $get->getString('dir', 'asc');

    return $data;
  }

  public function getDataset(): \Adept\Data\Dataset\Menu\Item
  {
    $get  = Application::getInstance()->session->request->data->get;
    $data = new \Adept\Data\Dataset\Menu\Item();

    //$table->status = $get->getInt('status', 99);

    if ($get->exists('menu')) {
      $data->menu = $get->getInt('menu');
    }

    if ($get->exists('parent')) {
      $data->parent = $get->getInt('parent');
    }

    if ($get->exists('route')) {
      $data->route = $get->getInt('route');
    }

    $data->title = $get->getString('title');

    if ($get->exists('status')) {
      $data->status = $get->getInt('status', 1);
    }

    $data->sort = $get->getString('sort', 'route');
    $data->dir = $get->getString('dir', 'asc');

    return $data;
  }
}
