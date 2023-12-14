<?php

namespace Adept\Document\HTML\Body\Element\Input;

defined('_ADEPT_INIT') or die();

class Text extends \Adept\Document\HTML\Body\Element\Input
{
  /*
  public function getBuffer(): string
  {
    if (!isset($this->html)) {
      $error = (!empty($this->getErrors()));
      $warn = false;
      $info = '';

      $html  = '<div class="row';

      //if (isset($this->params->showon)) {
      //  $html .= ' showon';
      //}

      if (isset($this->params->required) && $this->params->required) {
        $html .= ' required';

        if ($error) {
          $html .= ' error';
        }
      }

      $html .= '"';
      
      //if (isset($this->params->showon)) {
      //  $html .= ' data-showon="' . $this->params->showon . '"';
      //}
      

      $html .= '>';

      if (!empty($this->title)) {
        $html .= '<label for="' . $this->alias . '">' . $this->title . '</label>';
      }

      $html .= '<' . $this->type;
      //$html .= ' id="' . $this->alias . '"';
      $html .= ' name="' . $this->alias . '"';

      if (isset($this->params->type)) {
        $html .= ' type="' . $this->params->type . '"';
      }

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
  */
}
