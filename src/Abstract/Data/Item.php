<?php

namespace Adept\Abstract\Data;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Application\Session\Request\Data\Post;

abstract class Item
{
  /**
   * Allow caching?
   *
   * @var bool
   */
  protected bool $cache;

  /**
   * The path to the cache folder
   *
   * @var string
   */
  protected string $cachePath = '';

  /**
   * The cache file name
   *
   * @var string
   */
  protected string $cacheFile = '';

  /**
   * Undocumented variable
   *
   * @var array
   */
  protected array $connections = [];

  /**
   * The column used when the construct function is called
   *
   * @var string
   */
  protected string $indexCol = '';

  /**
   * Undocumented variable
   *
   * @var array
   */
  protected array $uniqueKeys = [];

  /**
   * The name of the data item, used for error messages
   *
   * @var string
   */
  protected string $errorName = 'item';

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
   * Undocumented variable
   *
   * @var array
   */
  protected array $index = [];

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
   * Name of the associated database table
   *
   * @var string
   */
  protected string $table = '';

  /**
   * ID of the record in the database table
   *
   * @var int
   */
  public int $id = 0;

  /**
   * Undocumented variable
   *
   * @var array
   */
  public array $error = [];

  /**
   * Undocumented function
   *
   * @param  \Adept\Application\Database $db
   * @param  int                         $id
   * @param  object|null|null            $obj
   */
  public function __construct(int|string|object $val = 0, bool $cache = true)
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

    $this->cache = $cache;
    $this->cachePath = FS_SITE_CACHE . str_replace("\\", '/', get_class($this)) . '/';

