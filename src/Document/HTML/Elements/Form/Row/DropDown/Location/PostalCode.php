<?php

namespace Adept\Document\HTML\Elements\Form\Row\DropDown\Location;

defined('_ADEPT_INIT') or die();

use Adept\Application;


class PostalCode extends \Adept\Document\HTML\Elements\Form\Row\DropDown
{
  /**
   * Undocumented function
   *
   * @param  array                       $attr
   */
  public function __construct(array $attr = [])
  {
    // Placing this before the parent::__construct saves us having to check if
    // empty before populating
    $this->label = 'PostalCode';

    parent::__construct($attr, []);



    $data = Application::getInstance()->db->getObjects(
      "SELECT `id`, `postalcode` FROM `LocationArea` GROUP BY `postalcode` ORDER BY `postalcode` ASC",
      []
    );

    for ($i = 0; $i < count($data); $i++) {
      $this->values[$data[$i]->id] = $data[$i]->postalcode;
    }
  }
}
