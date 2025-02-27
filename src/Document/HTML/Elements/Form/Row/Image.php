<?php

namespace Adept\Document\HTML\Elements\Form\Row;

defined('_ADEPT_INIT') or die();

use \Adept\Document\HTML\Elements\Img;

class Image extends \Adept\Document\HTML\Elements\Form\Row
{
  public string $label;

  // Textarea Specific Attributes
  public bool
    $autocomplete;
  public bool   $autofocus;
  public bool   $disabled;
  public string $name;
  public string $placeholder;
  public bool   $readonly;
  public bool   $required = false;
  public string $value;

  public function __construct(array $attr = [], array $children = [], bool $validate = false, bool $status = true)
  {
    $attrs = [];

    if (!empty($attr['css'])) {
      $attrs['css'] = $attr['css'];
    }

    if (!empty($attr['label'])) {
      $attrs['label'] = $attr['label'];
    }

    parent::__construct($attrs, $children);

    $this->css[] = 'image';

    $this->children[] = new \Adept\Document\HTML\Elements\Form\Image($attr);
  }
}
