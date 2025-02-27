<?php

namespace Adept\Component\CMS\Media\Admin\HTML;

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

  public function getItem(int $id = 0): \Adept\Data\Item\Media
  {
    $item = new \Adept\Data\Item\Media\Image();
    $item->loadFromID($id);
    return $item;
  }

  public function getTable(): \Adept\Data\Table\Media
  {
    $get = Application::getInstance()->session->request->data->get;

    $table = new \Adept\Data\Table\Media;
    $table->type   = 'Image';
    $table->file   = $get->getString('search', '');
    $table->path   = $get->getString('path', '/');
    $table->status = $get->getString('status');
    $table->mime   = $get->getString('mime');
    $table->load();

    return $table;
  }
}
