<?php

namespace AdeptCMS\Document;

defined('_ADEPT_INIT') or die();

class Font extends \AdeptCMS\Base\Document
{
  public function getBuffer(): string
  {
    return file_get_contents($this->file);
  }
}
