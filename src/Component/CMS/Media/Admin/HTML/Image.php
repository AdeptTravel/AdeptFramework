<?php

namespace Adept\Component\CMS\Media\Admin\HTML;

defined('_ADEPT_INIT') or die('No Access');


use Adept\Application;

class Image extends \Adept\Abstract\Component\HTML\Item
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

    $app =  Application::getInstance();

    if ($app->session->request->data->get->getInt('id', 0) > 0) {
      $app->html->head->meta->title = 'Edit Image Details';
    } else {
      $app->html->head->meta->title = 'New Image Details';
    }

    $this->conf->controls->save       = true;
    $this->conf->controls->saveclose  = true;
    $this->conf->controls->close      = true;
  }

  public function getItem(int $id = 0): \Adept\Data\Item\Media\Image
  {
    $item = new \Adept\Data\Item\Media\Image();

    if ($id > 0) {
      $item->loadFromID($id);
    }

    return $item;
  }
}
