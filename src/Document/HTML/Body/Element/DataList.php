<?php

namespace AdeptCMS\Document\HTML\Body\Element;

defined('_ADEPT_INIT') or die();

class DataList extends \AdeptCMS\Base\Document\HTML\Body\Form\Element
{

  protected $options;

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

  public function getHTML(): string
  {
    if (!isset($this->html)) {

      $error = (!empty($this->getErrors()));
      $warn = false;
      $info = '';

      $html  = '<div class="row';

      if (isset($this->params->required) && $this->params->required) {
        $html .= ' required';

        if ($error) {
          $html .= ' error';
        }
      }

      $html .= '">';

      if (!empty($this->title)) {
        $html .= '<label for="' . $this->alias . '">' . $this->title . '</label>';
      }

      $html .= '<input';
      $html .= ' name="' . $this->alias . '"';
      $html .= ' list="' . $this->alias . '"';
      $html .= $this->getAttributes($this->params);
      $html .= '/>';
      $html .= '<datalist id="' . $this->alias . '">';

      foreach ($this->getOptions() as $option) {
        $html .= '<option value="' . $option->value . '"';

        if (isset($this->value) && $this->value == $option->value) {
          $html .= ' selected';
        }

        $html .= '>' . $option->title . '</option>';
      }

      $html .= '</datalist>';
      //$html .= '<i class="control fas fa-arrow-alt-circle-down"></i>';

      $this->html = $html;
    }

    return $this->html;
  }

  public function getOptions(): array
  {
    return $this->options;
  }
}
