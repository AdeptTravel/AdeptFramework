<?php

namespace Adept\Abstract\Data;

use Adept\Application;

defined('_ADEPT_INIT') or die('No Access');

abstract class Table
{
  /**
   * Filter columns that are empty
   *
   * @var array
   */
  protected array $empty = [];

  /**
   * Filter columns that are not empty
   *
   * @var array
   */
  protected array $notEmpty = [];

  /**
   * Ignore these fields while filtering
   *
   * @var array
   */
  protected array $ignore = [];

  /**
   * Name of the associated database table
   *
   * @var string
   */
  protected string $table;

  /**
   * table columns to filter with the LIKE % SQL command
   *
   * @var array
   */
  protected array $like = [];

  /**
   * Specifies tables to join, as $table => $column
   *
   * @var array
   */
  protected array $joinInner = [];

  /**
   * Specifies tables to join, as $table => $column
   *
   * @var array
   */
  protected array $joinLeft = [];

  /**
   * The last filter used
   *
   * @var array
   */
  protected array $filter = [];

  /**
   * Fields to use for sorting when getting recursive data
   *
   * @var array
   */
  protected array $recursiveSort = [];

  /**
   * The last dataset returned
   *
   * @var array
   */
  protected array $data;

  /**
   * Sort by column
   *
   * @var string
   */
  public string $sort = '';

  /**
   * Sort Direction
   *
   * @var string
   */
  public string $dir = 'ASC';

  /**
   * Undocumented variable
   *
   * @var int
   */
  public int $id;

  /**
   * Status - Active, Inactive, Block
   *
   * @var string
   */
  public string $status;

  /**
   * The created date as a MySQL string
   *
   * @var string
   */
  public string $createdOn;

  /**
   * The last updated date as a MySQL string
   *
   * @var string
   */
  public string $updatedOn;

  public function getData(bool $recursive = false): array
  {
    $filter = $this->getFilterData();

    $data = [];

    if ($filter == $this->filter && isset($this->data)) {
      $data = $this->data;
    } else {
      if (!$this->cacheLoad($filter)) {
        $filter = $this->getFilterData();

        if ($recursive) {
          $query = $this->getRecursiveQuery();
        } else {
          $query = $this->getQuery();
        }

        $query .= $this->getFilterQuery($filter, $recursive);
        $query .= $this->getSortQuery($recursive);

        //if (get_class($this) == 'Adept\Data\Table\Menu\Item') {
        //  die($query);
        //}

        $db = \Adept\Application::getInstance()->db;

        $data = $db->getObjects($query, $filter);

        if ($data === false) {
          $this->data = [];
        } else {
          $this->data = $data;
          $this->filter = $filter;
          $this->cacheSave($filter);
        }
      }
    }

    return $this->data;
  }

  public function setFilter(array $filter)
  {
    $reflect    = new \ReflectionClass($this);
    $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

    for ($i = 0; $i < count($properties); $i++) {
      $key  = $properties[$i]->name;
      $type = $properties[$i]->getType();

      if ($key == 'id' && isset($this->id) && $this->id == 0) {
        continue;
      }

      if (in_array($key, ['id'])) {
        continue;
      }

      if (array_key_exists($key, $filter)) {
        $this->$key = $filter[$key];
      }
    }
  }

  public function toggle(int $id, string $col, bool $val): bool
  {
    $item = $this->getItem($id);
    $item->$col = $val;
    return $item->save();
  }

  protected function getQuery(): string
  {
    $query  = $this->getSelectQuery();
    $query .= ' FROM ' . $this->table;
    $query .= $this->getJoinQuery();

    return $query;
  }

