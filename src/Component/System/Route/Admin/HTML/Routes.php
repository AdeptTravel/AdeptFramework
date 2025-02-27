<?php

namespace Adept\Component\System\Route\Admin\HTML;

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
    //$get  = Application::getInstance()->session->request->data->get;
    $params = Application::getInstance()->params;
    $data   = new \Adept\Data\Table\Route();

    //$table->status = $get->getInt('status', 99);
    //if ($get->exists('status')) {
    //$data->status = $get->getString('status', 'Allow');
    //}

    $data->host         = $params->getString('host', Application::getInstance()->conf->site->host[0]);

    if (!empty($val = $params->getString('area'))) {
      $data->area = $val;
    }

    $data->type         = $params->getString('type');
    $data->component    = $params->getString('component');
    $data->view         = $params->getString('option');
    $data->template     = $params->getString('template');
    $data->route        = strtolower($params->getString('route'));
    $data->sitemap      = $params->getBool('sitemap');
    $data->allowGet   = $params->getBool('sitemap');
    $data->allowPost  = $params->getBool('sitemap');
    $data->allowEmail = $params->getBool('sitemap');
    $data->isSecure   = $params->getBool('sitemap');
    $data->status     = $params->getString('status', 'Allow');

    // Already in Adept\Abstract\Data\Table
    //$data->sort = $get->getString('sort', 'route');
    //$data->dir  = $get->getString('dir', 'asc');

    return $data;
  }
}
