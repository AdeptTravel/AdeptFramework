<?php

namespace Adept\Abstract\Data;

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;
use Adept\Application\Database\Query;
use Adept\Application\Session\Request\Data\Post;
use stdClass;

abstract class Item
{
  /**
   * Name of the associated database table.
   *
   * @var string
   */
  protected string $table;

  /**
   * The column that can be used for loading outside of the ID column.
   *
   * @var string
   */
  protected string $index = '';

  /**
   * Table columns to filter with the LIKE % SQL command.
   *
   * @var array
   */
  protected array $like = [];

  /**
   * Specifies tables to join (INNER JOIN), as $table => $column.
   *
   * @var array
   */
  protected array $joinInner = [];

  /**
   * Specifies tables to join (LEFT JOIN), as $table => $column.
   *
   * @var array
   */
  protected array $joinLeft = [];

  /**
   * Unique keys for duplicate checking.
   *
   * @var array
   */
  protected array $uniqueKeys = [];

  /**
   * List of public variables to exclude from saving into db.
   *
   * @var array
   */
  protected array $excludeKeys = [];

  /**
   * List of public variables to exclude from saving into db on new items.
   *
   * @var array
   */
  protected array $excludeKeysOnNew = [];

  /**
   * The last filter used.
   *
   * @var array
   */
  protected array $filter = [];

  /**
   * The original data, used to compare changes.
   *
   * @var object
   */
  protected object $originalData;

  /**
   * Undocumented variable.
   *
   * @var array
   */
  protected array $postFilters = [];

  /**
   * Array of required fields. (Leave undefined to automatically check all public vars.)
   *
   * @var array
   */
  protected array $required;

  /**
   * ID of the item in the database table.
   *
   * @var int
   */
  public int $id = 0;

  /**
   * Array of errors, used when displaying error messages to users.
   *
   * @var array
   */
  public array $error = [];

  /**
   * Status – Active, Inactive, Block.
   *
   * @var string
   */
  public string $status;

  /**
   * The created date as a MySQL string.
   *
   * @var \DateTime
   */
  public \DateTime $createdAt;

  /**
   * The last dataset returned.
   *
   * @var object
   */
  public object $data;

  /**
   * The last updated date as a MySQL string.
   *
   * @var \DateTime
   */
  public \DateTime $updatedAt;

  public function __construct()
  {
    $this->originalData = new stdClass();
    $this->excludeKeys[] = 'error';
    $this->excludeKeys[] = 'updatedAt';
  }

  /**
   * Returns a Query instance preconfigured with the select columns and joins.
   *
   * @return Query
   */
  protected function getQuery(): Query
  {
    // Get the Database instance (needed for column info)
    $db = Application::getInstance()->db;
    // Get all columns from the main table.
    $columns = $db->getColumns($this->table);
    $selectColumns = [];

    // Build select expressions for main table columns.
    foreach ($columns as $col) {
      $selectColumns[] = "`{$this->table}`.`{$col}` AS `{$col}`";
    }

    // Include columns from inner/left joins.
    $joins = array_merge($this->joinInner, $this->joinLeft);
    if (!empty($joins)) {
      foreach ($joins as $joinTable => $joinColumn) {
        $joinCols = $db->getColumns($joinTable);
        foreach ($joinCols as $jc) {
          $alias = strtolower($joinTable) . ucfirst($jc);
          $selectColumns[] = "`{$joinTable}`.`{$jc}` AS `{$alias}`";
        }
      }
    }

    // Initialize the Query.
    $qb = Query::table($this->table)
      ->select($selectColumns);

    // Add INNER JOIN clauses.
    if (!empty($this->joinInner)) {
      foreach ($this->joinInner as $joinTable => $col) {
        $qb->join($joinTable, '', "`{$this->table}`.`{$col}` = `{$joinTable}`.`id`", 'INNER');
      }
    }
    // Add LEFT JOIN clauses.
    if (!empty($this->joinLeft)) {
      foreach ($this->joinLeft as $joinTable => $col) {
        $qb->leftJoin($joinTable, '', "`{$this->table}`.`{$col}` = `{$joinTable}`.`id`");
      }
    }
    return $qb;
  }

