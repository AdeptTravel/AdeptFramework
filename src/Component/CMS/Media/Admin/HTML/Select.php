<?php

namespace Adept\Component\CMS\Media\Admin\HTML;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;
use Adept\Document\HTML\Head;

class Select extends \Adept\Abstract\Component\HTML
{
  /**
   * Undocumented function
   */
  public function __construct()
  {
    parent::__construct();

    Application::getInstance()->html->head->meta->title = 'Images';
  }

  public function getTable(): \Adept\Data\Table\Media
  {
    $get = Application::getInstance()->session->request->data->get;

    $table = new \Adept\Data\Table\Media;
    // TODO: Update for use with Videos and Audio as well
    $table->type   = 'Image';
    $table->file   = $get->getString('search', '');
    $table->path   = $get->getString('path', '/');
    $table->status = $get->getString('status');
    $table->mime   = $get->getString('mime');
    $table->load();

    return $table;
  }
}
