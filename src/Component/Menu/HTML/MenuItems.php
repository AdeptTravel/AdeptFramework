<?php

namespace Adept\Component\Menu\HTML;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;
use Adept\Document\HTML\Head;

class MenuItems extends \Adept\Abstract\Component\HTML\Items
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
    $app->html->head->meta->title = 'Menu Items';

    // Component controls
    $this->conf->controls->delete     = false;
    $this->conf->controls->duplicate  = false;
    $this->conf->controls->edit       = false;
    $this->conf->controls->new        = true;
    $this->conf->controls->publish    = false;
    $this->conf->controls->unpublish  = false;
  }

  protected function getItems(): \Adept\Data\Items\Menu\Item
  {
    return new \Adept\Data\Items\Menu\Item();
  }
}
