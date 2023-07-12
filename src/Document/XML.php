<?php

namespace AdeptCMS\Document;

defined('_ADEPT_INIT') or die();

class XML extends \AdeptCMS\Document
{
  public function getBuffer(): string
  {
    if (!isset($this->buffer)) {
      $this->buffer = '';

      if (file_exists($this->file)) {
        $this->buffer = file_get_contents($this->file);
      }
    }

    return $this->buffer;
  }
}
