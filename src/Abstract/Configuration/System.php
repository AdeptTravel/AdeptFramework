<?php

namespace Adept\Abstract\Configuration;

defined('_ADEPT_INIT') or die();

class System
{
  public bool   $acl   = true;
  public bool   $auth  = true;
  public bool   $cache = true;
  public bool   $debug = false;
  public string $name  = 'The Adept Framework';
}
