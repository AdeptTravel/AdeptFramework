<?php

namespace Adept\Component\System\Location\Admin\HTML;

defined('_ADEPT_INIT') or die('No Access');


use Adept\Application;

class Country extends \Adept\Abstract\Component\HTML\Item
{
  /**
   * Undocumented function
   *
   * @param  \Adept\Application        $app
   * @param  \Adept\Document\HTML\Head $head
   */
  public function __construct()
  {
    parent::__construct();

    $app = Application::getInstance();

    if ($app->session->request->data->get->getInt('id', 0) > 0) {
      $app->html->head->meta->title = 'Edit Area';
    } else {
      $app->html->head->meta->title = 'New Area';
    }

    $this->conf->controls->save       = true;
    $this->conf->controls->saveclose  = true;
    $this->conf->controls->close      = true;
  }

  public function getItem(int $id = 0): \Adept\Data\Item\Location\Area
  {
    $item = new \Adept\Data\Item\Location\Area();

    if ($id > 0) {
      $item->loadFromID($id);
    }

    return $item;
  }
}
