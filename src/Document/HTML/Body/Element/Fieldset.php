<?php

namespace AdeptCMS\Document\HTML\Body\Element;

defined('_ADEPT_INIT') or die();

class Fieldset extends \AdeptCMS\Base\Document\HTML\Body\Element
{
  public function getHTML(string|int $value = ''): string
  {
    if (!isset($this->html)) {
      $html = '<fieldset';
      $html .= $this->getAttributes($this->params);
      $html .= ' name="fieldset_' . $this->alias . '"';

      //if (isset($this->params->css)) {
      //  $html .= ' class="' . $this->params->css . '"';
      //}

      $html .= '>';

      $html .= '<legend>';

      if (isset($this->params->fa)) {
        $html .= '<i class="' . $this->params->fa . '"></i>';
      }

      $html .= $this->title;
      $html .= '</legend>';

      foreach ($this->children as $child) {
        $html .= $child->getHTML();
      }

      $html .= '</fieldset>';

      $this->html = $html;
    }

    return $this->html;
  }
}
