<?php

namespace Adept\Component\Menu\HTML;

defined('_ADEPT_INIT') or die('No Access');


use Adept\Application;
use Adept\Document\HTML;
use Adept\Document\HTML\Body\Status;
use Adept\Document\HTML\Head;


class Menu extends \Adept\Abstract\Component\HTML\Item
{
  /**
   * Undocumented function
   *
   * @param  \Adept\Application        $app
   * @param  \Adept\Document\HTML\Head $head
   */
  public function __construct(Application &$app, Head &$head)
  {
    parent::__construct($app, $head);

    if ($this->app->session->request->data->get->getInt('id', 0) > 0) {
      $head->meta->title = 'Edit Menu';
    } else {
      $head->meta->title = 'New Menu';
    }

    $this->conf->controls->save       = true;
    $this->conf->controls->saveclose  = true;
    $this->conf->controls->savecopy   = true;
    $this->conf->controls->savenew    = true;
    $this->conf->controls->close      = true;
  }

  public function getItem(int $id = 0): \Adept\Data\Item\Menu
  {
    return new \Adept\Data\Item\Menu($this->app->db, $id);
  }
}
