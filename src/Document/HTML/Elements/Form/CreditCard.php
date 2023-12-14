<?php

namespace Adept\Document\HTML\Elements\Form;

defined('_ADEPT_INIT') or die();

use \Adept\Document\HTML\Elements\Span;

class Row extends \Adept\Document\HTML\Elements\Div
{
  public string $label;

  public bool $required = false;

  public function __construct(array $attr = [], array $children = [], bool $validate = false)
  {
    parent::__construct($attr, $children);

    $this->css[] = 'row';

    if ($this->required || $validate) {
      $this->css[] = 'isValid';
      $this->children[] = new Span(['css' => ['status']]);
    }
  }
}
