<?php

namespace AdeptCMS\Document\HTML\Body\Element;

defined('_ADEPT_INIT') or die();

class Asset extends \AdeptCMS\Base\Document\HTML\Body\Form\Element
{
  public function getHTML(string|int $value = ''): string
  {
    if (!isset($this->html)) {
      $this->doc->head->css->addFile('/css/form/asset.css');
      $this->doc->head->getJavaScript()->addFile('/js/form/asset.js');
      $error = (!empty($this->getErrors()));
      $value = $this->value;
      $warn = false;

      $info = '';

      $html  = '<div class="row asset';

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

      $html .= '<img id="' . $this->alias . '_img" src="/img/trans.gif">';

      $html .= '<input';
      $html .= ' id="' . $this->alias . '"';
      $html .= ' name="' . $this->alias . '"';
      $html .= ' type="hidden"';

      if (!empty($value)) {
        $html .= ' value = "' . $value . '"';
      }

      $html .= $this->getAttributes($this->params);

      $html .= '>';

      if (empty($value)) {
        $html .= '<button';
        $html .= ' type="button"';
        $html .= ' class="asset"';
        $html .= ' data-element="' . $this->alias . '"';
        $html .= '>';
        $html .= 'Select ' . $this->params->type;
        $html .= '</button>';
      } else {
      }

      if ($error) {
        $html .= '<i class="error warning required fas fa-exclamation-circle" aria-hidden="true"></i>';
      }

      if (!empty($info)) {
        $html .= '<i class="info fas fa-info-circle" aria-hidden="true"></i>';
      }

      if (isset($this->params->required)) {
        $html .= '<i class="required fa fa-asterisk" aria-hidden="true"></i>';
      }

      $html .= '</div>';

      $this->html = $html;
    }

    return $this->html;
  }
}
