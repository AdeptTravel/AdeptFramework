<?php

namespace AdeptCMS\Document\HTML\Body\Element;

defined('_ADEPT_INIT') or die();

class P extends \AdeptCMS\Base\Document\HTML\Body\Element
{
  public function getHTML(string|int $value = ''): string
  {
    if (!isset($this->html)) {
      $html = '<p';

      $html .= $this->getAttributes($this->params);

      if (isset($this->params->css)) {
        $html .= ' class="' . $this->params->css . '"';
      }

      $html .= '>';
      $html .= $this->params->content;

      $html .= '</p>';
      $this->html = $html;
    }

    return $this->html;
  }
}
