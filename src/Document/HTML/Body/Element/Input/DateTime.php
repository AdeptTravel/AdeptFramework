<?php

namespace AdeptCMS\Document\HTML\Body\Element\Input;

defined('_ADEPT_INIT') or die();

class DateTime extends \AdeptCMS\Document\HTML\Body\Element\Input
{
  public function getHTML(string $value = ''): string
  {
    if (!isset($this->html)) {
      $error = (!empty($this->errors));
      $warn = false;
      $info = '';

      $html  = '<div class="row';

      if (isset($this->params->required) && $this->params->required) {
        $html .= ' required';

        if ($error) {
          $html .= ' error';
        }
      }

      if (isset($this->params->css)) {
        $html .= ' ' . $this->params->css;
      }

      $html .= '">';

      if (!empty($this->title)) {
        $html .= '<label for="' . $this->alias . '">' . $this->title . '</label>';
      }

      $html .= '<input';
      $html .= ' name="' . $this->alias . '"';


      $html .= ' type="datetime-local"';

      if (!empty($this->value)) {
        $html .= ' value = "' . $this->value . '"';
      }

      $html .= $this->getAttributes($this->params);

      if (isset($this->conditions)) {
        $html .= $this->getHTMLConditions($this->conditions);
      }

      $html .= '>';
      //$html .= '<i class="success fas fa-check-circle" aria-hidden="true"></i>';';
      if ($error) {
        $html .= '<i class="error warning required fas fa-exclamation-circle" aria-hidden="true"></i>';
      }

      if (!empty($info)) {
        $html .= '<i class="info fas fa-info-circle" aria-hidden="true"></i>';
      }

      if (isset($this->params->required)) {
        $html .= '<i class="required fa fa-asterisk" aria-hidden="true"></i>';
      }

      //$html .= '<i class="fas fa-question-circle" aria-hidden="true"></i>';

      $html .= '</div>';

      $this->html = $html;
    }

    return $this->html;
  }
}
