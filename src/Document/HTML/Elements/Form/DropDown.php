<?php

namespace Adept\Document\HTML\Elements\Form;

defined('_ADEPT_INIT') or die();

use Adept\Application;
use \Adept\Document\HTML\Elements\A;
use \Adept\Document\HTML\Elements\Button;
use \Adept\Document\HTML\Elements\Div;
use \Adept\Document\HTML\Elements\Input;
use \Adept\Document\HTML\Elements\Input\Hidden;
use \Adept\Document\HTML\Elements\Li;
use \Adept\Document\HTML\Elements\Span;
use \Adept\Document\HTML\Elements\Ul;

class DropDown extends \Adept\Document\HTML\Elements\Div
{
  // Form element attributes
  public string $name;
  public bool   $required = false;
  public bool
    $autocomplete;
  public bool   $autofocus;
  public bool   $disabled;
  public string $form;
  public int|string $value = '';
  // Select specific attributes
  public array  $values = [];
  // Input specific attributes
  public string $accept;
  public string $alt;
  public string $dirname;
  public string $formaction;
  public string $formenctype;
  public string $formmethod;
  public bool   $formnovalidate;
  public string $formtarget;
  public int    $height;
  public string $max;
  public int    $maxlength;
  public string $min;
  public int    $minlength;
  public bool   $multiple;
  public string $pattern;
  public string $placeholder;
  public bool   $readonly = true;
  public int    $size;
  public string $src;
  public string $step;
  public string $type;
  public string $usemap;
  public int    $width;
  // Custom param
  public bool   $filter = true;
  public bool   $allowEmpty = true;
  public string $emptyDisplay = '';
  public array  $optionShowOn = [];
  public array  $optionHideOn = [];
  // Duplicate, here for reference only
  //public bool   $required; 
  //public string $label = '';

  public function getBuffer(): string
  {
    $app = Application::getInstance();
    $app->html->head->javascript->addAsset('Core/Form/DropDown');

    if (!empty($this->conditions)) {
      $app->html->head->javascript->addAsset('Core/Form/Conditional');
    }

    $this->css[]   = 'dropdown';

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
      'max',
      'maxlength',
      'min',
      'minlength',
      'multiple',
      //'name',
      'pattern',
      'placeholder',
      'readonly',
      'required',
      'size',
      'src',
      'step',
      'type',
      'usemap',
      //'value',
      'width'
    ];

    $hidden = new Hidden();

    foreach (['name', 'value'] as $k) {
      $hidden->$k = $this->$k;
      unset($this->$k);
    }

    for ($i = 0; $i < count($attrs); $i++) {
      $key = $attrs[$i];

      if (!empty($this->$key)) {
        $attr[$key] = $this->$key;
        unset($this->$key);
      }
    }

    $attr['css'][] = 'display';

    $display  = new Input($attr);
    $dropdown = new Div();
    $filter   = new Input(['css' => ['filter']]);
    $list     = new Div(['css' => ['list']]);

    if (!empty($this->emptyDisplay)) {
      $list->children[] = new Button([
        'data' => ['value' => ''],
        'text' => $this->emptyDisplay,
      ]);
    }

    foreach ($this->values as $k => $v) {

      if (isset($hidden->value) && $hidden->value == $k) {
        $display->value = $v;
      }

      $option = new Button([
        'data' => ['value' => $k],
        'text' => $v,
      ]);

      if (array_key_exists($k, $this->conditions)) {

        for ($i = 0; $i < count($this->conditions[$k]); $i++) {
          $option->showOn[] = $this->conditions[$k][$i];
        }
      }

      $list->children[] = $option;
    }

    if ($this->filter && count($this->values) > 4) {
      $dropdown->children[] = $filter;
    }

    $dropdown->children[] = $list;

    $this->children = [
      $hidden,
      $display,
      $dropdown
    ];

    return parent::getBuffer();
  }
}