  protected function getRecursiveQuery(): string
  {

    $query  = 'WITH RECURSIVE cte AS (';

    // Root level query 
    $query .= 'SELECT';
    $query .= ' `' . $this->table . '`.*,';

    for ($i = 0; $i < count($this->recursiveSort); $i++) {
      $key = $this->recursiveSort[$i];
      $query .= ' CAST(`' . $this->table . '`.`' . $key . '` AS CHAR(200)) AS `' . $key . 'Path`,';
    }
    /*
    if (property_exists($this, 'title')) {
      $query .= ' CAST(`' . $this->table . '`.`title` AS CHAR(200)) AS `titlePath`,';
    }

    if (property_exists($this, 'displayOrder')) {
      $query .= ' CAST(`' . $this->table . '`.`displayOrder` AS CHAR(200)) AS `orderPath`,';
    }
    */

    $query .= ' 0 AS `level`';

    $query .= ' FROM `' . $this->table . '`';
    $query .= ' WHERE `MenuItem`.`parentId` IS NULL';

    $query .= ' UNION ALL';

    // Recursive case: Get child items

    $query .= ' SELECT';
    $query .= ' child.*,';

    for ($i = 0; $i < count($this->recursiveSort); $i++) {
      $key = $this->recursiveSort[$i];
      $query .= " CONCAT(cte.`' . $key . 'Path`, '/ ', child.`' . $key . '`) AS `' . $key . 'Path`,";
    }

    /*
    if (property_exists($this, 'title')) {
      $query .= $this->getRecursiveQuerySort('title') . ',';
      //$query .= " CONCAT(cte.`titlePath`, '/ ', child.`title`) AS `titlePath`,";
    }

    if (property_exists($this, 'displayOrder')) {
      $query .= $this->getRecursiveQuerySort('displayOrder', 'order') . ',';
      //$query .= " CONCAT(cte.`displayOrder`, '/ ', child.`id`) AS `orderPath`,";
    }
    */

    $query .= ' cte.`level` + 1 AS `level`';
    $query .= ' FROM `' . $this->table . '` child';
    $query .= ' INNER JOIN cte ON child.`parentId` = cte.`id`';

    $query .= ' ) ';

    $query .= ' ' . $this->getSelectQuery(true);

    $query .= ' FROM cte ';

    $query .= $this->getJoinQuery(true);

    return $query;
  }

  protected function getSelectQuery(bool $recursive = false): string
  {
    $table = ($recursive) ? 'cte' : $this->table;

    $db   = Application::getInstance()->db;
    $cols = $db->getColumns($this->table);

    if ($recursive) {
      for ($i = 0; $i < count($this->recursiveSort); $i++) {
        $key = $this->recursiveSort[$i];
        $cols[] = $key . 'Path';
      }
    }
    /*
    if ($recursive) {
      if (property_exists($this, 'title')) {
        $cols[] = 'titlePath';
      }

      if (property_exists($this, 'displayOrder')) {
        $cols[] = 'orderPath';
      }

      $cols[] = 'level';
    }
    */
    for ($i = 0; $i < count($cols); $i++) {
      $cols[$i] = "`$table`.`$cols[$i]`";
    }

    $joins = array_merge($this->joinInner, $this->joinLeft);

    if (!empty($joins)) {

      foreach ($joins as $table => $col) {
        $tmp = $db->getColumns($table);

        for ($i = 0; $i < count($tmp); $i++) {
          $tmp[$i] = "`$table`.`$tmp[$i]`";
        }

        $cols = array_merge($cols, $tmp);
      }
    }

    $query = 'SELECT ';

    for ($i = 0; $i < count($cols); $i++) {
      $parts = explode('.', str_replace('`', '', $cols[$i]));

      if ($parts[0] == $this->table || $parts[0] == 'cte') {
        $as = $parts[1];
      } else {
        $as = strtolower($parts[0]) . ucfirst($parts[1]);
      }

      if ($i > 0) {
        $query .= ', ';
      }

      $query .= $cols[$i] . " AS  `$as`";
    }

    if ($recursive) {
      $query = str_replace('cte.', '', $query);
    } else {
      $query = str_replace($this->table . '.', '', $query);
    }

    return $query;
  }

  protected function getJoinQuery(bool $recursive = false)
  {
    $table = ($recursive) ? 'cte' : $this->table;

    $query = '';

    if (!empty($this->joinInner)) {
      foreach ($this->joinInner as $t => $c) {
        $query .= " INNER JOIN $t ON $table.$c = $t.id";
      }
    }

    if (!empty($this->joinLeft)) {
      foreach ($this->joinLeft as $t => $c) {
        $query .= " LEFT JOIN $t ON $table.$c = $t.id";
      }
    }

    return $query;
  }

