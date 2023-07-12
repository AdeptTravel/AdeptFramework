<?php

namespace AdeptCMS\Document\HTML\Head;

defined('_ADEPT_INIT') or die();

class Link
{

  protected $links;

  public function __construct()
  {
    $this->links = [];
  }

  public function add(string $href, string $rel, array $args = [])
  {
    if (!array_key_exists($href, $this->links)) {
      $obj = new \stdClass();
      $obj->href = $href;
      $obj->rel = $rel;
      foreach ($args as $key => $value) {
        $obj->$key = $value;
      }

      $this->links[$href] = $obj;
    }
  }

  public function getHTML(): string
  {
    $html = '';

    foreach ($this->links as $link) {
      $html .= '<link';

      foreach ($link as $key => $value) {
        $html .= ' ' . $key;

        if (!empty($value)) {
          $html .= '="' . $value . '"';
        }
      }

      $html .= '>';
    }

    return $html;
  }
}
