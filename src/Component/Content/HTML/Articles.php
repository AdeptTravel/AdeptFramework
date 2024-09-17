<?php

namespace Adept\Component\Content\HTML;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;

class Articles extends \Adept\Abstract\Component\HTML\Items
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

    Application::getInstance()->html->head->meta->title = 'Articles';

    // Component controls
    $this->conf->controls->delete     = false;
    $this->conf->controls->duplicate  = false;
    $this->conf->controls->edit       = false;
    $this->conf->controls->new        = true;
    $this->conf->controls->publish    = false;
    $this->conf->controls->unpublish  = false;
  }

  public function getItems(): \Adept\Data\Table\Content\Article
  {
    return new \Adept\Data\Table\Content\Article();
  }
}
