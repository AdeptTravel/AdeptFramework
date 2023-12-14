<?php

namespace Adept\Document\HTML\Elements;

defined('_ADEPT_INIT') or die();

class Tab extends \Adept\Abstract\Document\HTML\Element
{
  protected string $tag = 'div';
  // Element Specific Attributes

  public function __construct(array $attr = [], array $children = [])
  {
    parent::__construct($attr, $children);
    $this->css[] = 'tab';
  }

  public function getBuffer(): string
  {
    array_unshift($this->children, new H3(['text' => $this->title]));

    $this->title = '';

    return parent::getBuffer();
  }
}
