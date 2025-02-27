<?php

namespace Adept\Component\Error\JSON;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Abstract\Document;
use \Adept\Application;


class Error extends \Adept\Abstract\Component
{
  public function getBuffer(string $template = ''): string
  {
    $data = (object)[
      'status' => 'error',
      'type' => 404
    ];

    return json_encode($data);
  }
}
