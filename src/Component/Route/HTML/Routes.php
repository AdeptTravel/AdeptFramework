<?php

namespace Adept\Component\Route\HTML;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;

class Routes extends \Adept\Abstract\Component\HTML\Items
{
  /**
   * Undocumented function
   */
  public function __construct()
  {
    parent::__construct();

    Application::getInstance()->html->head->meta->title = 'Routes';

    // Component controls
    $this->conf->controls->delete     = false;
    $this->conf->controls->duplicate  = false;
    $this->conf->controls->edit       = false;
    $this->conf->controls->new        = true;
    $this->conf->controls->publish    = false;
    $this->conf->controls->unpublish  = false;
  }

  public function getTable(): \Adept\Data\Table\Route
  {
    $get  = Application::getInstance()->session->request->data->get;
    $data = new \Adept\Data\Table\Route();

    //$table->status = $get->getInt('status', 99);
    if ($get->exists('status')) {
      $data->status = $get->getInt('status', 1);
    }

    $data->component = $get->getString('component');
    $data->option = $get->getString('option');
    $data->template = $get->getString('template');
    $data->route = strtolower($get->getString('route'));


    if ($get->exists('sitemap')) {
      $data->sitemap = $get->getInt('sitemap', 1);
    }

    if ($get->exists('get')) {
      $data->get = $get->getInt('get', 1);
    }

    if ($get->exists('post')) {
      $data->post = $get->getInt('post', 1);
    }

    if ($get->exists('email')) {
      $data->email = $get->getInt('email', 1);
    }

    if ($get->exists('secure')) {
      $data->secure = $get->getInt('secure', 1);
    }

    if ($get->exists('block')) {
      $data->block = $get->getInt('block', 1);
    }

    $data->sort = $get->getString('sort', 'route');
    $data->dir = $get->getString('dir', 'asc');


    return $data;
  }
}