  /**
   * Load an item from the database using its ID.
   *
   * @param int $id
   * @return bool
   */
  public function loadFromID(int $id): bool
  {
    $db     = Application::getInstance()->db;
    $status = $this->cacheLoad((string)$id);

    if (!$status) {
      $qb = $this->getQuery();
      // Add condition on the primary key.
      $qb->where('id', '=', $id);
      $params = $qb->getParams();
      $query  = $qb->getQuery();

      if (($obj = $db->getObject($query, $params)) !== false) {
        $this->loadFromObj($obj);
        $status = true;
      }
    }

    return $status;
  }

  /**
   * Load an item based on a unique index.
   *
   * @param string|int $index
   * @return bool
   */
  public function loadFromIndex(string|int $index): bool
  {
    $db     = Application::getInstance()->db;
    $status = $this->cacheLoad(hash('md5', (string)$index));

    if (!$status) {
      $qb = $this->getQuery();
      $qb->where($this->index, '=', $index);
      $params = $qb->getParams();
      $query  = $qb->getQuery();

      if (($obj = $db->getObject($query, $params)) !== false) {
        $status = true;
        $this->loadFromObj($obj);
      }
    }

    return $status;
  }

  /**
   * Load an item based on multiple index conditions.
   *
   * @param array $index
   * @return bool
   */
  public function loadFromIndexes(array $index): bool
  {
    $db     = Application::getInstance()->db;
    $status = $this->cacheLoad(hash('md5', print_r($index, true)));

    if (!$status) {
      $qb = $this->getQuery();
      // Add each index condition to the Query.
      foreach ($index as $key => $value) {
        $qb->where($key, '=', $value);
      }
      $params = $qb->getParams();
      $query  = $qb->getQuery();

      if (($obj = $db->getObject($query, $params)) !== false) {
        $status = true;
        $this->loadFromObj($obj);
      }
    }
    return $status;
  }

  /**
   * Loads the object properties from a database object.
   *
   * @param object $obj
   * @return void
   */
  public function loadFromObj(object $obj)
  {
    $this->data = $obj;
    foreach ($obj as $k => $v) {
      if (!empty($v)) {
        $this->setVar($k, $v);
      }
    }
    $this->originalData = $this->getData();
  }

  /**
   * Load data from a Post object.
   *
   * @param Post $post
   * @param string $prefix
   * @return void
   */
  public function loadFromPost(Post $post, string $prefix = '')
  {
    $this->data = new stdClass();
    $reflect    = new \ReflectionClass($this);
    $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

    if (!empty($prefix)) {
      $prefix .= '_';
    }

    for ($i = 0; $i < count($properties); $i++) {
      $key = $properties[$i]->name;
      if (in_array($key, ['id', 'createdAt', 'data', 'error'])) {
        continue;
      }
      if ($properties[$i]->getType() !== null) {
        $type = $properties[$i]->getType()->getName();
        if (array_key_exists($key, $this->postFilters)) {
          $method = 'get' . ucfirst($this->postFilters[$key]);
          $this->setVar($key, $post->$method($prefix . $key));
        } else {
          if ($post->exists($prefix . $key)) {
            switch ($type) {
              case 'int':
                $this->$key = $post->getInt($prefix . $key);
                break;
              case 'bool':
                $this->$key = $post->getBool($prefix . $key);
                break;
              case 'string':
                $this->$key = $post->getString($prefix . $key);
                break;
              case 'object':
                $this->$key = $post->getInt($prefix . $key);
                break;
              case 'DateTime':
                $this->$key = $post->getDateTime($prefix . $key);
                break;
              default:
                $this->$key = new $type($post->getInt($prefix . $key));
                break;
            }
            $this->data->$key = $this->$key;
          }
        }
      }
    }
  }

