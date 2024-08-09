<?php

namespace Adept\Component\Route\HTML;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;

class Routes extends \Adept\Abstract\Component\HTML\Items
{
  /**
   * Undocumented function
   */
  public function __construct()
  {
    parent::__construct();

    Application::getInstance()->html->head->meta->title = 'Routes';

    // Component controls
    $this->conf->controls->delete     = false;
    $this->conf->controls->duplicate  = false;
    $this->conf->controls->edit       = false;
    $this->conf->controls->new        = true;
    $this->conf->controls->publish    = false;
    $this->conf->controls->unpublish  = false;
  }

  public function getItems(): \Adept\Data\Items\Route
  {
    return new \Adept\Data\Items\Route();
  }
}
