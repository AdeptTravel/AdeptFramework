<?php

namespace Adept\Abstract\Configuration;

defined('_ADEPT_INIT') or die();

use \Adept\Abstract\Configuration\Assets\Asset;

class Assets
{
  public \Adept\Abstract\Configuration\Assets\Asset $css;
  public \Adept\Abstract\Configuration\Assets\Asset $js;

  public function __construct()
  {
    $this->css = new Asset();
    $this->js  = new Asset();
  }
}
