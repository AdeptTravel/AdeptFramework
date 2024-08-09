<?php

namespace Adept\Document\HTML\Elements\Form\Row;

defined('_ADEPT_INIT') or die();

use \Adept\Document\HTML\Elements as Elements;
use \Adept\Document\HTML\Elements\Span;


class TextArea extends \Adept\Document\HTML\Elements\Form\Row
{

  public string $label;

  // Textarea Specific Attributes
  public string $autocomplete;
  public bool   $autofocus;
  public int    $cols;
  public string $dirname;
  public bool   $disabled;
  public string $form;
  public int    $maxlength;
  public int    $minlength;
  public string $name;
  public string $placeholder;
  public bool   $readonly;
  public bool   $required = false;
  public int    $rows;
  public string $value;
  public string $wrap;

  public function __construct(array $attr = [], array $children = [], bool $validate = false, bool $status = true)
  {
    parent::__construct($attr, $children);

    $this->css[] = 'textarea';


    $attr = [
      'autocomplete',
      'autofocus',
      'cols',
      'dirname',
      'disabled',
      'form',
      'maxlength',
      'minlength',
      'name',
      'placeholder',
      'readonly',
      'required',
      'rows',
      'value',
      'wrap'
    ];

    //$input = new Elements\Input([]);
    $textarea = new \Adept\Document\HTML\Elements\TextArea([]);

    for ($i = 0; $i < count($attr); $i++) {
      $key = $attr[$i];

      if (!empty($this->$key)) {

        if ($key == 'value') {
          $textarea->text = $this->$key;
          unset($this->$key);
          continue;
        }

        if ($key == 'label' && empty($this->placeholder)) {
          $textarea->placeholder = $this->label;
        }

        $textarea->$key = $this->$key;

        if ($key != 'required') {
          unset($this->$key);
        }
      }
    }

    $this->children[] = $textarea;
  }
}
