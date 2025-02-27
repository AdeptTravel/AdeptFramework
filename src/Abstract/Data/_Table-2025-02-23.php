<?php

namespace Adept\Abstract\Data;

use Adept\Application;

defined('_ADEPT_INIT') or die('No Access');

abstract class Table
{
  /**
   * Columns to filter that are empty.
   *
   * @var array
   */
  protected array $empty = [];

  /**
   * Columns to filter that are not empty.
   *
   * @var array
   */
  protected array $notEmpty = [];

  /**
   * Fields to ignore while applying filters.
   *
   * @var array
   */
  protected array $ignore = ['recursiveLevel'];

  /**
   * Name of the associated database table.
   *
   * @var string
   */
  protected string $table;

  /**
   * Columns to be filtered using SQL `LIKE %`.
   *
   * @var array
   */
  protected array $like = [];

  /**
   * Inner join definitions, formatted as `table => column`.
   *
   * @var array
   */
  protected array $joinInner = [];

  /**
   * Left join definitions, formatted as `table => column`.
   *
   * @var array
   */
  protected array $joinLeft = [];

  /**
   * The last applied filter.
   *
   * @var array
   */
  protected array $filter = [];

  /**
   * Columns to use for sorting recursive data.
   *
   * @var array
   */
  protected array $recursiveSort = [];

  /**
   * The last dataset returned.
   *
   * @var array
   */
  protected array $data;

  public array $columns;

  /**
   * Column to sort by.
   *
   * @var string
   */
  public string $sort = '';

  /**
   * Sorting direction, default is 'ASC' (ascending).
   *
   * @var string
   */
  public string $dir = 'ASC';

  public int $recursiveLevel;

  /**
   * Identifier for the table item.
   *
   * @var int
   */
  public int $id;

  /**
   * Status of the record, options: 'Active', 'Block', or 'Error'.
   *
   * @var string
   */
  public string $status;

  /**
   * Date when the record was created.
   *
   * @var string
   */
  public string $createdAt;

  /**
   * Date when the record was last updated.
   *
   * @var string
   */
  public string $updatedAt;

  /**
   * Get data from the table, applying filters and joins.
   * Optionally, recursive data retrieval can be enabled.
   *
   * @param bool $recursive Flag to determine if the query should be recursive.
   * @return array The data fetched based on the filters and settings.
   */
  public function getData(bool $recursive = false): array
  {
    //if (isset($this->recursiveLevel))
    // Get filter data
    $filter = $this->getFilterData();

    // If cached data matches the filter, return it.
    if ($filter == $this->filter && isset($this->data)) {
      return $this->data;
    }

    // Attempt to load cached data based on the filter.
    if (!$this->cacheLoad($filter)) {
      $filter = $this->getFilterData();

      // Get query, recursive or not.
      if ($recursive) {
        $query = $this->getRecursiveQuery();
      } else {
        $query = $this->getQuery();
      }

      // Add filtering and sorting to the query.
      $query .= $this->getFilterQuery($filter, $recursive);
      $query .= $this->getSortQuery($recursive);

      // Execute query using the database.
      $db = \Adept\Application::getInstance()->db;
      $data = $db->getObjects($query, $filter);

      // Cache and store the data if valid.
      if ($data !== false) {
        if (!empty($this->columns)) {
          $old = $data;
          $data = [];
          for ($i = 0; $i < count($old); $i++) {
            $item = [];
            for ($c = 0; $c < count($this->columns); $c++) {
              $col = $this->columns[$c];
              $item[$col] = $old[$i]->$col;
            }
            $data[] = (object)$item;
          }
        }

        $this->data = $data;
        $this->filter = $filter;
        $this->cacheSave($filter);
      } else {
        $this->data = [];
      }
    }

    return $this->data;
  }

