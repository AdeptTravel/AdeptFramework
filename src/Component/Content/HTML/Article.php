<?php

namespace Adept\Component\Content\HTML;

defined('_ADEPT_INIT') or die('No Access');


use Adept\Application;


class Article extends \Adept\Abstract\Component\HTML\Item
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
      $head->meta->title = 'Edit Article';
    } else {
      $head->meta->title = 'New Article';
    }

    $this->conf->controls->save       = true;
    $this->conf->controls->saveclose  = true;
    $this->conf->controls->savecopy   = true;
    $this->conf->controls->savenew    = true;
    $this->conf->controls->close      = true;
  }

  public function getItem(int $id = 0): \Adept\Data\Item\Content
  {
    return new \Adept\Data\Item\Content($id, false);
  }
}
