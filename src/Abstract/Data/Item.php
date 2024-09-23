<?php

namespace Adept\Abstract\Data;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Application;
use \Adept\Application\Session\Request\Data\Post;

abstract class Item
{
  /**
   * Name of the associated database table
   *
   * @var string
   */
  protected string $table = '';

  /**
   * The column that can be used for loading outside of the ID column
   *
   * @var string
   */
  protected string $index = '';

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
   * Undocumented variable
   *
   * @var array
   */
  protected array $uniqueKeys = [];

  /**
   * List of public variabled to exclude from saving into db
   *
   * @var array
   */
  protected array $excludeKeys = [];

  /**
   * List of public variabled to exclude from saving into db
   *
   * @var array
   */
  protected array $excludeKeysOnNew = [];

  /**
   * The last filter used
   *
   * @var array
   */
  protected array $filter = [];

  /**
   * The last dataset returned
   *
   * @var array
   */
  protected array $data;

  /**
   * The original data, used to 
   *
   * @var array
   */
  protected array $originalData = [];

  /**
   * Undocumented variable
   *
   * @var array
   */
  protected array $postFilters = [];

  /**
   * Array of required fields.  To automatically check all public vars don't
   * initilize this.  To check for nothing initilize this as an empty array.
   *
   * @var array
   */
  protected array $required;

