<?php

namespace AdeptCMS\Document\HTML\Head;

defined('_ADEPT_INIT') or die();

class JavaScript extends \AdeptCMS\Base\Document\HTML\Head\Asset
{
  use \AdeptCMS\Traits\JavaScript;

  public function getFileTag(string $file, \stdClass $args = null): string
  {
    $tag  = '<script';
    $tag .= ' src="' . str_replace(FS_CACHE, '', $file) . '"';
    $tag .= $this->formatArgs($args);
    $tag .= '></script>';

    return $tag;
  }

  public function getInlineTag(string $contents, \stdClass $args = null): string
  {
    $tag  = '<script' . $this->formatArgs($args) . '>';
    $tag .= $contents;
    $tag .= '</script>';

    return $tag;
  }

  public function addJavaScript(string $js)
  {
    $this->addInline($js);
  }
}
