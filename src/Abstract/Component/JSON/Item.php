<?php

namespace Adept\Abstract\Component;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Abstract\Configuration\Component;

class JSON extends \Adept\Abstract\Component
{
  /**
   * Undocumented variable
   *
   * @var \Adept\Abstract\Configuration\Component
   */
  public Component $conf;

  /**
   * Undocumented function
   *
   * @param  \Adept\Application        $app
   * @param  \Adept\Document\HTML\Head $head
   */
  public function __construct()
  {
    $app = \Adept\Application::getInstance();

    $this->conf = $app->conf->component;
  }
}
