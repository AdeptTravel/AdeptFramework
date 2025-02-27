<?php

namespace Adept\Document\HTML\Elements\Form\Row\DropDown\Location;

defined('_ADEPT_INIT') or die();

use Adept\Application;


class State extends \Adept\Document\HTML\Elements\Form\Row\DropDown
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
    $this->label = 'State';

    parent::__construct($attr, []);



    $data = Application::getInstance()->db->getObjects(
      "SELECT `id`, `state` FROM `LocationArea` GROUP BY `state` ORDER BY `state` ASC",
      []
    );

    for ($i = 0; $i < count($data); $i++) {
      $this->values[$data[$i]->id] = $data[$i]->state;
    }
  }
}
