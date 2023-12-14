<?php

namespace Adept\Document\HTML\Elements\Form;

defined('_ADEPT_INIT') or die();

use \Adept\Document\HTML\Elements\Span;

class Row extends \Adept\Document\HTML\Elements\Div
{

  public string $label;
  public bool $required = false;

  public function __construct(array $attr = [], array $children = [], bool $validate = false, bool $status = true)
  {
    parent::__construct($attr, $children);

    $this->css[] = 'row';

    if ($status && ($this->required || $validate)) {
      $this->css[] = 'isValid';
      $this->children[] = new Span(['css' => ['status']]);
    }
  }

  function getBuffer(): string
  {
    $html  = "<div" . $this->getAttr() . '>';

    if (isset($this->label)) {
      $html .= '<label>' . $this->label . '</label>';
    }

    if (!empty($this->html)) {
      $html .= $this->html;
    } else if (!empty($this->text)) {
      $html .= $this->text;
    }

    if (!empty($this->children)) {
      for ($i = 0; $i < count($this->children); $i++) {
        $html .= $this->children[$i]->getBuffer();
      }
    }
    /*
    if (in_array('edit', $this->css)) {
      $html .= '<div class="controls">';
      $html .= '<i class="fa-solid fa-pen-to-square edit"></i>';
      $html .= '<i class="fa-solid fa-circle-check save"></i>';
      $html .= '<i class="fa-solid fa-circle-xmark cancel"></i>';
      $html .= '</div>';
    } else if (in_array('repeat', $this->css)) {
      $html .= '<div class="controls">';
      $html .= '<i class="fa-solid fa-circle-plus new"></i>';
      $html .= '<i class="fa-solid fa-circle-minus del"></i>';
      $html .= '</div>';
    }
    */
    $html .= "</div>";

    return $html;
  }
}
