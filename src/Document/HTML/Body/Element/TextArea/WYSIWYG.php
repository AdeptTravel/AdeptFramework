<?php

namespace AdeptCMS\Document\HTML\Body\Element;

defined('_ADEPT_INIT') or die();

class WYSIWYG extends \AdeptCMS\Document\HTML\Body\Element\TextArea
{

  public function getHTML(string $value = ''): string
  {
    if (!isset($this->html)) {

      $this->head->css->addFile('/css/medium/medium-editor.css');
      $this->head->css->addFile('/css/medium/themes/default.css', ['id' => 'medium-editor-theme']);
      $this->head->javascript->addFile('/js/medium/medium-editor.js');

      $this->params->css .= ' editable medium-editor-textarea';


      $html .= '<script>';

      $html .= "var editor = new MediumEditor('#" . $this->alias . "', {";
      $html .= "buttonLabels: 'fontawesome'";
      $html .= "});";
      $html .= '</script>';


      $html .= '</div>';

      $this->html = $html;
    }

    return $this->html;
  }
}
