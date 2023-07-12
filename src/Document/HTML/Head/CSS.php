<?php

namespace AdeptCMS\Document\HTML\Head;

defined('_ADEPT_INIT') or die();

class CSS extends \AdeptCMS\Base\Document\HTML\Head\Asset
{

  use \AdeptCMS\Traits\CSS;

  public function getFileTag(string $file, \stdClass $args = null): string
  {
    $tag  = '<link';
    $tag .= ' rel="stylesheet"';
    $tag .= ' href="' . str_replace(FS_CACHE, '', $file) . '"';
    $tag .= $this->formatArgs($args);
    $tag .= '>';

    return $tag;
  }

  public function getInlineTag(string $contents, \stdClass $args = null): string
  {
    $tag  = '<style' . $this->formatArgs($args) . '>';
    $tag .= $contents;
    $tag .= '</style>';

    return $tag;
  }

  public function addCSS(string $css)
  {
    $this->addInline($css);
  }
}