    switch (gettype($val)) {
      case 'integer':
        if ($val != 0) {
          $this->cacheFile = $val . '.php';
          $this->load($val, 'id');
        } else {
          $this->init();
        }
        break;

      case 'string':
        if (empty($this->indexCol)) {
          $this->indexCol = strtolower(end($parts));
        }
        $this->cacheFile = hash('md5', $val) . '.php';
        $this->load($val, $this->indexCol);
        break;

      case 'object':
        $this->loadFromObj($val);
        break;

      default:
        break;
    }
  }

  public function __destruct()
  {
    if (!file_exists($this->cachePath . $this->cachePath) && $this->cache) {
      $this->cacheSave();
    }
  }

  public function init()
  {
    $reflect    = new \ReflectionClass($this);
    $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

    $this->excludeKeys[] = 'error';

    foreach ($properties as $p) {

      $key  = (string)$p->name;
      $type = (string)$p->getType();

      if ($key == 'id' && $this->$key == 0) {
        continue;
      }

      if (
        !in_array($key, $this->excludeKeys)
        //&& !in_array($key, $this->excludeKeysOnNew)
        && !isset($this->$key)
      ) {
        switch ($type) {
          case 'string':
            // Check if the string contains a MySQL DateTime
            if (in_array($key, ['archive', 'created', 'modified', 'publish'])) {
              $this->$key = '0000-00-00 00:00:00';
            } else {
              $this->$key = '';
            }

            break;

          case 'int':
            $this->$key = 0;

            break;

          case 'bool':
            $this->$key = false;
            break;

          case 'array':
            $this->$key = [];
            break;

          default:

            if (strpos($type, 'Adept\Data\Item') !== false) {
              if ($this->id == 0 && $type != get_class($this)) {
                $this->$key = new $type();
              }
            }

            break;
        }
      }
    }
  }

  public function load(int|string $id, string $col = 'id'): bool
  {
    $db     = \Adept\Application::getInstance()->db;
    $status = $this->cacheLoad($col, $id);

    if (!$status) {
      $query = $this->getQuery($col);

      if (($obj = $db->getObject($query, [$id])) !== false) {
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
   * Checks if the object has changed after loading
   *
   * @param  array $data
   *
   * @return bool
   */
  public function hasChanged(array $data = []): bool
  {
    if (empty($data)) {
      $data = $this->getData();
    }

    return ($this->originalData != $data);
  }

  /**
   * Saves the current data object in the db
   *
   * @param  string $table
   *
   * @return bool
   */
  public function save(string $table = ''): bool
  {
    $status = false;
    $app = \Adept\Application::getInstance();
    $data   = $this->getData();

    if ($this->hasChanged($data)) {

      if (empty($table)) {
        $table = $this->table;
      }

      if ($this->id == 0) {
        // Insert
        if ($this->isDuplicate()) {
          // Duplicate
          $this->setError('Duplicate', 'The ' . $this->errorName . ' already exists.');
        } else {
          // Insert
          if (($id = $app->db->insertSingleTableGetId($this->table, $data)) !== false) {
            $this->id = $id;
            $status = true;
          }
        }
      } else {
        $status = $app->db->updateSingleTable($table, $data);
      }
    }
    $this->cachePurge();

    if ($status && $this->cache) {
      $this->cacheSave();
    }

    return $status;
  }

  public function cachePurge()
  {
    array_map('unlink', array_filter((array) glob($this->cachePath . '*')));
  }

  /**
   * Get's the current object as an array, used when storing the data in the DB or caching
   *
   * @return array
   */
  public function getData(bool $sql = true): array
  {
    $data       = [];
    $reflect    = new \ReflectionClass($this);
    $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

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
   * Delete the current record
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

  protected function setVar(string $key, $val)
  {
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

  /**
   * Allows data objects to override the query used to retrieve the data
   *
   * @param  string $col
   *
   * @return string
   */
  protected function getQuery(string $col = 'id'): string
  {
    $query  = 'SELECT * FROM `' . $this->table . '` AS a';
    $query .= ' WHERE `' . $col . '` = ?';

    return $query;
  }

  protected function cacheLoad(string|int $key, string $val): bool
  {
    $status = false;
    if ($this->cache) {

      if (file_exists($this->cachePath . $this->cacheFile)) {
        $serialized = file_get_contents($this->cachePath . $this->cacheFile);
        $serialized = substr($serialized, 15);
        $data      = unserialize($serialized);

        foreach ($data as $k => $v) {
          $this->$k = $v;
        }

        $status = true;
      }
    }
    return $status;
  }

  protected function cacheSave()
  {
    $data = [];

    $reflect    = new \ReflectionClass($this);
    $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

    $this->excludeKeys[] = 'error';

    for ($i = 0; $i < count($properties); $i++) {
      $type = $properties[$i]->getType();
      $key  = $properties[$i]->name;

      if (
        isset($this->$key)
        && !in_array($key, $this->excludeKeys)
        && !($this->id == 0 && in_array($key, $this->excludeKeysOnNew))
      ) {

        switch ($type) {
          case 'string':
          case 'int':
          case 'bool':
          case 'array':
          case 'DateTime':
            $data[$key] = $this->$key;
            break;

          default:

            if (strpos($type, "Adept\\Data\\") !== false) {
              $data[$key] = $this->$key->id;
            }

            break;
        }
      }
    }

    $serialized = serialize($data);
    $cache = '<?php die(); ?>' . $serialized;

    if (!file_exists($this->cachePath)) {
      mkdir($this->cachePath, 0774, true);
    }

    if (!empty($this->cacheFile)) {
      file_put_contents($this->cachePath . $this->cacheFile, $cache);
    }
  }


  public function getLinked(string $table, string $namespace): array
  {
    $app = \Adept\Application::getInstance();
    $objs = [];

    $ids = $app->db->getObjects(
      "SELECT `" . $table . "` FROM `" . $this->table . '_' . $table . "` WHERE `" . $this->table . "` = ?",
      [$this->id]
    );

    for ($i = 0; $i < count($ids); $i++) {
      $objs[] = new $namespace($ids[$i]->$table);
    }

    return $objs;
  }

  public function map(string $table, array $data)
  {
    $app  = \Adept\Application::getInstance();
    $keys = '';
    $vals = '';

    if (!$app->db->isDuplicate($table, $data)) {
      foreach ($data as $k => $v) {
        $keys .= "`$k`, ";
        $vals .= '?, ';

        $params[] = $v;
      }

      $keys = substr($keys, 0, strlen($keys) - 2);
      $vals = substr($vals, 0, strlen($vals) - 2);

      $query = "INSERT INTO `$table` ($keys) VALUES ($vals)";

      $app->db->insert($query, $params);
    }
  }

  /**
   * Checks for required data
   *
   * @return bool
   */
  protected function isValid(): bool
  {
    if (isset($this->required)) {
      foreach ($this->required as $k => $v) {
        if (empty($this->$k)) {
          $this->setError($v, $v . ' is a required field.');
        }
      }
    } else if (!empty($this->required)) {
      $reflect = new \ReflectionClass($this);
      $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

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
  protected function isDuplicate(string $table = ''): int|bool
  {
    $app = \Adept\Application::getInstance();

    if (empty($table)) {
      $table = $this->table;
    }

    if (!empty($this->uniqueKeys)) {
      $query = "SELECT `id` FROM `$table`";

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
      return $app->db->isDuplicate($table, $this->getData());
    }
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
}