  /**
   * Set public properties based on the provided filter array.
   *
   * @param array $filter Associative array of filters to apply to the object.
   */
  public function setFilter(array $filter)
  {
    $reflect = new \ReflectionClass($this);
    $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

    // Loop through public properties and set values from the filter.
    for ($i = 0; $i < count($properties); $i++) {
      $key = $properties[$i]->name;
      $type = $properties[$i]->getType();

      // Skip setting 'id' if it's 0 (unset).
      if ($key == 'id' && isset($this->id) && $this->id == 0) {
        continue;
      }

      // Only set properties if they exist in the filter.
      if (array_key_exists($key, $filter)) {
        $this->$key = $filter[$key];
      }
    }
  }

  /**
   * Toggle a boolean field on a specific item and save it.
   *
   * @param int $id The item ID.
   * @param string $col The column to toggle.
   * @param bool $val The value to toggle to (true/false).
   * @return bool Whether the toggle and save were successful.
   */
  public function toggle(int $id, string $col, bool $val): bool
  {
    $item = $this->getItem($id);
    $item->$col = $val;
    return $item->save();
  }

  abstract public function getItem(int $id): \Adept\Abstract\Data\Item;

  /**
   * Build the base SQL query to retrieve data from the table.
   *
   * @return string The SQL query string.
   */
  protected function getQuery(): string
  {
    $query = $this->getSelectQuery();
    $query .= ' FROM ' . $this->table;
    $query .= $this->getJoinQuery();
    return $query;
  }

  /**
   * Build the SQL query for recursive data retrieval.
   *
   * @return string The SQL recursive query string.
   */
  protected function getRecursiveQuery(): string
  {
    // Recursive query to build a hierarchical dataset.
    $query = 'WITH RECURSIVE cte AS (';

    // Base case: select root items.
    $query .= 'SELECT `' . $this->table . '`.*,';
    for ($i = 0; $i < count($this->recursiveSort); $i++) {
      $key = $this->recursiveSort[$i];
      $query .= ' CAST(`' . $this->table . '`.`' . $key . '` AS CHAR(200)) AS `' . $key . 'Path`,';
    }
    $query .= ' 0 AS `level`';
    $query .= ' FROM `' . $this->table . '`';
    $query .= ' WHERE `' . $this->table . '`.`parentId` IS NULL';

    $query .= ' UNION ALL';

    // Recursive case: select child items.
    $query .= ' SELECT child.*,';
    for ($i = 0; $i < count($this->recursiveSort); $i++) {
      $key = $this->recursiveSort[$i];
      $query .= " CONCAT(cte.`" . $key . "Path`, '/', child.`" . $key . "`) AS `" . $key . "Path`,";
    }
    $query .= ' cte.`level` + 1 AS `level`';
    $query .= ' FROM `' . $this->table . '` child';
    $query .= ' INNER JOIN cte ON child.`parentId` = cte.`id`';

    if (isset($this->recursiveLevel)) {
      $query .= ' WHERE cte.`level` < ' . (int)$this->recursiveLevel;
    }

    $query .= ' ) ';

    $query .= ' ' . $this->getSelectQuery(true);
    $query .= ' FROM cte ';
    $query .= $this->getJoinQuery(true);

    return $query;
  }

