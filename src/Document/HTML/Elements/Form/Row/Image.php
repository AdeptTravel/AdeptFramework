<?php

namespace Adept\Document\HTML\Elements\Form\Row;

defined('_ADEPT_INIT') or die();

use \Adept\Document\HTML\Elements\Img;

class Image extends \Adept\Document\HTML\Elements\Form\Row
{
  public string $label;

  // Textarea Specific Attributes
  public string $autocomplete;
  public bool   $autofocus;
  public bool   $disabled;
  public string $name;
  public string $placeholder;
  public bool   $readonly;
  public bool   $required = false;
  public string $value;

  public function __construct(array $attr = [], array $children = [], bool $validate = false, bool $status = true)
  {
    parent::__construct($attr, $children);

    $this->css[] = 'image';

    $this->children[] = new \Adept\Document\HTML\Elements\Form\Image([
      'value' => $this->value
    ]);
  }
}
