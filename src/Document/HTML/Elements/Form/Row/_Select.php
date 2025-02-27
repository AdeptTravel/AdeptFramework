<?php

namespace Adept\Document\HTML\Elements\Form\Row;

defined('_ADEPT_INIT') or die();

use \Adept\Document\HTML\Elements\Span;
use \Adept\Document\HTML\Elements\Select as Sel;

class Select extends \Adept\Document\HTML\Elements\Form\Row
{
  public string $label;
  public bool $required = false;

  // Select Specific Attributes
  public bool
    $autocomplete;
  public bool   $autofocus;
  public bool   $disabled;
  public string $form;
  public bool   $multiple;
  public string $name;
  public int    $size;
  public string $value = '';
  public array  $values = [];

  // Custom param
  public bool   $allowEmpty = true;

  // Duplicate, here for reference only
  //public bool   $required; 
  //public string $label = '';

  public function __construct(array $attr = [], array $children = [], bool $validate = false, bool $status = true)
  {
    parent::__construct($attr, $children);

    $this->css[] = 'select';

    $select = new Sel([]);

    $attr = [
      'autocomplete',
      'autofocus',
      'disabled',
      'form',
      'label',
      'multiple',
      'name',
      'required',
      'size',
      'value',
      'values'
    ];

    for ($i = 0; $i < count($attr); $i++) {

      $key = $attr[$i];

      if ($key == 'label' && $this->allowEmpty == true) {
        $select->$key = '-- ' . $this->$key . ' --';
        continue;
      }

      if (!empty($this->$key) && $key != 'label') {



        $select->$key = $this->$key;

        if ($key != 'required') {
          unset($this->$key);
        }
      }
    }

    $this->children[] = $select;
  }
}
