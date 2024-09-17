<?php

namespace Adept\Abstract\Component\JSON;

use Adept\Application;

defined('_ADEPT_INIT') or die('No Access');

abstract class Items extends \Adept\Abstract\Component\JSON
{
  /**
   * Init
   */
  public function __construct()
  {
    $app  = Application::getInstance();
    $post = $app->session->request->data->post;

    if (!empty($action = $post->getString('action'))) {
      switch ($action) {
        case 'toggle':
          $id = $post->getInt('id');
          $col = $post->getString('column');
          $val = $post->getInt('value');
          $this->data['status'] = false;
          $this->data['id'] = $id;
          $this->data['column'] = $col;
          $this->data['value'] = $val;
          if ($id > 0 && $col != '' && ($val == 0 || $val == 1)) {
            $this->data['status'] = $this->toggle($id, $col, $val);
          }
      }
    }
  }

  public function toggle(int $id, string $col, bool $val): bool
  {
    $item = $this->getItem($id);
    $item->$col = $val;
    return $item->save();
  }

  abstract protected function getTable(): \Adept\Abstract\Data\Table;
  abstract protected function getItem(int $id): \Adept\Abstract\Data\Item;
}
