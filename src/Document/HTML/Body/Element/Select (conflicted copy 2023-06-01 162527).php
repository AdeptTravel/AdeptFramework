<?php

namespace AdeptCMS\Document\HTML\Body\Element;

defined('_ADEPT_INIT') or die();

class Select extends \AdeptCMS\Base\Document\HTML\Body\Form\Element
{



  public function __construct(
    \AdeptCMS\Application\Database &$db,
    \AdeptCMS\Application\Session &$session,
    \AdeptCMS\Document\HTML\Head &$head,
    object &$element,
    string $alias = ''
  ) {

    parent::__construct($db, $session, $head, $element, $alias);

    if (isset($element->options)) {
      $this->options = $element->options;
    }
  }
  /*
  public function getHTML(string|int $value = ''): string
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

      $html .= '<select';
      $html .= ' name="' . $this->alias . '"';



      //if (isset($this->params->showon)) {
      //  $html .= ' data-showon="' . $this->params->showon . '"';
      //}
      if (isset($this->conditions)) {
        $html .= $this->getHTMLConditions($this->conditions);
      }

      $html .= $this->getAttributes($this->params);
      $html .= '>';

      foreach ($this->options as $option) {

        $html .= '<option value="' . $option->value . '"';

        if (isset($this->value) && strtolower($this->value) == strtolower($option->value)) {
          $html .= ' selected';
        } else if (isset($this->placeholder) && $this->placeholder) {
          $html .= ' disabled selected hidden';
        }

        if (isset($option->conditions)) {
          $html .= $this->getHTMLConditions($option->conditions);
        }

        $html .= '>' . $option->title . '</option>';
      }

      $html .= '</select>';
      $html .= '<i class="control fas fa-arrow-alt-circle-down"></i>';
      //$html .= '<i class="success fas fa-check-circle" aria-hidden="true"></i>';';
      //$html .= '<i class="error warning required fas fa-exclamation-circle" aria-hidden="true"></i>';';
      //$html .= '<i class="info fas fa-info-circle" aria-hidden="true"></i>';
      //$html .= '<i class="fas fa-question-circle" aria-hidden="true"></i>';
      //$html .= '<i class="required fa fa-asterisk" aria-hidden="true"></i>';
      $html .= '</div>';

      $this->html = $html;
    }

    return $this->html;
  }
  */
}
