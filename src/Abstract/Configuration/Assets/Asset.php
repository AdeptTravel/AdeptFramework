<?php

namespace Adept\Abstract\Configuration\Assets;

defined('_ADEPT_INIT') or die();

class Asset
{
  public bool $autoload = true;
  public bool $combine  = false;
  public bool $minify   = false;
}
