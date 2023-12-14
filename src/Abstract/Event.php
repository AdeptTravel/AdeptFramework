<?php

namespace Adept\Abstract;

defined('_ADEPT_INIT') or die();

abstract class Event
{

  public function __construct()
  {
    $this->run();
  }

  abstract public function run();
}
