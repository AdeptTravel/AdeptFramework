<?php

namespace AdeptCMS\Base\Document\HTML\Body\Form;
/*
use AdeptCMS\CMSClass;
use AdeptCMS\HTML\P;
*/


defined('_ADEPT_INIT') or die();

abstract class Element extends \AdeptCMS\Base\Document\HTML\Body\Element
{

  /**
   * Used with the Input Element
   *
   * @var array
   */
  protected $datalist;

  /**
   * Array of errors
   * 
   * @var array
   */
  protected $errors = [];

  /**
   * Information about the element
   *
   * @var string
   */
  protected $info;

  /**
   * Used for the Select Element
   *
   * @var array
   */
  protected $options;

  /**
   * Used by Input, TextArea, and any of their derivities.
   *
   * @var [type]
   */
  public $placeholder;

  /**
   * Form element value
   * 
   * @var string
   */
  public $value;

  /*
  protected function loadChildren(array $children)
  {
    $this->children = [];
    $this->value = '';

    for ($i = 0; $i < count($children); $i++) {

      $alias = $this->alias . '_' . $children[$i]->alias . '_' . $i;
      $parts = explode('.', $children[$i]->type);

      $namespace = "\\AdeptCMS\\Document\\HTML\\Body\\Element";

      for ($j = 0; $j < count($parts); $j++) {
        $namespace .= "\\" . $parts[$j];
      }

      echo "\n<!-- $namespace -->";

      $this->children[$alias] = new $namespace($this->session, $this->head, $children[$i], $alias);
    }
  }
*/
  protected function getHTMLConditions($conditions): string
  {

    if (empty($conditions->container)) {
      $conditions->container = '.row';
    }

    return parent::getHTMLConditions($conditions);
  }

  public function getHTML(string $value = ''): string
  {
    $type = strtolower($this->type);

    if (strpos($type, '.') !== false) {
      $type = substr($type, 0, strpos($type, '.'));
    }

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

      if (!empty($this->params->css)) {
        $this->params->css = str_replace([
          'full',
          'twothirds',
          'threequarters',
          'half',
          'third',
          'quarter'
        ], '', $this->params->css);
      }

      $html .= '">';

      if (!empty($this->title)) {
        $html .= '<label for="' . $this->alias . '">' . $this->title . '</label>';
      }

      $html .= '<' . $type;
      $html .= ' id="' . $this->alias . '"';
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

      //$html .= ' data-formtype='
      $element = str_replace("AdeptCMS\\Document\\HTML\Body\\Element\\", '', get_Class($this));
      $element = str_replace("\\", '.', $element);

      $html .= ' data-element="' . $element . '"';

      $html .= '>';
      $html .= "\n\n";


      if (!empty($this->options)) {

        if (!empty($this->datalist)) {
          $html .= '<datalist id="' . $this->datalist . '">';
          $html .= "\n\n";
        }

        for ($i = 0; $i < count($this->options); $i++) {
          $html .= "\n\n";

          $html .= '<option value="' . $this->options[$i]->value . '"';

          if (isset($this->value) && strtolower($this->value) == strtolower($this->options[$i]->value)) {
            $html .= ' selected';
          } else if (isset($this->placeholder) && $this->placeholder) {
            $html .= ' disabled selected hidden';
          }

          if (isset($this->options[$i]->conditions)) {
            $html .= $this->getHTMLConditions($this->options[$i]->conditions);
          }

          $html .= '>' . $this->options[$i]->title . '</option>';

          if (!empty($this->datalist)) {
            $html .= '</datalist>';
          }
        }
      }

      if ($type == 'textarea' || $type == 'select') {
        $html .= '</' . $type . '>';
        $html .= "\n\n";
      }

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

  public function setError(string $error)
  {
    $this->errors[] = $error;
  }

  public function validate()
  {
    $required = $this->params->required;

    if (isset($required) && $required) {
      if (empty($this->value)) {
        $this->setError($this->title . ' is required.');
      }
    }
  }
}
