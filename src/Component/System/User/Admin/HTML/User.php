<?php

namespace Adept\Component\System\User\Admin\HTML;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;

class User extends \Adept\Abstract\Component\HTML\Item
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
      $app->html->head->meta->title = 'Edit User';
    } else {
      $app->html->head->meta->title = 'New User';
    }

    $this->conf->controls->save       = true;
    $this->conf->controls->saveclose  = true;
    $this->conf->controls->savecopy   = true;
    $this->conf->controls->savenew    = true;
    $this->conf->controls->close      = true;
  }

  public function getItem(int $id = 0): \Adept\Data\Item\User
  {
    $item = new \Adept\Data\Item\User();

    if ($id > 0) {
      $item->loadFromID($id);
    }

    return $item;
  }
}
