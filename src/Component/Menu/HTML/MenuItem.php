<?php

namespace Adept\Component\Menu\HTML;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;

class MenuItem extends \Adept\Abstract\Component\HTML\Item
{
  /**
   * Undocumented function
   */
  public function __construct()
  {
    parent::__construct();

    $app = Application::getInstance();

    if ($app->session->request->data->get->getInt('id', 0) > 0) {
      $app->html->head->meta->title = 'Edit Menu Item';
    } else {
      $app->html->head->meta->title = 'New Menu Item';
    }

    $this->conf->controls->save       = true;
    $this->conf->controls->saveclose  = true;
    $this->conf->controls->savecopy   = true;
    $this->conf->controls->savenew    = true;
    $this->conf->controls->close      = true;
  }

  public function getItem(int $id = 0): \Adept\Data\Item\Menu\Item
  {
    $app = Application::getInstance();

    if ($id == 0) {
      $id = $app->session->request->data->get->getInt('id');
    }

    return new \Adept\Data\Item\Menu\Item($id, false);
  }
}
