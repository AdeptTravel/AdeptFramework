<?php

namespace AdeptCMS\Document;

defined('_ADEPT_INIT') or die();

class JSON extends \AdeptCMS\Base\Document
{
  public function getBuffer(): string
  {
    return $this->component->getBuffer();
  }
}