  /**
   * Build the SQL select clause for the query, optionally for recursive queries.
   *
   * @param bool $recursive Whether to build for recursive queries.
   * @return string The SQL select clause.
   */
  protected function getSelectQuery(bool $recursive = false): string
  {
    $table = ($recursive) ? 'cte' : $this->table;
    $db = Application::getInstance()->db;
    $cols = $db->getColumns($this->table);

    // Add recursive path columns if necessary.
    if ($recursive) {
      $cols[] = 'level';
      for ($i = 0; $i < count($this->recursiveSort); $i++) {
        $key = $this->recursiveSort[$i];
        $cols[] = $key . 'Path';
      }
    }

    // Build the select statement with aliases.
    for ($i = 0; $i < count($cols); $i++) {
      $cols[$i] = "`$table`.`$cols[$i]`";
    }

    $joins = array_merge($this->joinInner, $this->joinLeft);

    // Include columns from joined tables.
    if (!empty($joins)) {

      foreach ($joins as $table => $col) {
        //$tmp = (!empty($this->colums)) ? $this->columns : $db->getColumns($table);
        if ($pos = strpos($table, ' ')) {
          $table = substr($table, 0, $pos);
        }
        $tmp = $db->getColumns($table);

        for ($i = 0; $i < count($tmp); $i++) {
          $tmp[$i] = "`$table`.`$tmp[$i]`";
        }
        $cols = array_merge($cols, $tmp);
      }
    }

    // Build the full select statement.
    // Build the full select statement.
    $query = 'SELECT ';

    for ($i = 0; $i < count($cols); $i++) {
      $parts = explode('.', str_replace('`', '', $cols[$i]));

      // Assign column alias based on table and column name.
      if ($parts[0] == $this->table || $parts[0] == 'cte') {
        $as = $parts[1];
      } else {
        $as = strtolower($parts[0]) . ucfirst($parts[1]);
      }

      // Append the column to the select clause.
      if ($i > 0) {
        $query .= ', ';
      }

      $query .= $cols[$i] . " AS  `$as`";
    }

    // Remove the table name prefix from recursive queries for easier reading.
    if ($recursive) {
      $query = str_replace('cte.', '', $query);
    } else {
      $query = str_replace($this->table . '.', '', $query);
    }

    return $query;
  }

  /**
   * Generate SQL for join clauses based on inner and left joins.
   *
   * @param bool $recursive Whether the query is recursive.
   * @return string The SQL join clauses.
   */
  protected function getJoinQuery(bool $recursive = false): string
  {
    $table = ($recursive) ? 'cte' : $this->table;
    $query = '';

    // Add inner join clauses.
    if (!empty($this->joinInner)) {
      foreach ($this->joinInner as $t => $c) {
        if (strpos($t, ' ') !== false) {
          $parts = explode(' ', $t);
          $query .= " INNER JOIN `$parts[0]` `$parts[1]` ON `$table`.`$c` = `$parts[1]`.id";
        } else {
          $query .= " INNER JOIN `$t` ON `$table`.`$c` = `$t`.id";
        }
      }
    }

    // Add left join clauses.
    if (!empty($this->joinLeft)) {
      foreach ($this->joinLeft as $t => $c) {
        if (strpos($t, ' ') !== false) {
          $parts = explode(' ', $t);
          $query .= " LEFT JOIN `$parts[0]` `$parts[1]` ON `$table`.`$c` = `$parts[1]`.id";
        } else {
          $query .= " LEFT JOIN `$t` ON `$table`.`$c` = `$t`.id";
        }
      }
    }

    return $query;
  }


  /**
   * Generate SQL for filtering the data based on provided filter conditions.
   *
   * @param array $filter Array of filters to apply to the query.
   * @param bool $recursive Whether the query is recursive.
   * @return string The SQL where clause for filtering.
   */
  protected function getFilterQuery(array $filter = [], bool $recursive = false): string
  {
    $table = ($recursive) ? 'cte' : '`' . $this->table . '`';
    $query = '';

    // Apply the filters provided.
    if (!empty($filter)) {
      foreach ($filter as $key => $val) {
        // Skip ignored fields.
        if (in_array($key, $this->ignore)) {
          continue;
        }

        $query .=  ((empty($query)) ? ' WHERE ' : ' AND ');
        $query .= ' ' . $table .  '.`' . $key . '`';

        // Use SQL LIKE for filters that match the `like` array.
        if (in_array($key, $this->like)) {
          $query .= ' LIKE ';
        } else {
          $query .= '=';
        }

        $query .= ':' . $key;
      }
    }

    // Filter for empty columns.
    if (!empty($this->empty)) {
      for ($i = 0; $i < count($this->empty); $i++) {
        $query .= ((strpos($query, ' WHERE ') === false) ? ' WHERE ' : ' AND ');
        $query .= $this->empty[$i] . "=''";
      }
    }

    // Filter for non-empty columns.
    if (!empty($this->notEmpty)) {
      for ($i = 0; $i < count($this->notEmpty); $i++) {
        $query .= ((strpos($query, ' WHERE ') === false) ? ' WHERE ' : ' AND ');
        $query .= $this->notEmpty[$i] . " <> ''";
      }
    }

    return $query;
  }

