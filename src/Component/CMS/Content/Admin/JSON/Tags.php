<?php

namespace Adept\Component\Content\JSON;

defined('_ADEPT_INIT') or die('No Access');


use Adept\Application;

class Tags extends \Adept\Abstract\Component\JSON\Table
{
  protected bool $recursive = true;

  public function getTable(): \Adept\Data\Table\Content
  {
    $get   = Application::getInstance()->session->request->data->get;
    $table = new \Adept\Data\Table\Content();

    if ($get->exists('status')) {
      $table->status = $get->getString('status', 'Active');
    }
    $table->columns = ['id', 'title', 'titlePath'];

    $table->type = 'Tag';

    return $table;
  }
}
