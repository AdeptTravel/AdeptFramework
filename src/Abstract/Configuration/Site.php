<?php

namespace Adept\Abstract\Configuration;

defined('_ADEPT_INIT') or die();

use \Adept\Abstract\Configuration\Site\Legal;

class Site
{
  public string $name;
  public Legal $legal;
  public string $url;
  public string $template = 'Default';
  public string $images;

  public function __construct()
  {
    $this->legal = new Legal();
  }
}
