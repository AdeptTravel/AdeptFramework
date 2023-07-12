<?php

namespace AdeptCMS\Component\Admin;

defined('_ADEPT_INIT') or die('No Access');

class Router extends \AdeptCMS\Base\Component\Router
{
  public function requireAuth(): bool
  {
    return true;
  }
}