  protected function getFilterQuery(array $filter = [], bool $recursive = false): string
  {
    $table = ($recursive) ? 'cte' : '`' . $this->table . '`';
    $query = '';

    if (!empty($filter)) {

      foreach ($filter as $key => $val) {
        $query .=  ((empty($query)) ? ' WHERE ' : ' AND ');
        $query .= ' ' . $table .  '.`' . $key . '`';

        if (in_array($key, $this->like)) {
          $query .= ' LIKE ';
        } else {
          $query .= '=';
        }

        $query .= ':' . $key;
      }
    }

    if (!empty($this->empty)) {
      for ($i = 0; $i < count($this->empty); $i++) {
        $query .= ((strpos($query, ' WHERE ') === false) ? ' WHERE ' : ' AND ');
        $query .= $this->empty[$i] . "=''";
      }
    }

    if (!empty($this->notEmpty)) {
      for ($i = 0; $i < count($this->notEmpty); $i++) {
        $query .= ((strpos($query, ' WHERE ') === false) ? ' WHERE ' : ' AND ');
        $query .= $this->notEmpty[$i] . " <> ''";
      }
    }

    return $query;
  }

  protected function getFilterData(): array
  {
    $filter     = [];
    $reflect    = new \ReflectionClass($this);
    $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

    for ($i = 0; $i < count($properties); $i++) {
      $key  = $properties[$i]->name;
      $type = $properties[$i]->getType();

      if ($key == 'id' && isset($this->id) && $this->id == 0) {
        continue;
      }

      if (in_array($key, ['id', 'sort', 'dir'])) {
        continue;
      }

      if (in_array($key, $this->ignore)) {
        continue;
      }

      if (isset($this->$key)) {

        switch ($type) {
          case 'string':
            if (!empty($this->$key)) {
              if (in_array($key, $this->like)) {
                $filter[$key] = '%' . $this->$key . '%';
              } else {
                $filter[$key] = $this->$key;
              }
            }

            break;

          case 'int':
          case 'bool':
            $filter[$key] = (int)$this->$key;
            break;

          case 'DateTime':
            if ($this->$key->format('Y') != '-0001') {
              $filter[$key] = $this->$key->format('Y-m-d H:i:s');
            } else {
              $filter[$key] = '0000-00-00 00:00:00';
            }

            break;

          default:

            if (strpos($type, "Adept\\Data\\") !== false) {
              $filter[$key] = $this->$key->id;
            }

            break;
        }
      }
    }

    return $filter;
  }

  protected function getSortQuery(bool $recursive = false)
  {
    $query = '';
    $sort = $this->sort;
    $dir = strtoupper($this->dir);

    if (empty($dir) || ($dir != 'ASC' && $dir != 'DESC')) {
      $dir = 'ASC';
    }

    if (empty($sort) && property_exists($this, 'displayOrder')) {
      $sort = 'displayOrder';
    }

    if ($sort == 'title') {
      $sort = 'title';
    }

    if ($recursive && in_array($sort, $this->recursiveSort)) {
      $sort = $sort . 'Path';
    }

    if (!empty($sort)) {
      $query = ' ORDER BY `' . $sort . '` ' . $dir;
    }

    return $query;
  }

  protected function getRecursiveSortField(string $field) {}

  abstract protected function getItem(int $id): \Adept\Abstract\Data\Item;

  /**
   * Load the cache data
   *
   * @param  int|string $val
   *
   * @return bool
   */

  protected function cacheLoad(array $filter): bool
  {
    $status = false;

    if (Application::getInstance()->conf->system->cache) {
      $path   = FS_SITE_CACHE . str_replace("\\", '/', get_class($this)) . '/';

      $file   = $this->cacheHash($filter) . '.php';

      if (file_exists($path . $file)) {
        // Get the serialized cache data
        $cache = file_get_contents($path . $file);
        // Remove security block
        $cache = substr($cache, 15);
        // Unseralize the data
        $this->data  = unserialize($cache);

        $status = true;
      }
    }

    return $status;
  }

  /**
   * Save the cache file
   *
   * @param  string $col - The columns to use as an index for the cache file
   *
   * @return void
   */
  protected function cacheSave(array $filter)
  {
    if (Application::getInstance()->conf->system->cache) {
      $path   = FS_SITE_CACHE . str_replace("\\", '/', get_class($this)) . '/';
      $file   = $this->cacheHash($filter) . '.php';

      $serialized = serialize($this->data);
      $cache = '<?php die(); ?>' . $serialized;

      if (!file_exists($path)) {
        mkdir($path, 0774, true);
      }

      if (!file_exists($path . $file)) {
        file_put_contents($path . $file, $cache);
      }
    }
  }

  protected function cacheHash(array $filter): string
  {
    if (!empty($this->dir)) {
      $filter['dir'] = $this->dir;
    }

    if (!empty($this->sort)) {
      $filter['sort'] = $this->sort;
    }

    $json = json_encode($filter);
    return hash('md5', $json);
  }
}
