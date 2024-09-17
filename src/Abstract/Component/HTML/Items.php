<?php

namespace Adept\Abstract\Component\HTML;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;

abstract class Items extends \Adept\Abstract\Component\HTML
{
  /**
   * Init
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
  }

  abstract protected function getTable(): \Adept\Abstract\Data\Table;
}
