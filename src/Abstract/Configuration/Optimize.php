<?php

namespace Adept\Abstract\Configuration;

defined('_ADEPT_INIT') or die();

use \Adept\Abstract\Configuration\Optimize\Asset;

class Optimize
{
  /**
   * Undocumented variable
   *
   * @var \Adept\Abstract\Configuration\Optimize\Asset
   */
  public Asset $css;

  /**
   * Undocumented variable
   *
   * @var \Adept\Abstract\Configuration\Optimize\Asset
   */
  public Asset $js;


  public function __construct()
  {
    $this->css = new Asset();
    $this->js = new Asset();
  }
}