  /**
   * Saves the current data object in the DB.
   *
   * @return bool
   */
  public function save(): bool
  {
    $status = false;
    $app    = Application::getInstance();
    $data   = $this->getData();

    if (count($this->error) == 0) {
      if ($this->hasChanged($data)) {
        if ($this->id == 0) {
          // Insert – first check for duplicate
          if ($this->isDuplicate()) {
            $this->setError('Duplicate', 'The data already exists in table ' . $this->table . '.');
          } else {
            if (($id = $app->db->insertSingleTableGetId($this->table, $data)) !== false) {
              $this->id = $id;
              $status = true;
            }
          }
        } else {
          $status = $app->db->updateSingleTable($this->table, $data);
          if (!$status) {
            $this->setError('Failed', 'Failed to save the data to table ' . $this->table . '.');
          }
        }
      } else {
        $status = true;
      }
    }

    if ($status) {
      $this->cacheSave();
    }
    return $status;
  }

  /**
   * Checks if the object has changed after loading.
   *
   * @param object|null $data
   * @return bool
   */
  public function hasChanged(object|null $data = null): bool
  {
    if (!isset($data)) {
      $data = $this->getData();
    }
    return ($this->originalData != $data);
  }

  /**
   * Checks for required data.
   *
   * @return bool
   */
  public function isValid(): bool
  {
    if (isset($this->required)) {
      foreach ($this->required as $k => $v) {
        if (empty($this->$k)) {
          $this->setError($v, $v . ' is a required field.');
        }
      }
    } else if (!empty($this->required)) {
      $reflection = new \ReflectionClass($this);
      $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
      foreach ($properties as $p) {
        $key = $p->name;
        if (empty($this->$key)) {
          $key = str_replace('_', ' ', $key);
          $key = ucwords($key);
          $this->setError($key, $key . ' is a required field.');
        }
      }
    }
    return empty($this->error);
  }

  /**
   * Checks for duplicate data in the DB based on the uniqueKeys array.
   *
   * @return int|bool
   */
  public function isDuplicate(): int|bool
  {
    $app = Application::getInstance();
    if (!empty($this->uniqueKeys)) {
      $params = [];
      $qb = Query::table($this->table)
        ->select(['id']);
      for ($i = 0; $i < count($this->uniqueKeys); $i++) {
        $key = $this->uniqueKeys[$i];
        $qb->where($key, '=', $this->$key);
      }
      return $app->db->getInt($qb->getQuery(), $qb->getParams());
    } else {
      return $app->db->isDuplicate($this->table, $this->getData());
    }
  }

  /**
   * Delete the current item.
   *
   * @return bool
   */
  public function delete(): bool
  {
    $app = Application::getInstance();
    $result = false;
    if ($this->id > 0) {
      $result = $app->db->delete($this->table, $this->id);
    }
    return $result;
  }

  /**
   * Returns the current object data as an object, used when storing data or caching.
   *
   * @param bool $sql
   * @return object
   */
  protected function getData(bool $sql = true): object
  {
    $data       = new stdClass();
    $reflection = new \ReflectionClass($this);
    $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

    for ($i = 0; $i < count($properties); $i++) {
      $key  = $properties[$i]->name;
      $type = $properties[$i]->getType();

      if ($key == 'id' && $this->$key == 0) {
        continue;
      }
      if (in_array($key, $this->excludeKeys)) {
        continue;
      }
      if (in_array($key, ['createdAt', 'data'])) {
        continue;
      }
      if (empty($this->$key)) {
        $data->$key = null;
      } else if (
        isset($this->$key)
        && !in_array($key, $this->excludeKeys)
        && !($this->id == 0 && in_array($key, $this->excludeKeysOnNew))
      ) {
        switch ($type) {
          case 'string':
            $data->$key = trim($this->$key);
            break;
          case 'int':
            $data->$key = (int)$this->$key;
            break;
          case 'bool':
            $data->$key = ($this->$key) ? 1 : 0;
            break;
          case 'array':
            $data->$key = json_encode($this->$key);
            break;
          case 'DateTime':
            $val = $this->$key->format('Y-m-d H:i:s');
            $data->$key = ($this->$key->format('Y') != '-0001' && $val != '0000-01-01 00:00:00')
              ? $val
              : '0000-00-00 00:00:00';
            break;
          default:
            if (strpos($type, "Adept\\Data\\") !== false) {
              $data->$key = $this->$key->id;
            } else {
              $data->$key = json_encode($this->$key);
            }
            break;
        }
      }
    }
    return $data;
  }

