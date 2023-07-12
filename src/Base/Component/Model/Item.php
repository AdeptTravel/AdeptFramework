<?php

namespace AdeptCMS\Base\Component\Model;

defined('_ADEPT_INIT') or die('No Access');

abstract class Item extends \AdeptCMS\Base\Component\Model
{
  /**
   * The data item
   *
   * @var object
   */
  public $item;

  /**
   * Save the data
   *
   * @return bool
   */
  abstract protected function save(): bool;

  public function getJSON(): string
  {
    return json_encode($this->item);
  }
}
