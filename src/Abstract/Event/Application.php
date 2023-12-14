<?php

namespace Adept\Abstract\Event;

defined('_ADEPT_INIT') or die();

abstract class Application extends \Adept\Abstract\Event
{
  protected $app;

  public function __construct(\Adept\Application &$app)
  {
    $this->app = $app;

    parent::__construct();
  }
}
