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
    $id   = $post->getInt('id');
    $ids  = explode(',', $post->getString('ids'));
    $col  = $post->getString('column');
    $val  = $post->getInt('value');

    if (!empty($action = $post->getString('action'))) {

      if ($action == 'delete' || $action == 'publish' || $action == 'unpublish') {
        $this->data['status'] = [];

        for ($i = 0; $i < count($ids); $i++) {
          $this->data['status'][$ids[$i]] = $this->$action($ids[$i]);
        }
      } else if ($action == 'toggle') {
        $id  = $post->getInt('id');
        $col = $post->getString('column');
        $val = $post->getInt('value');

        $this->data['id']     = $id;
        $this->data['column'] = $col;
        $this->data['status'] = 'fail';
        $this->data['value']  = $val;

        if ($id > 0 && $col != '' && ($val == 0 || $val == 1)) {
          $this->data['status'] = ($this->toggle($id, $col, $val)) ? 'success' : 'fail';
        }
      }
    }
  }

  public function toggle(int $id, string $col, int $val): bool
  {

    file_put_contents($col . ' - ' . $val, FS_SITE_LOG . 'debug.log', FILE_APPEND);
    $item = $this->getItem($id);
    $item->$col = $val;
    $status = $item->save();
    return $status;
  }

  public function archive(int $id)
  {
    return $this->toggle($id, 'status', ITEM_STATUS_ARCHIVE);
  }


  public function delete(int $id)
  {

    return $this->toggle($id, 'status', ITEM_STATUS_TRASH);
  }

  public function publish(int $id)
  {
    return $this->toggle($id, 'status', ITEM_STATUS_ON);
  }

  public function unpublish(int $id)
  {
    return $this->toggle($id, 'status', ITEM_STATUS_OFF);
  }

  abstract protected function getTable(): \Adept\Abstract\Data\Table;
  abstract protected function getItem(int $id): \Adept\Abstract\Data\Item;
}
