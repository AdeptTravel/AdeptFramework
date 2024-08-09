<?php

namespace Adept\Document\HTML\Elements\Form\Row;

defined('_ADEPT_INIT') or die();

use \Adept\Document\HTML\Elements as Elements;
use \Adept\Document\HTML\Elements\Span;


class Input extends \Adept\Document\HTML\Elements\Form\Row
{
  protected string $inputNameSpace = "\\Adept\\Document\\HTML\\Elements\\Input";


  public string $label;
  public bool $required = false;

  // Input Specific Attributes
  public string $accept;
  public string $alt;
  public string $autocomplete;
  public bool   $autofocus;
  public bool   $checked;
  public string $dirname;
  public bool   $disabled;
  public string $form;
  public string $formaction;
  public string $formenctype;
  public string $formmethod;
  public bool   $formnovalidate;
  public string $formtarget;
  public int    $height;
  public string $list;
  public string $max;
  public int    $maxlength;
  public string $min;
  public int    $minlength;
  public bool   $multiple;
  public string $name;
  public string $pattern;
  public string $placeholder;
  public bool   $readonly;
  public int    $size;
  public string $src;
  public string $step;
  public string $type;
  public string $usemap;
  public string $value;
  public int    $width;

  public function __construct(array $attr = [], array $children = [], bool $validate = false, bool $status = true)
  {
    parent::__construct($attr, $children);

    $this->css[] = 'input';

    $attr = [
      'accept',
      'alt',
      'autocomplete',
      'autofocus',
      'checked',
      'dirname',
      'disabled',
      'form',
      'formaction',
      'formenctype',
      'formmethod',
      'formnovalidate',
      'formtarget',
      'height',
      'list',
      'max',
      'maxlength',
      'min',
      'minlength',
      'multiple',
      'name',
      'pattern',
      'placeholder',
      'readonly',
      'required',
      'size',
      'src',
      'step',
      'type',
      'usemap',
      'value',
      'width'
    ];

    //$input = new Elements\Input([]);
    $input = new $this->inputNameSpace([]);

    for ($i = 0; $i < count($attr); $i++) {
      $key = $attr[$i];

      if (!empty($this->$key)) {

        if ($key == 'label' && empty($this->placeholder)) {
          $input->placeholder = $this->label;
        }

        $input->$key = $this->$key;

        if ($key != 'required') {
          unset($this->$key);
        }
      }
    }

    $this->children[] = $input;
  }
}
