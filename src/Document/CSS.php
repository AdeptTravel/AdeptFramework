<?php

namespace AdeptCMS\Document;

defined('_ADEPT_INIT') or die();

class CSS extends \AdeptCMS\Base\Document
{
  use \AdeptCMS\Traits\CSS;

  public function getBuffer(): string
  {
    if (!isset($this->buffer)) {

      $buffer = '';

      if ($this->app->conf->optimize->css->minify) {
        $minified = str_replace('.css', '.min.css', $this->file);
        // Check if cached file exists
        if (file_exists($cache = str_replace(FS_TEMPLATE, FS_CACHE, $minified)) && filemtime($cache) > filemtime($minified)) {
          $buffer = file_get_contents($cache);
        } else {
          $buffer = $this->minify($this->file);
          $this->saveFile($minified, $this->buffer);
        }
      } else {
        $buffer = file_get_contents($this->file);
      }

      $this->buffer = $buffer;
    }

    return $this->buffer;
  }
}
