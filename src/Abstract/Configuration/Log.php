<?php

namespace Adept\Abstract\Configuration;

defined('_ADEPT_INIT') or die();

use \Adept\Abstract\Configuration\Site\Legal;

class Log
{
  public bool $auth    = false;
  public bool $error   = true;
  public bool $get     = false;
  public bool $post    = false;
  public bool $query   = false;
  public bool $request = false;
  public bool $session = false;
  public bool $warning = true;
}
