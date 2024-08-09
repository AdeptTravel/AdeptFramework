<?php

namespace Adept\Application\Database;

defined('_ADEPT_INIT') or die();

class Query
{

  protected array $select = [];
  protected array $join = [];
  protected array $where = [];
  protected array $params = [];
  protected array $order = [];

  public function select(string $col)
  {
    if (!in_array($col, $this->select)) {
      $this->select[] = $col;
    }
  }

  public function join(string $type, string $as, string $table, string $left, string $right)
  {
    $this->join .= ' ' . strtoupper($type) . ' JOIN ' . $table . ' AS ' . $as . ' ON ' . $left . ' = ' . $right;
  }

  public function where()
  {
  }
}
