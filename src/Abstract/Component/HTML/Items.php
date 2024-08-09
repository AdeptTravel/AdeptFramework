<?php

namespace Adept\Abstract\Component\HTML;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;

abstract class Items extends \Adept\Abstract\Component\HTML
{
  /**
   * Undocumented variable
   *
   * @var \Adept\Abstract\Data\Items
   */
  protected \Adept\Abstract\Data\Items $items;

  /**
   * Undocumented function
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

    $app  = \Adept\Application::getInstance();
    $get  = $app->session->request->data->get;
    $data = $get->getArray();

    $this->items = $this->getItems();

    $this->items->sort = $get->getString('sort', '');
    $this->items->dir  = $get->getString('dir', 'asc');

    // Now we use reflection to look at the namespace of the return type and 
    // get a list of all the public variables.
    $reflectionClass = new \ReflectionClass($this->items);
    $properties = $reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC);

    foreach ($properties as $property) {
      $key = $property->getName();

      if (isset($data[$key])) {
        $this->items->$key = $data[$key];
      }
    }
  }

  abstract protected function getItems(): \Adept\Abstract\Data\Items;
  /*
  public function save(int $id): bool
  {
    $status = false;

    if ($id > 0) {
      $app  = Application::getInstance();
      $item = $this->getItem($id);
      $item->loadFromPost($app->session->request->data->post);
      $status = $item->save();
    }

    return $status;
  }

  public function delete($id): bool
  {
    $status = false;

    if ($id > 0) {
      $item = $this->getItem($id);
      $status = $item->delete();
    }

    return $status;
  }

  public function toggle(int $id, string $col, bool $val): bool
  {
    $status = false;

    if ($id > 0) {
      $item = $this->item->getItem($id);
      $item->$col = $val;
      $status = $item->save();
    }

    return $status;
  }
  */
}
