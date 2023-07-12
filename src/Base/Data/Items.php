<?php

namespace AdeptCMS\Base\Data;

defined('_ADEPT_INIT') or die('No Access');

abstract class Items
{
  /**
   * Undocumented variable
   *
   * @var \AdeptCMS\Application\Database
   */
  protected $db;

  /**
   * Undocumented variable
   *
   * @var array
   */
  public $items = [];

  /**
   * Table of the items
   *
   * @var string
   */
  protected $table = '';

  /**
   * Connection: Parent ID
   *
   * @var integer
   */
  protected $id = 0;

  /**
   * Connection: Connecting table
   *
   * @var string
   */
  protected $connect = '';

  /**
   * Connection: Column name for Parent ID
   *
   * @var string
   */
  protected $parent = '';

  /**
   * Connection: Column name for Child ID
   *
   * @var string
   */
  protected $child = '';

  /**
   * Undocumented variable
   *
   * @var array
   */
  //protected $filter = [];

  /**
   * Undocumented variable
   *
   * @var string
   */
  protected $namespace = '';

  public function __construct(
    \AdeptCMS\Application\Database &$db,
    int|string $id = 0,
    array $filter = []
  ) {
    $this->db = $db;
    $this->id = $id;
    $this->filter = $filter;
    $this->namespace = str_replace("Data\\Items", "Data\\Item", get_class($this));

    if (empty($this->table)) {
      $parts = explode("\\", $this->namespace);
      $this->table = strtolower($parts[3]);
    }

    $this->load();
  }

  public function load(): bool
  {
    $params = [];
    $query  = 'SELECT a.* FROM `' . $this->table . '` AS a';

    if (
      !empty($this->connect)
      && !empty($this->parent)
      && !empty($this->child)
      && $this->id > 0
    ) {

      $query .= ' INNER JOIN `' . $this->connect . '` AS b';
      $query .= ' ON a.id = b.`' . $this->child . '`';
      $query .= ' WHERE b.`' . $this->parent . '` = ?';

      $params[] = $this->id;
    }

    $parts = explode("\\", get_class($this));

    if (count($parts) == 4) {
      // Example: AdeptCMS\Data\Items\Route
      $parts[2] = 'Item';
    } else if (count($parts) == 5) {
      // Example: Component\Trip\Data\Items\Trip
      $parts[4] = 'Item';
    }

    $namespace = implode("\\", $parts);
    $vars = get_class_vars($namespace);

    if (
      isset($this->filter['sort']) &&
      array_key_exists($this->filter['sort'], $vars)
    ) {

      $query .= ' ORDER BY `' . $this->filter['sort'] . '`';

      if (
        isset($this->filter['order'])
        && (strtolower($this->filter['order']) == 'asc'
          || strtolower($this->filter['order'] == 'desc')
        )
      ) {
        $query .= ' ' . ((strtolower($this->filter['order'] == 'desc')) ? 'DESC' : 'ASC');
      }
    }

    if (($objs = $this->db->getObjects($query, $params)) !== false) {
      $status = true;

      for ($i = 0; $i < count($objs); $i++) {
        $this->items[] = new $this->namespace($this->db, 0, $objs[$i]);
      }
    }

    return $status;
  }

  public function save(): bool
  {
    $status = true;

    for ($i = 0; $i < count($this->items); $i++) {
      if (!$this->items[$i]->save()) {
        $status = false;
      }
    }

    return $status;
  }

  protected function saveCache()
  {
    /*
    if (!empty($this->cache)) {
      $path = FS_CACHE . str_replace("\\", '/', get_class($this)) . '/';
      $file = $path . $this->cache;

      if (!file_exists($path)) {
        mkdir($path, 0755, true);
      }

      file_put_contents($file, json_encode($this->items));
    }
    */
  }

  protected function loadCache(): bool
  {
    /*
    $status = false;

    if (!empty($this->cache)) {
      $path = FS_CACHE . str_replace("\\", '/', get_class($this)) . '/';
      $file = $path . $this->cache;

      if (file_exists($file)) {
        if ($data = json_decode(file_get_contents($file))) {

          foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->index)) {
              $ns = $this->index[$key];
              $this->$key = new $ns($value);
            } else {
              $this->$key = $value;
            }
          }

          $status = true;
        }
      }
    }

    return $status;
    */

    return true;
  }
}
