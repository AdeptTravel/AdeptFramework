<?php

namespace Adept\Document\HTML\Elements\Form\Textbox;

defined('_ADEPT_INIT') or die();

use Adept\Application;

use \Adept\Document\HTML\Elements\Button;
use \Adept\Document\HTML\Elements\Div;
use \Adept\Document\HTML\Elements\Input;

class Search extends \Adept\Document\HTML\Elements\Div
{
  // Form element attributes
  public string $name = 'search';
  public bool   $required = false;
  public bool
    $autocomplete;
  public bool   $autofocus;
  public bool   $disabled;
  public string $form;
  public string $value = '';
  // Input
  // Element Specific Attributes
  public string $accept;
  public string $alt;
  public bool   $checked;
  public string $dirname;
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
  public string $pattern;
  public string $placeholder;
  public bool   $readonly;
  public int    $size;
  public string $src;
  public string $step;
  public string $type;
  public string $usemap;
  public int    $width;
  // Custom param
  //public bool   $filter = true;
  //public bool   $allowEmpty = true;
  //public string $emptyDisplay = '';
  //public array  $optionShowOn = [];
  //public array  $optionHideOn = [];
  // Duplicate, here for reference only
  //public bool   $required; 
  //public string $label = '';


  public function getBuffer(): string
  {
    $this->css[]   = 'textbox search';

    $attr = [];

    $attrs = [
      'accept',
      'alt',
      'autocomplete',
      'autofocus',
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

    for ($i = 0; $i < count($attrs); $i++) {
      $key = $attrs[$i];

      if (!empty($this->$key)) {
        $attr[$key] = $this->$key;
        unset($this->$key);
      }
    }

    //$attr['css'][] = 'sea';

    $text  = new Input($attr);
    $button = new Button();
    $this->children = [
      $text,
      $button
    ];

    return parent::getBuffer();
  }
}