  /**
   * Allows data objects to override the query used to retrieve the data.
   *
   * Now simply returns the built query from the Query.
   *
   * @return string
   */
  //protected function getQuery(): string
  //{
  //  return $this->getQuery()->getQuery();
  //}

  /**
   * Sets an error (used on the client side).
   *
   * @param string $title
   * @param string $message
   * @return void
   */
  protected function setError(string $title, string $message)
  {
    $this->error[] = (object)[
      'title'   => $title,
      'message' => $message
    ];
  }

  /**
   * Sets a variable from a key/value pair.
   *
   * @param string $key
   * @param mixed $val
   * @return void
   */
  protected function setVar(string $key, $val)
  {
    if (property_exists($this, $key)) {
      $reflection = new \ReflectionProperty($this, $key);
      $type = $reflection->getType()->getName();

      switch ($type) {
        case 'string':
          $this->$key = (string)$val;
          break;
        case 'int':
          $this->$key = (int)$val;
          break;
        case 'bool':
          $this->$key = (bool)$val;
          break;
        case 'array':
          $this->$key = json_decode($val);
          break;
        case 'DateTime':
          if (strlen($val) == 10) {
            $this->$key = \DateTime::createFromFormat('Y-m-d', $val);
          } else {
            $this->$key = \DateTime::createFromFormat('Y-m-d H:i:s', $val);
          }
          break;
        case 'object':
          $this->$key = json_decode($val);
          break;
        default:
          if (strpos($type, "Adept\\Data\\Item") !== false) {
            $this->$key = new $type($val);
          } else {
            $this->$key = $val;
          }
          break;
      }
    }
  }

  /**
   * Load cached data.
   *
   * @param string $file
   * @return bool
   */
  protected function cacheLoad(string $file): bool
  {
    $status = false;
    if (Application::getInstance()->conf->system->cache) {
      $path = FS_SITE_CACHE . str_replace("\\", '/', get_class($this)) . '/';
      $file = $file . '.php';
      if (file_exists($path . $file)) {
        $cache = file_get_contents($path . $file);
        $cache = substr($cache, 15);
        $data  = unserialize($cache);
        foreach ($data as $k => $v) {
          $this->$k = $v;
        }
        $status = true;
      }
    }
    return $status;
  }

  /**
   * Save the cache file.
   *
   * @return void
   */
  protected function cacheSave()
  {
    if (Application::getInstance()->conf->system->cache) {
      $path = FS_SITE_CACHE . str_replace("\\", '/', get_class($this)) . '/';
      $data = $this->getData();
      $serialized = serialize($data);
      $cache = '<?php die(); ?>' . $serialized;
      if (!file_exists($path)) {
        mkdir($path, 0774, true);
      }
      file_put_contents($path . $this->id . '.php', $cache);
      file_put_contents($path . hash('md5', $this->index) . '.php', $cache);
      $path = str_replace("Data/Item/", "Data/Table/", $path);
      array_map([$this, 'cacheDelete'], array_filter((array) glob($path . '*')));
    }
  }

  /**
   * Delete a cache file or directory.
   *
   * @param string $item
   * @return void
   */
  protected function cacheDelete($item)
  {
    if (is_dir($item)) {
      $items = array_diff(scandir($item), ['.', '..']);
      if (count($items) > 0) {
        for ($i = 2; $i < count($items) + 2; $i++) {
          unlink($item . '/' . $items[$i]);
        }
      }
      rmdir($item);
    } else {
      unlink($item);
    }
  }
}
