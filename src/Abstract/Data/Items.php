<?php

namespace Adept\Abstract\Data;

defined('_ADEPT_INIT') or die('No Access');

abstract class Items
{
  /**
   * Cache file
   *
   * @var string
   */
  protected string $cache;

  /**
   * The path to the cache folder
   *
   * @var string
   */
  protected string $cachePath = '';

  /**
   * The name of the data item, used for error messages
   *
   * @var string
   */
  protected string $errorName = 'Items';

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
   * Name of the associated database table
   *
   * @var string
   */
  protected string $table = '';

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
   * Should the data list returned be recursive?
   *
   * @var bool
   */
  public bool $recursive = false;

  /**
   * Undocumented variable
   *
   * @var int
   */
  public int $status;

  /**
   * Undocumented function
   *
   * @param  \Adept\Application\Database $db
   */
  public function __construct(bool $cache = true)
  {
    $table  = get_class($this);
    $parts  = explode('\\', $table);
    $offset = ($parts[0] == 'Adept') ? 3 : 5;
    $parts  = array_splice($parts, $offset);

    $this->errorName = end($parts);

    if (empty($this->table)) {
      $this->table = implode('', $parts);
    }

    //
    // Cache
    //
    $this->cache     = $cache;
    $this->cachePath = FS_SITE_CACHE . str_replace("\\", '/', get_class($this)) . '/';
  }

  public function getList(): array
  {
    $params = $this->getFilterData();
    $hash   = hash('md5', json_encode($params));

    if ($this->cache) {
      $list = $this->cacheLoad($hash);
    }

    if (!$this->cache || empty($list)) {
      $db = \Adept\Application::getInstance()->db;

      if ($this->recursive) {
        $query = $this->getRecursiveQuery();
      } else {
        $query = $this->getQuery();
      }

      $query .= $this->getFilterQuery($params);

      $list = $db->getObjects($query, $params);

      if ($list === false) {
        $list = [];
      } else {
        $this->cacheSave($hash, $list);
      }
    }

    return $list;
  }

  public function setFilter(array $params)
  {
    $reflect    = new \ReflectionClass($this);
    $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

    for ($i = 0; $i < count($properties); $i++) {
      $key  = $properties[$i]->name;
      $type = $properties[$i]->getType();

      if ($key == 'id' && isset($this->id) && $this->id == 0) {
        continue;
      }

      if (in_array($key, ['id', 'recursive'])) {
        continue;
      }

      if (array_key_exists($key, $params)) {
        $this->$key = $params[$key];
      }
    }
  }

  public function cachePurge()
  {
    array_map('unlink', array_filter((array) glob($this->cachePath . '*')));
  }

  protected function getQuery(): string
  {
    return 'SELECT * FROM ' . $this->table;
  }

  protected function getFilterQuery(array $filter = []): string
  {
    $query = '';

    if (!empty($filter)) {

      foreach ($filter as $key => $val) {
        $query .=  ((empty($query)) ? ' WHERE ' : ' AND ');
        $query .= ' `' . $key . '` = :' . $key;
      }
    }

    if (!empty($this->empty)) {
      for ($i = 0; $i < count($this->empty); $i++) {
        $query .= ((strpos($query, ' WHERE ') === false) ? ' WHERE ' : ' AND ');
        $query .= $this->empty[$i] . " = ''";
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

      if (in_array($key, ['id', 'sort', 'dir', 'recursive'])) {
        continue;
      }


      if (isset($this->$key)) {

        switch ($type) {
          case 'string':
            if (!empty($this->$key)) {
              $filter[$key] = $this->$key;
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

  protected function getRecursiveQuery(): string
  {
    $query  = ' WITH RECURSIVE cte AS (';
    $query .= '  SELECT';
    $query .= '    `' . $this->table . '`.*,';
    $query .= '    CAST(`' . $this->table . '`.`order` AS CHAR(200)) AS `path`,';
    $query .= '    0 AS `level`';
    $query .= '  FROM ';
    $query .= '    `' . $this->table . '`';
    $query .= '  WHERE ';
    $query .= '    `' . $this->table . '`.`parent` = 0';

    $query .= '  UNION ALL';

    $query .= '  SELECT ';
    $query .= '    t.*,';
    $query .= "    CONCAT(`cte`.`path`, ' / ', `t`.`order`) AS `path`,";
    $query .= '    `cte`.`level` + 1 AS `level`';
    $query .= '  FROM';
    $query .= '    `' . $this->table . '` t';
    $query .= '  INNER JOIN';
    $query .= '    `cte` ON `t`.`parent` = `cte`.`id`';
    $query .= '  WHERE ';
    $query .= '    `cte`.`level` < 1000';
    $query .= ' ) ';

    $query .= ' SELECT';
    $query .= '  * ';
    $query .= ' FROM ';
    $query .= '  `cte` ';

    return $query;
  }

  protected function cacheLoad(string $hash): array
  {
    $data = [];
    $file = $this->cachePath . $hash . '.php';

    if ($this->cache && file_exists($file)) {
      $file = $this->cachePath . $hash . '.php';

      $serialized = file_get_contents($file);
      $serialized = substr($serialized, 15);
      $data = unserialize($serialized);
    }

    return $data;
  }

  protected function cacheSave(string $hash, array $data)
  {
    if ($this->cache) {
      $file = $this->cachePath . $hash . '.php';
      $serialized = serialize($data);
      $cache = '<?php die(); ?>' . $serialized;

      if (!file_exists($this->cachePath)) {
        mkdir($this->cachePath, 0774, true);
      }

      file_put_contents($file, $cache);
    }
  }
}
