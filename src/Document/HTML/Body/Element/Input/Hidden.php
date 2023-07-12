<?php

namespace AdeptCMS\Document\HTML\Body\Element\Input;

defined('_ADEPT_INIT') or die();

class Hidden extends \AdeptCMS\Document\HTML\Body\Element\Input
{
  public function getHTML(string|int $value = ''): string
  {
    $html  = '<input';
    $html .= ' name="' . $this->alias . '"';
    $html .= ' type="hidden"';

    if (!empty($this->value)) {
      $html .= ' value = "' . $this->value . '"';
    }

    $html .= $this->getAttributes($this->params);
    $html .= '>';

    $this->html = $html;

    return $this->html;
  }
}