  /**
   * Retrieves the filtering data based on public properties of the class.
   *
   * @return array Array of filters to be applied to the query.
   */
  protected function getFilterData(): array
  {
    $filter = [];
    $reflect = new \ReflectionClass($this);
    $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

    // Collect filters from public properties.
    for ($i = 0; $i < count($properties); $i++) {
      $key = $properties[$i]->name;
      $type = $properties[$i]->getType();

      // Skip 'id' if it's 0.
      if ($key == 'id' && isset($this->id) && $this->id == 0) {
        continue;
      }

      // Skip certain fields from being used as filters.
      if (in_array($key, ['id', 'sort', 'dir', 'columns'])) {
        continue;
      }

      // Skip ignored fields.
      if (in_array($key, $this->ignore)) {
        continue;
      }

      // Add property values to the filter array based on type.
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

  /**
   * Build the SQL order by clause based on the class's sorting properties.
   *
   * @param bool $recursive Whether sorting should be recursive.
   * @return string The SQL order by clause.
   */
  protected function getSortQuery(bool $recursive = false): string
  {
    $query = '';
    $sort = $this->sort;
    $dir = strtoupper($this->dir);

    // Ensure sorting direction is valid.
    if (empty($dir) || ($dir != 'ASC' && $dir != 'DESC')) {
      $dir = 'ASC';
    }

    // Default sort by `sortOrder` if not set.
    if (empty($sort) && property_exists($this, 'sortOrder')) {
      $sort = 'sortOrder';
    }

    // Handle recursive sorting.
    if ($recursive && in_array($sort, $this->recursiveSort)) {
      $sort = $sort . 'Path';
    }

    // Build order by clause if sorting is defined.
    if (!empty($sort)) {
      $query = ' ORDER BY `' . $sort . '` ' . $dir;
    }

    return $query;
  }

  /**
   * Load cache data if available.
   *
   * @param array $filter The filter criteria to check for cached data.
   * @return bool True if cache was loaded successfully, false otherwise.
   */
  protected function cacheLoad(array $filter): bool
  {
    $status = false;

    // Check if caching is enabled.
    if (Application::getInstance()->conf->system->cache) {
      $path = FS_SITE_CACHE . str_replace("\\", '/', get_class($this)) . '/';
      $file = $this->cacheHash($filter) . '.php';

      // Load cached file if it exists.
      if (file_exists($path . $file)) {
        $cache = file_get_contents($path . $file);
        $cache = substr($cache, 15); // Remove security block.
        $this->data = unserialize($cache); // Unserialize cached data.
        $status = true;
      }
    }

    return $status;
  }

  /**
   * Save the current dataset to the cache.
   *
   * @param array $filter The filter used to generate the cache.
   * @return void
   */
  protected function cacheSave(array $filter)
  {
    // Check if caching is enabled.
    if (Application::getInstance()->conf->system->cache) {
      $path = FS_SITE_CACHE . str_replace("\\", '/', get_class($this)) . '/';
      $file = $this->cacheHash($filter) . '.php';

      $serialized = serialize($this->data);
      $cache = '<?php die(); ?>' . $serialized;

      // Create cache directory if it doesn't exist.
      if (!file_exists($path)) {
        mkdir($path, 0774, true);
      }

      // Save the cache file if it doesn't already exist.
      if (!file_exists($path . $file)) {
        file_put_contents($path . $file, $cache);
      }
    }
  }

  /**
   * Generate a unique cache hash based on the filter array.
   *
   * @param array $filter The filter array to be hashed.
   * @return string The MD5 hash of the filter array.
   */
  protected function cacheHash(array $filter): string
  {
    // Include sorting direction and column in the hash.
    if (!empty($this->dir)) {
      $filter['dir'] = $this->dir;
    }

    if (!empty($this->sort)) {
      $filter['sort'] = $this->sort;
    }

    // Convert the filter array to a JSON string and hash it using MD5.
    $json = json_encode($filter);
    return hash('md5', $json);
  }
}
