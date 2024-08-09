<?php

namespace Adept\Component\Route\JSON;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Abstract\Document;
use \Adept\Application;
use \Adept\Data\Item\Route;
use \Adept\Document\HTML\Body\Status;

class NotFound extends \Adept\Abstract\Component\Edit
{
  protected \Adept\Data\Item\Route $item;

  public function save()
  {
    $post     = &$this->app->session->request->data->post;
    $id       = $post->getInt('id', 0);
    $route    = $post->getString('route', '');
    $redirect = $post->getString('redirect', '');


    if ($id > 0) {
      $item = new \Adept\Data\Item\Route($this->app->db, $id);

      if ($this->item->id > 0) {
        if ($this->item->redirect != $redirect) {
          $this->item->redirect = $redirect;
          $this->item->save();
        } else if (empty($redirect)) {
          $this->item->delete();
        } else {
          $this->item->route = $route;
          $this->item->redirect = $redirect;
          $this->item->save();
        }
      }
    }
  }
}
