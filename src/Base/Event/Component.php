<?php

namespace AdeptCMS\Base\Event;

defined('_ADEPT_INIT') or die();

abstract class Application extends \AdeptCMS\Base\Event
{
  protected $app;

  public function __construct(\AdeptCMS\Application &$app)
  {
    $this->app = $app;

    parent::__construct();
  }
}
