<?php

namespace Adept\Abstract;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Application;

class Component
{
  public function getBuffer(string $template = ''): string
  {
    $app = \Adept\Application::getInstance();

    $buffer     = '';
    $method     = 'get' . $app->session->request->url->type;

    if (method_exists($this, $method)) {
      $buffer = $this->$method($template);
    }

    return $buffer;
  }
}
