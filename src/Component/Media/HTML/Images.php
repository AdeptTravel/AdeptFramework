<?php

namespace Adept\Component\Media\HTML;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;
use Adept\Document\HTML\Head;

class Images extends \Adept\Abstract\Component\HTML\Items
{
  /**
   * Undocumented function
   */
  public function __construct()
  {
    parent::__construct();

    Application::getInstance()->html->head->meta->title = 'Images';

    // Component controls
    $this->conf->controls->delete     = false;
    $this->conf->controls->duplicate  = false;
    $this->conf->controls->edit       = false;
    $this->conf->controls->new        = false;
    $this->conf->controls->publish    = false;
    $this->conf->controls->unpublish  = false;
    $this->conf->controls->upload = true;
    $this->conf->controls->newdir = true;
  }

  public function getItem(int $id = 0): \Adept\Data\Item\Media\Image
  {
    return new \Adept\Data\Item\Media\Image($id);
  }


  public function getItems(): \Adept\Data\Table\Media\Image
  {
    $get = Application::getInstance()->session->request->data->get;

    $items = new \Adept\Data\Table\Media\Image(false);

    $items->path = ($get->exists('path')) ? $get->getString('path') : '/';
    $items->load();

    return $items;
  }
}
