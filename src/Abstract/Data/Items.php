<?php

namespace Adept\Abstract\Data;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Application\Database;
use \Adept\Application\Session\Request\Data\Post;

abstract class Items
{
  /**
   * Cache file
   *
   * @var string
   */
  protected string $cache;

  /**
   * The database object
   *
   * @var \Adept\Application\Database
   */
  protected Database $db;

  /**
   * The name of the data item, used for error messages
   *
   * @var string
   */
  protected string $name = 'item';

  /**
   * Sort by column
   *
   * @var string
   */
  protected string $sort = '';

  /**
   * Sort Direction
   *
   * @var string
   */
  protected string $dir = 'ASC';

  /**
   * Name of the associated database table
   *
   * @var string
   */
  protected string $table = '';

  /**
   * Undocumented variable
   *
   * @var array|int
   */
  public array|int $id;

  /**
   * Undocumented variable
   *
   * @var array
   */
  public array $items;

  /**
   * Undocumented function
   *
   * @param  \Adept\Application\Database $db
   * @param  object|null|null            $obj
   */
  public function __construct(Database &$db)
  {
    $this->db = $db;

    $table = strtolower(get_class($this));
    $parts = explode('\\', $table);
    $offset = ($parts[0] == 'adept') ? 3 : 5;
    $parts = array_splice($parts, $offset);

    if (empty($this->table)) {
      $this->table = implode('_', $parts);
    }
  }

  public function load(): bool
  {
    //$status = $this->loadCache();
    $status = false;

    $query = 'SELECT * FROM `' . $this->table . '`';
    $params = [];

    $reflect = new \ReflectionClass($this);
    $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

    for ($i = 0; $i < count($properties); $i++) {
      $key = $properties[$i]->name;
      $type = $properties[$i]->getType();

      if ($key == 'id' && $this->$key == 0) {
        continue;
      }

      if (!empty($this->$key)) {

        $query .=  (($i == 0) ? ' WHERE ' : ' AND ');

        switch ($type) {
          case 'string':
            $query .= " `$key` = ?";
            $params[] = (string)$this->$key;
            break;

          case 'int':
            $query .= " `$key` = ?";
            $params[] = (int)$this->$key;
            break;

          case 'bool':
            $query .= " `$key` = ?";
            $params[] = (int)$this->$key;
            break;

          case 'array':
            for ($a = 0; $a < count($this->$key); $a++) {
              $query .= " `$key` = ?";
              $params[] = (int)$this->$key;

              if ($a < count($this->$key) - 1) {
                $query .= " AND";
              }
            }

            break;

          case 'DateTime':
            $query .= " `$key` = ?";

            if ($this->$key->format('Y') != '-0001') {
              $params[] = $this->$key->format('Y-m-d H:i:s');
            }

            break;

          default:

            if (strpos($type, "Adept\\Data\\") !== false) {
              $query .= " `$key` = ?";
              $params[] = (int)$this->$key->id;
              $data[$key] = $this->$key->id;
            }

            break;
        }
      }

      if (!empty($this->sort)) {
        $query .= ' ORDER BY `' . $this->sort . '` ' . $this->dir;
      }

      $items = $this->db->getObjects($query, $params);

      if ($items !== false) {
        $this->items = $items;
        $status = true;
      }

      return $status;
    }
  }

  public function inItems(string $key, string $val): bool
  {

    for ($i = 0; $i < count($this->items); $i++) {

      if ($this->items[$i]->$key == $val) {
        return true;
      }
    }

    return false;
  }
}
