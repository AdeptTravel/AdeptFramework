<?php

namespace Adept\Document\HTML\Elements\Form;

defined('_ADEPT_INIT') or die();

use \Adept\Document\HTML\Elements\Label;
use \Adept\Document\HTML\Elements\Span;
use \Adept\Document\HTML\Elements\Select\Day;
use \Adept\Document\HTML\Elements\Select\Month;
use \Adept\Document\HTML\Elements\Select\Year;

class Date extends \Adept\Document\HTML\Elements\Div
{
  public string $label;
  public string $name;
  public bool $required = false;

  public function __construct(array $attr = [], array $children = [], bool $validate = false)
  {
    parent::__construct($attr, $children);

    if (!empty($this->name)) {
      $this->name .= '_';
    }

    if ($attr['label']) {
      unset($attr['label']);
    }

    $this->css[] = 'row';
    $this->css[] = 'date';

    if ($this->required || $validate) {
      $this->css[] = 'isValid';

      if ($this->required) {
        $this->css[] = 'required';
      }
    }

    $rowAttr['css'] = $this->css;

    if ($this->required) {
      $rowAttr['required'] = true;
    }

    $css = $this->css;

    if (in_array('isValid', $css)) {
      unset($css[array_search('isValid', $css)]);
    }

    if (in_array('required', $css)) {
      unset($css[array_search('required', $css)]);
    }

    if (in_array('row', $css)) {
      unset($css[array_search('row', $css)]);
    }

    $this->children[] = new Label(['text' => $this->label]);

    $this->children[] = new Row([
      'css' => $this->css,
      'required' => $this->required
    ], [
      new Month([
        'name' => $this->name . 'month',
        'required' => (!empty($attr['required'])) ? $attr['required'] : null,
        'css' => $css
      ])
    ], true);

    $this->children[] = new Row([
      'css' => $this->css,
      'required' => $this->required
    ], [
      new Day([
        'name' => $this->name . 'day',
        'required' => (!empty($attr['required'])) ? $attr['required'] :
          null,
        'css' => $css
      ])
    ], true);

    $this->children[] = new Row([
      'css' => $this->css,
      'required' => $this->required
    ], [
      new Year([
        'name' => $this->name . 'year',
        'required' => (!empty($attr['required'])) ? $attr['required'] : null,
        'css' => $css
      ], -110)
    ], true);
  }
}
