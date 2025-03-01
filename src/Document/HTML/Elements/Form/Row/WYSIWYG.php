<?php

namespace Adept\Document\HTML\Elements\Form\Row;

defined('_ADEPT_INIT') or die();

use Adept\Application;
use Adept\Document\HTML\Elements\Form\Row;

class WYSIWYG extends \Adept\Document\HTML\Elements\Form\WYSIWYG
{
  public string $label;

  public function getBuffer(): string
  {
    $this->css[] = 'wysiwyg';
    $attrs = ['label', 'css', 'required', 'showOn', 'hideOn'];
    $attr  = [];

    for ($i = 0; $i < count($attrs); $i++) {
      $key = $attrs[$i];

      if (isset($this->$key)) {
        $attr[$key] = $this->$key;
        //unset($this->$key);
      }
    }

    $row = new Row($attr);
    $row->html = parent::getBuffer();

    return $row->getBuffer();
  }
}
