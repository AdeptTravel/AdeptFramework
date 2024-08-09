<?php

namespace Adept\Abstract\Component\JSON;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;

abstract class Items extends \Adept\Abstract\Component\HTML
{

  protected array $params = [];

  /**
   * Undocumented function
   *
   * @param  \Adept\Application        $app
   * @param  \Adept\Document\HTML\Head $head
   */
  public function __construct()
  {
    parent::__construct();

    // Component controls
    $this->conf->controls->delete     = true;
    $this->conf->controls->duplicate  = true;
    $this->conf->controls->edit       = true;
    $this->conf->controls->new        = true;
    $this->conf->controls->publish    = true;
    $this->conf->controls->unpublish  = true;

    $app     = \Adept\Application::getInstance();
    $get     = &$app->session->request->data->get;
    $post    = &$app->session->request->data->post;
    $id      = $post->getInt('id', 0);
    $action  = $post->getString('action', '');
    $sort    = $get->getString('sort', '');
    $dir     = $get->getString('dir', 'asc');
    $reflect = new \ReflectionClass($this);

    $col = null;
    $val = null;

    if (!empty($sort)) {
      $this->params['sort'] = $sort;
      $this->params['dir'] = $dir;
    }

    // Filter is used when displaying multiple items
    if ($action == 'filter') {
      $data = $post->getArray();

      foreach ($data as $k => $v) {
        if ($reflect->hasProperty($k)) {
          $this->params[$k] = $v;
        }
      }
    }

    $status = false;

    switch ($action) {

      case 'save':
        $status = $this->save($id);
        break;

      case 'delete':
        $status = $this->delete($id);
        break;

      case 'toggle':
        $col = $post->getString('col', '');
        $val = $post->getBool('val');

        if (!empty($col)) {
          $status = $this->toggle($id, $col, $val);
        }

        break;

      default:
        break;
    }

    $this->data = (object)[
      'id' => $id,
      'act' => $action,
      'status' => $status
    ];

    if (isset($col)) {
      $this->data->col = $col;
    }

    if (isset($val)) {
      $this->data->val = $val;
    }
  }

  abstract function getItem(int $id);
  abstract function getItems(): \Adept\Abstract\Data\Items;

  public function save(int $id): bool
  {
    $app = Application::getInstance();
    $item = $this->getItem($id);
    $item->loadFromPost($app->session->request->data->post);
    return $item->save();
  }

  public function delete($id): bool
  {
    $item = $this->getItem($id);
    return $item->delete();
  }

  public function toggle(int $id, string $col, bool $val): bool
  {
    $status = false;

    $item = $this->getItem($id);

    if ($item->id > 0) {
      $item->$col = $val;
      $status = $item->save();
    }

    return $status;
  }
}
