<?php

namespace Adept\Document\HTML\Elements\Form\Row\DropDown\Location;

defined('_ADEPT_INIT') or die();

use Adept\Application\Database;

class Currency extends \Adept\Document\HTML\Elements\Form\Row\DropDown
{
  /**
   * Undocumented function
   *
   * @param  Database $db
   * @param  array                       $attr
   */
  public function __construct(array $attr = [])
  {
    // Placing this before the parent::__construct saves us having to check if
    // empty before populating
    $this->label = 'Currency';

    parent::__construct($attr, []);

    $table = new \Adept\Data\Table\Location\Currency();
    $data  = $table->getData();

    for ($i = 0; $i < count($data); $i++) {
      $this->values[$data[$i]->id] = $data[$i]->currency . ' (' . $data[$i]->code . ')';
    }
  }
}
