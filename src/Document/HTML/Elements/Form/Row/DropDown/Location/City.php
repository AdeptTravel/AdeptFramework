<?php

namespace Adept\Document\HTML\Elements\Form\Row\DropDown\Location;

defined('_ADEPT_INIT') or die();

use Adept\Application;


class City extends \Adept\Document\HTML\Elements\Form\Row\DropDown
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
    $this->label = 'City';

    parent::__construct($attr, []);

    $data = Application::getInstance()->db->getObjects(
      "SELECT id, city FROM LocationArea GROUP BY city ORDER BY city ASC",
      []
    );

    for ($i = 0; $i < count($data); $i++) {
      $this->values[$data[$i]->id] = $data[$i]->city;
    }
  }
}
