<?php

namespace AdeptCMS\Component\Site;

defined('_ADEPT_INIT') or die('No Access');

class Router extends \AdeptCMS\Base\Component\Router
{
  public function requireAuth()
  {
    return false;
  }
}
