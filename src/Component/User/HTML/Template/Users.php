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
      $data->status = $get->getString('status', 'Allow');
    }

    $data->component = $get->getString('component');
    $data->view    = $get->getString('option');
    $data->template  = $get->getString('template');
    $data->route     = strtolower($get->getString('route'));

    foreach (['sitemap', 'allowGet', 'allowPost', 'allowEmail', 'isSecure'] as $v) {
      if ($get->exists($v)) {
        $data->$v = $get->getInt($v, 0);
      }
    }

    $data->sort = $get->getString('sort', 'route');
    $data->dir = $get->getString('dir', 'asc');

    return $data;
  }
}
