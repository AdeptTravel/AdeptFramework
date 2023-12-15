<?php

namespace Adept\Document\HTML\Head;

defined('_ADEPT_INIT') or die();

class JavaScript extends \Adept\Abstract\Document\HTML\Head\Asset
{
  public function getFileTag(string $file, \stdClass $args = null): string
  {
    $tag  = '<script';
    $tag .= ' src="' . str_replace(FS_SITE_CACHE, '', $file) . '"';
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

  public function minify(string $js): string
  {
    // Remove preceeding and trailing spaces
    $js = trim($js);

    // Nomalize linebreaks
    str_replace(["\r\n", "\r"], "\n", $js);

    // Remove comments
    $pattern = '/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/';
    $js = preg_replace($pattern, '', $js);

    // Minify 
    $minifier = new \MatthiasMullie\Minify\JS();
    $minifier->add($js);
    $js = $minifier->minify();

    // Final trim
    $js = trim($js);

    return $js;
  }
}
