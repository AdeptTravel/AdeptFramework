<?php

namespace Adept\Component\Menu\HTML;

defined('_ADEPT_INIT') or die('No Access');


use Adept\Application;

class Menu extends \Adept\Abstract\Component\HTML\Item
{
  /**
   * Init
   */
  public function __construct()
  {
    parent::__construct();

    $app = Application::getInstance();

    if ($app->session->request->data->get->getInt('id', 0) > 0) {
      $app->html->head->meta->title = 'Edit Menu';
    } else {
      $app->html->head->meta->title = 'New Menu';
    }

    $this->conf->controls->save       = true;
    $this->conf->controls->saveclose  = true;
    $this->conf->controls->savecopy   = true;
    $this->conf->controls->savenew    = true;
    $this->conf->controls->close      = true;
  }

  public function getItem(int $id = 0): \Adept\Data\Item\Menu
  {
    $item = new \Adept\Data\Item\Menu();

    if ($id > 0) {
      $item->loadFromID($id);
    }

    return $item;
  }
}
