<?php

namespace Adept\Document;

defined('_ADEPT_INIT') or die();

use \Adept\Application;

class XML extends \Adept\Abstract\Document
{
  /**
   * Init
   */
  public function __construct(Application &$app)
  {
    parent::__construct($app);
  }

  public function getBuffer(): string
  {

    return $this->component->getBuffer();
  }
}
