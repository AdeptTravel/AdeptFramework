<?php

namespace Adept\Component\CMS\Content\Admin\HTML;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;

class Tag  extends \Adept\Abstract\Component\HTML\Item
{
  /**
   * Undocumented function
   */
  public function __construct()
  {
    parent::__construct();

    $app  = Application::getInstance();
    $head = $app->html->head;

    if ($app->session->request->data->get->getInt('id', 0) > 0) {
      $head->meta->title = 'Edit Tag';
    } else {
      $head->meta->title = 'New Tag';
    }

    $this->conf->controls->save       = true;
    $this->conf->controls->saveclose  = true;
    $this->conf->controls->savecopy   = true;
    $this->conf->controls->savenew    = true;
    $this->conf->controls->close      = true;
  }

  public function getItem(int $id = 0): \Adept\Data\Item\Content
  {
    if ($id == 0) {
      $id   = Application::getInstance()->session->request->data->get->getInt('id', 0);
    }

    $item = new \Adept\Data\Item\Content();
    $item->type = 'Tag';

    if ($id > 0) {
      $item->loadFromID($id);
    }

    return $item;
  }
}
