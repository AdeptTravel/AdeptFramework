<?php

namespace Adept\Component\System\Route\Admin\HTML;

defined('_ADEPT_INIT') or die('No Access');


use Adept\Application;

class Route extends \Adept\Abstract\Component\HTML\Item
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

    $app = Application::getInstance();

    if ($app->session->request->data->get->getInt('id', 0) > 0) {
      $app->html->head->meta->title = 'Edit Route';
    } else {
      $app->html->head->meta->title = 'New Route';
    }

    $this->conf->controls->save       = true;
    $this->conf->controls->saveclose  = true;
    $this->conf->controls->savecopy   = true;
    $this->conf->controls->savenew    = true;
    $this->conf->controls->close      = true;
  }

  public function getItem(int $id = 0): \Adept\Data\Item\Route
  {
    $item = new \Adept\Data\Item\Route();

    if ($id > 0) {
      $item->loadFromID($id);
    }

    return $item;
  }

  public static function getBreadcrumbTitle(string $area, string $view, array $args = []): string
  {
    return 'Route';
  }
}