  /**
   * ID of the item in the database table
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

  public function loadFromID(int $id): bool
  {
    $db     = \Adept\Application::getInstance()->db;
    $status = $this->cacheLoad($id);

    if (!$status) {
      $query  = $this->getQuery();
      $query .= " WHERE `$this->table`.`id` = :id";
      $params = [':id' => $id];

      if (($obj = $db->getObject($query, $params)) !== false) {
        $this->loadFromObj($obj);
        $status = true;
      }
    }

    if ($status) {
      $this->originalData = $this->getData();
    }

    return $status;
  }

  public function loadFromIndex(string|int $index): bool
  {
    $db     = \Adept\Application::getInstance()->db;
    $status = $this->cacheLoad(hash('md5', $index));

    if (!$status) {
      $query = $this->getQuery();
      $query .= " WHERE `$this->index` = :index";
      $params = [':index' => $index];

      if (($obj = $db->getObject($query, $params)) !== false) {
        $status = true;
        $this->loadFromObj($obj);
      }
    }

    $this->originalData = $this->getData();

    return $status;
  }

  public function loadFromObj(object $obj)
  {
    foreach ($obj as $k => $v) {
      if (!empty($v)) {
        $this->setVar($k, $v);
      }
    }
  }

  public function loadFromPost(Post $post, string $prefix = '')
  {
    $reflect    = new \ReflectionClass($this);
    $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

    if (!empty($prefix)) {
      $prefix .= '_';
    }

    foreach ($properties as $p) {
      $key = $p->name;

      if ($key == 'error' || $key == 'id') {
        continue;
      }

      if ($p->getType() !== null) {
        $type = $p->getType()->getName();

        if (array_key_exists($key, $this->postFilters)) {
          $method = 'get' . ucfirst($this->postFilters[$key]);
          $this->setVar($key, $post->$method($prefix . $key));
        } else {

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
        }
      }
    }
  }

  /**
   * Saves the current data object in the db
   *
   * @param  string $table
   *
   * @return bool
   */
  public function save(): bool
  {
    $status = false;
    $app    = Application::getInstance();
    $data   = $this->getData();

    if ($this->changed($data)) {
      if ($this->id == 0) {

        // Insert
        if ($this->duplicate()) {
          // Duplicate
          $this->setError('Duplicate', 'The data already exists in table ' . $this->table . '.');

          // TODO: Add check for is Valid here
          // } else if (!$this->valid()) {

        } else {
          // Insert
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

    if ($status) {
      $this->cacheSave();
    }

    return $status;
  }

  /**
   * Get's the current object as an array, used when storing the data in the DB or caching
   *
   * @return array
   */
  protected function getData(bool $sql = true): array
  {
    $data       = [];
    $reflection = new \ReflectionClass($this);
    $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

    $this->excludeKeys[] = 'error';

    for ($i = 0; $i < count($properties); $i++) {

      $key  = $properties[$i]->name;
      $type = $properties[$i]->getType();

      if ($key == 'id' && $this->$key == 0) {
        continue;
      }

      if (
        isset($this->$key)
        && !in_array($key, $this->excludeKeys)
        && !($this->id == 0 && in_array($key, $this->excludeKeysOnNew))
      ) {

        switch ($type) {
          case 'string':
            if ($this->$key != '0000-00-00 00:00:00') {

              $data[$key] = trim($this->$key);
            }

            break;

          case 'int':

            $data[$key] = (int)$this->$key;

            break;

          case 'bool':

            $data[$key] = ($this->$key) ? 1 : 0;
            break;

          case 'array':

            $data[$key] = json_encode($this->$key);
            break;

          case 'DateTime':

            $val = $data[$key] = $this->$key->format('Y-m-d H:i:s');

            if ($this->$key->format('Y') != '-0001' && $val != '0000-01-01 00:00:00') {
              $data[$key] = $val;
            } else {
              $data[$key] = '0000-00-00 00:00:00';
            }

            break;

          default:

            if (strpos($type, "Adept\\Data\\") !== false) {
              //$this->$key->save();
              $data[$key] = $this->$key->id;
            } else {
              // Encode object as JSON
              $data[$key] = json_encode($this->$key);
            }

            break;
        }
      }
    }

    return $data;
  }

  /**
   * Allows data objects to override the query used to retrieve the data
   *
   * @param  string $col
   *
   * @return string
   */
  protected function getQuery(): string
  {
    $query  = $this->getSelectQuery();
    $query .= ' FROM ' . $this->table;
    $query .= $this->getJoinQuery();

    return $query;
  }

  protected function getSelectQuery(): string
  {
    $db    = Application::getInstance()->db;
    $cols  = $db->getColumns($this->table);
    $joins = array_merge($this->joinInner, $this->joinLeft);

    $query = 'SELECT ';

    for ($i = 0; $i < count($cols); $i++) {

      if ($i > 0) {
        $query .= ', ';
      }

      if (!empty($joins)) {
        $query .= "`$this->table`.";
      }

      $query .= "`$cols[$i]` AS `$cols[$i]`";
    }

    $joins = array_merge($this->joinInner, $this->joinLeft);

    if (!empty($joins)) {
      foreach ($joins as $table => $col) {

        $cols = $db->getColumns($table);

        for ($i = 0; $i < count($cols); $i++) {
          $query .= ', ';
          $query .= "`$table`.`$cols[$i]` AS ";
          $query .= strtolower($table) . ucfirst($cols[$i]);
        }
      }
    }

    return $query;
  }

  protected function getJoinQuery(bool $recursive = false)
  {
    $query = '';

    if (!empty($this->joinInner)) {
      foreach ($this->joinInner as $t => $c) {
        $query .= " INNER JOIN $t ON $this->table.$c = $t.id";
      }
    }

    if (!empty($this->joinLeft)) {
      foreach ($this->joinLeft as $t => $c) {
        $query .= " LEFT JOIN $t ON $this->table.$c = $t.id";
      }
    }

    return $query;
  }

  /**
   * Set's an error, errors are used on the client end
   *
   * @param  string $title
   * @param  string $message
   *
   * @return void
   */
  protected function setError(string $title, string $message)
  {
    $this->error[] = (object)[
      'title' => $title,
      'message' => $message
    ];
  }

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
          $this->$key = \DateTime::createFromFormat('Y-m-d H:i:s', $val);
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
   * Checks if the object has changed after loading
   *
   * @param  array $data
   *
   * @return bool
   */
  protected function changed(array $data = []): bool
  {
    if (empty($data)) {
      $data = $this->getData();
    }

    return ($this->originalData != $data);
  }

  /**
   * Checks for required data
   *
   * @return bool
   */
  protected function valid(): bool
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

      $this->excludeKeys[] = 'error';

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
   * Checks for duplicate data in the db based on the uniqueKeys array
   *
   * @param  string   $table
   *
   * @return int|bool
   */
  protected function duplicate(): int|bool
  {
    $app = \Adept\Application::getInstance();

    if (!empty($this->uniqueKeys)) {
      $query = "SELECT `id` FROM `$this->table`";

      for ($i = 0; $i < count($this->uniqueKeys); $i++) {
        $key      = $this->uniqueKeys[$i];
        $query   .= (($i == 0) ? ' WHERE' : ' AND');
        $query   .= ' `' . $key . '` = ?';

        if (is_object($this->$key)) {
          $params[] = $this->$key->id;
        } else {
          $params[] = $this->$key;
        }
      }

      return $app->db->getInt($query, $params);
    } else {
      return $app->db->isDuplicate($this->table, $this->getData());
    }
  }

  /**
   * Delete the current item
   *
   * @return bool
   */
  public function delete(): bool
  {
    $app = \Adept\Application::getInstance();
    $result = false;

    if ($this->id > 0) {
      $result = $app->db->delete($this->table, $this->id);
    }

    return $result;
  }

  /**
   * Load the cache data
   *
   * @param  int|string $val
   *
   * @return bool
   */

  protected function cacheLoad(string $file): bool
  {
    $status = false;

    $path = FS_SITE_CACHE . str_replace("\\", '/', get_class($this)) . '/';
    $file = $file . '.php';

    if (file_exists($path . $file)) {
      // Get the serialized cache data
      $cache = file_get_contents($path . $file);
      // Remove security block
      $cache = substr($cache, 15);
      // Unseralize the data
      $data  = unserialize($cache);

      // Set the objects variable from the cache data
      foreach ($data as $k => $v) {
        $this->$k = $v;
      }

      $status = true;
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
  protected function cacheSave()
  {
    $path = FS_SITE_CACHE . str_replace("\\", '/', get_class($this)) . '/';

    $data = $this->getData();

    $serialized = serialize($data);
    $cache = '<?php die(); ?>' . $serialized;

    if (!file_exists($path)) {
      mkdir($path, 0774, true);
    }

    file_put_contents($path . $this->id . '.php', $cache);
    file_put_contents($path . hash('md5', $this->index) . '.php', $cache);

    // Delete all table cache files related the the database table of this item
    $path = str_replace("Data/Item/", "Data/Table/", $path);

    array_map([$this, 'cacheDelete'], array_filter((array) glob($path . '*')));
  }

  // Method to delete files and directories
  protected function cacheDelete($item)
  {
    if (is_dir($item)) {

      $items = array_diff(scandir($item), ['.', '..']);

      if (count($items) > 0) {
        for ($i = 2; $i < count($items) + 2; $i++) {
          unlink($item . '/' . $items[$i]);
        }
      }

      // Remove the empty directory
      rmdir($item);
    } else {
      // Delete the file
      unlink($item);
    }
  }
}
