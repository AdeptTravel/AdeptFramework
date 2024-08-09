<?php

namespace Adept\Document\HTML\Elements\Input;

defined('_ADEPT_INIT') or die();

use \Adept\Document\HTML\Elements\Span;
use \Adept\Document\HTML\Elements\Input\Checkbox;

class Toggle extends \Adept\Document\HTML\Elements\Label
{

  public function __construct(array $attr = [], array $children = [])
  {
    parent::__construct($attr, $children);

    $this->css[] = 'toggle';

    $this->children[] = new Checkbox($attr);
    $this->children[] = new Span(['css' => ['slider round']]);
  }
}
