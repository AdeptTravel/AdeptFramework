<?php

namespace Adept\Document\HTML\Elements\Form;

defined('_ADEPT_INIT') or die();

use \Adept\Document\HTML\Elements\Input;

class Name extends \Adept\Abstract\Document\HTML\Element
{
  public string $name = '';
  public bool $required = false;

  public function __construct(array $attr = [])
  {
    parent::__construct($attr);

    if (!empty($this->name)) {
      $this->name .= '_';
    }

    $this->children[] = new Row([
      'required' => $this->required,
      'css' => $this->css
    ], [
      new Input([
        'name' => $this->name . 'firstname',
        'placeholder' => 'First Name',
        'required' => $this->required,
        'autocomplete' => 'given-name',
        'value' => ((array_key_exists('firstname', $this->data)) ? $this->data['firstname'] : ''),
        'disabled' => (in_array('edit', $this->css))
      ])
    ]);

    $this->children[] = new Row([
      'css' => $this->css
    ], [
      new Input([
        'name' => $this->name . 'middlename',
        'placeholder' => 'Middle Name',
        'autocomplete' => 'additional-name',
        'value' => ((array_key_exists('middlename', $this->data)) ? $this->data['middlename'] : ''),
        'disabled' => (in_array('edit', $this->css))
      ])
    ]);

    $this->children[] = new Row([
      'required' => $this->required,
      'css' => $this->css
    ], [
      new Input([
        'name' => $this->name . 'lastname',
        'placeholder' => 'Last Name',
        'required' => $this->required,
        'autocomplete' => 'family-name',
        'value' => ((array_key_exists('lastname', $this->data)) ? $this->data['lastname'] : ''),
        'disabled' => (in_array('edit', $this->css))
      ])
    ]);
  }
}
