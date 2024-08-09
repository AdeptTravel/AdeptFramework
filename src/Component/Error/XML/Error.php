<?php

namespace Adept\Component\Error\XML;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Abstract\Document;
use \Adept\Application;


class Error extends \Adept\Abstract\Component
{
  public function getBuffer(string $template = ''): string
  {
    return '';
  }
}
