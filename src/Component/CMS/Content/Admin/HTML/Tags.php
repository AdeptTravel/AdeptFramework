<?php

namespace Adept\Component\CMS\Content\Admin\HTML;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;

class Tags extends \Adept\Abstract\Component\HTML\Items
{
  /**
   * Undocumented function
   */
  public function __construct()
  {
    parent::__construct();

    Application::getInstance()->html->head->meta->title = 'Tags';

    // Component controls
    $this->conf->controls->delete     = true;
    $this->conf->controls->duplicate  = false;
    $this->conf->controls->edit       = true;
    $this->conf->controls->new        = true;
    $this->conf->controls->publish    = false;
    $this->conf->controls->unpublish  = false;
  }

  public function getTable(): \Adept\Data\Table\Content
  {
    $get   = Application::getInstance()->session->request->data->get;
    $table = new \Adept\Data\Table\Content();

    if ($get->exists('status')) {
      $table->status = $get->getString('status', 'Active');
    }

    if (!$get->isEmpty('parent')) {
      $table->parentId = $get->getInt('parent');
    }

    if (!$get->isEmpty('level')) {
      $table->recursiveLevel = $get->getInt('level');
    }

    $table->title = $get->getString('search');
    $table->type = 'Tag';

    return $table;
  }
}
