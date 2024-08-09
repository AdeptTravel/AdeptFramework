<?php

namespace Adept\Document\HTML\Elements\Form;

defined('_ADEPT_INIT') or die();

use \Adept\Document\HTML\Elements\Input;
use \Adept\Document\HTML\Elements\Input\Hidden;
use \Adept\Document\HTML\Elements\Select\Country;

class Phone extends \Adept\Abstract\Document\HTML\Element
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

    $this->children[] = new Row(['required' => $this->required], [new Input([
      'name' => $this->name . 'street0',
      'placeholder' => 'Street',
      'required' => $this->required,
      'css' => ['address', 'street0']
    ])]);
  }
}
