<?php

namespace Adept\Abstract\Component\JSON;

use Adept\Application;

defined('_ADEPT_INIT') or die('No Access');

abstract class Table extends \Adept\Abstract\Component\JSON
{

  protected bool $recursive = false;
  /**
   * Init
   */
  public function __construct()
  {
    $table = $this->getTable();
    $this->data = $table->getData($this->recursive);
  }

  abstract protected function getTable(): \Adept\Abstract\Data\Table;
}
