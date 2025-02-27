<?php

namespace Adept\Component\System\Menu\Admin\HTML;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;

class Menus extends \Adept\Abstract\Component\HTML\Items
{
  /**
   * Undocumented function
   */
  public function __construct()
  {
    parent::__construct();

    Application::getInstance()->html->head->meta->title = 'Menus';

    // Component controls
    $this->conf->controls->delete     = false;
    $this->conf->controls->duplicate  = false;
    $this->conf->controls->edit       = false;
    $this->conf->controls->new        = true;
    $this->conf->controls->publish    = false;
    $this->conf->controls->unpublish  = false;
  }

  public function getTable(): \Adept\Data\Table\Menu
  {
    $get  = Application::getInstance()->session->request->data->get;
    $data = new \Adept\Data\Table\Menu();

    $data->title = $get->getString('title', '');


    $data->host = $get->getString('host', Application::getInstance()->conf->site->host[0]);

    if (!$get->isEmpty('status')) {
      $data->status = $get->getString('status');
    }

    if (!$get->isEmpty('secure')) {
      $data->isSecure = $get->getBool('secure', false);
    }

    $data->sort = $get->getString('sort', '');
    $data->dir = $get->getString('dir', 'ASC');

    return $data;
  }
}
