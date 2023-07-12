<?php

namespace AdeptCMS\Document;

defined('_ADEPT_INIT') or die();

class JavaScript extends \AdeptCMS\Base\Document
{
  use \AdeptCMS\Traits\JavaScript;

  public function getBuffer(): string
  {
    if (!isset($this->buffer)) {

      $buffer = '';

      if ($this->app->conf->optimize->js->minify) {
        $minified = str_replace('.js', '.min.js', $this->file);
        // Check if cached file exists
        if (file_exists($cache = str_replace(FS_TEMPLATE, FS_CACHE, $minified)) && filemtime($cache) > filemtime($minified)) {
          $buffer = file_get_contents($cache);
        } else {
          $buffer = file_get_contents($this->file);
          $buffer = $this->minify($buffer);
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
