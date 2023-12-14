<?php

namespace Adept\Document\HTML\Elements\Form;

defined('_ADEPT_INIT') or die();

use \Adept\Document\HTML\Elements\Input;
use \Adept\Document\HTML\Elements\Input\Hidden;
use \Adept\Document\HTML\Elements\Select\Country;

class Address extends \Adept\Abstract\Document\HTML\Elements
{
  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $name = '';

  /**
   * Undocumented variable
   *
   * @var bool
   */
  public bool $required = false;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $country = 'US';

  /**
   * Undocumented function
   *
   * @param  array $attr
   */
  public function __construct(array $attr = [])
  {
    parent::__construct($attr);

    if (!empty($this->name)) {
      $this->name .= '_';
    }

    $this->children[] = new Row([
      'required' => $this->required
    ], [
      new Input([
        'name' => $this->name . 'street0',
        'placeholder' => 'Street',
        'required' => $this->required,
        'value' => ((array_key_exists('street0', $this->data)) ? $this->data['street0'] : ''),
        'css' => ['address', 'street0'],
        'disabled' => (in_array('edit', $this->css))
      ])
    ]);

    $this->children[] = new Row([], [
      new Input([
        'name' => $this->name . 'street1',
        'placeholder' => 'Street',
        'value' => ((array_key_exists('street1', $this->data)) ? $this->data['street1'] : ''),
        'css' => ['address', 'street1'],
        'disabled' => (in_array('edit', $this->css))
      ])
    ]);

    $this->children[] = new Row([
      'required' => $this->required
    ], [
      new Input([
        'name' => $this->name . 'city',
        'type' => 'hidden',
        'placeholder' => 'City',
        'required' => $this->required,
        'autocomplete' => 'address-level2',
        'list' => 'datalist-city',
        'value' => ((array_key_exists('city', $this->data)) ? $this->data['city'] : ''),
        'css' => ['address', 'city'],
        'disabled' => (in_array('edit', $this->css))
      ])
    ]);

    $this->children[] = new Row([
      'required' => $this->required
    ], [
      new Input([
        'name' => $this->name . 'county',
        'value' => ((array_key_exists('county', $this->data)) ? $this->data['county'] : ''),
        'placeholder' => 'County',
        'list' => 'datalist-county',
        'value' => ((array_key_exists('county', $this->data)) ? $this->data['county'] : ''),
        'css' => ['address', 'county'],
        'disabled' => (in_array('edit', $this->css))
      ])
    ]);

    $this->children[] = new Row([
      'required' => $this->required,
      'css' => $this->css
    ], [
      new Input([
        'name' => $this->name . 'state',
        'type' => 'hidden',
        'placeholder' => 'State',
        'required' => $this->required,
        'autocomplete' => 'address-level1',
        'list' =>
        'datalist-state',
        'value' => ((array_key_exists('state', $this->data)) ? $this->data['state'] : ''),
        'css' => ['address', 'state'],
        'disabled' => (in_array('edit', $this->css))
      ])
    ]);

    $this->children[] = new Row([
      'required' => $this->required
    ], [
      new Input([
        'name' => 'postalcode',
        'placeholder' => 'Postal Code',
        'required' => $this->required,
        'value' => ((array_key_exists('postalcode', $this->data)) ? $this->data['postalcode'] : ''),
        'autocomplete' =>
        'postal-code',
        'disabled' => (in_array('edit', $this->css))
      ])
    ]);

    if (empty($this->country)) {
      $this->children[] = new Row([
        'required' => $this->required,
        'css' => ['select']
      ], [
        new Country([
          'app' => $this->app,
          'name' => 'postalcode',
          'placeholder' => 'Postal Code',
          'required' => $this->required,
          'autocomplete' => 'postal-code'
        ])
      ]);
    } else {
      $this->children[] = new Hidden([
        'name' => 'country',
        'value' => $this->country
      ]);
    }
  }
}
