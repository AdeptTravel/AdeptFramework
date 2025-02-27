<?php

namespace Adept\Abstract\Document\HTML;

defined('_ADEPT_INIT') or die();

abstract class Module
{
  public function __construct(object|null $params = null)
  {
    if (isset($params)) {
      foreach ($params as $k => $v) {
        if (property_exists($this, $k)) {
          $this->$k = $v;
        }
      }
    }
  }

  public abstract function getBuffer(): string;
}
