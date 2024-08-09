<?php

namespace Adept\Abstract\Configuration;

defined('_ADEPT_INIT') or die();

use \Adept\Abstract\Configuration\Component\Controls;

class Component
{
  public Controls $controls;

  public function __construct()
  {
    $this->controls = new Controls();
  }
}
