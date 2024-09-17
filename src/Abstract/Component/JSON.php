<?php

namespace Adept\Abstract\Component;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Abstract\Configuration\Component;

class JSON extends \Adept\Abstract\Component
{

  public array $data = [];

  public function getJSON(bool $asObject = false): string
  {
    $data = ($asObject) ? (object)$this->data : $this->data;
    return json_encode($data);
  }
}
