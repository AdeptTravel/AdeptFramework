<?php

namespace Adept\Abstract\Data;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Application\Database;
use \Adept\Application\Session\Request\Data\Post;

abstract class Item
{
  /**
   * Cache file
   *
   * @var string
   */
  protected string $cache;

  /**
   * Undocumented variable
   *
   * @var array
   */
  protected array $connections = [];

  /**
   * The database object
   *
   * @var \Adept\Application\Database
   */
  protected Database $db;

  /**
   * Undocumented variable
   *
   * @var array
   */
  protected array $duplicateKeys = [];

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
   * The name of the data item, used for error messages
   *
   * @var string
   */
  protected string $name = 'item';

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
  public function __construct(Database &$db, int|string $id = 0, object|null $obj = null)
  {
    $this->db = $db;

    $table  = strtolower(get_class($this));
    $parts  = explode('\\', $table);
    $offset = ($parts[0] == 'adept') ? 3 : 5;
    $parts  = array_splice($parts, $offset);

    if (empty($this->table)) {
      $this->table = implode('_', $parts);
    }

    if ($id !== 0) {
      $this->cache = (is_numeric($id)) ? $id : hash('md5', $id);
      $col = (is_numeric($id)) ? 'id' : $parts[count($parts) - 1];

      $this->load($id, ((is_numeric($id)) ? 'id' : $col));
    } else if ($id == 0 && isset($obj)) {
      $this->loadFromObj($obj);
    } else {
      $this->init();
    }
  }

  public function init()
  {
    $reflect = new \ReflectionClass($this);
    $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

    $this->excludeKeys[] = 'error';

    foreach ($properties as $p) {

      $key = (string)$p->name;
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
            $this->$key = '';
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

          case 'DateTime':
            $this->$key = new \DateTime();
            break;

          default:

            if (strpos($type, 'Adept\Data\Item') !== false) {

              if ($this->id == 0 && $type != get_class($this)) {
                $this->$key = new $type($this->db);
              }
            }

            break;
        }
      }
    }
  }

  public function load(int|string $id, string $col = 'id'): bool
  {
    //$status = $this->loadCache();
    $status = false;

    $query  = 'SELECT * FROM `' . $this->table . '` AS a';
    $query .= ' WHERE `' . $col . '` = ?';

    if (($obj = $this->db->getObject($query, [$id])) !== false) {
      $status = true;
      $this->loadFromObj($obj);
    }

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
    $keys = [];
    $reflect = new \ReflectionClass($this);
    $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

    if (!empty($prefix)) {
      $prefix .= '_';
    }

    foreach ($properties as $p) {
      $key = $p->name;

      if ($key == 'error') {
        continue;
      }

      if ($p->getType() !== null) {
        $type = $p->getType()->getName();

        //if (in_array($key, $this->postFilters)) {
        if (array_key_exists($key, $this->postFilters)) {
          $method = 'get' . ucfirst($this->postFilters[$key]);
          $this->setVar($key, $post->$method($prefix . $key));
        } else {

          switch ($type) {
            case 'int':
              $this->setVar($key, $post->getInt($prefix . $key));
              break;

            case 'bool':
              $this->setVar($key, $post->getBool($prefix . $key));
              break;

            case 'string':
              $this->setVar($key, $post->getString($prefix . $key));
              break;
              //case 'array':
            case 'object':
              $this->setVar($key, $post->getInt($prefix . $key));
              break;
            case 'DateTime':
              break;
            default:
              //$this->$key = new $type($this->db, $post->getInt($prefix . $key));
              if ($this->$key->id == 0) {
                $this->$key->loadFromPost($post, $prefix);
              }

              break;
          }
        }
      }
    }
  }

  public function setVar(string $key, $val)
  {
    //echo '<div>' . $key . '</div>';
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
        $this->$key = (string)$val;
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
          $this->$key = new $type($this->db, $val);
        } else {
          $this->$key = $val;
        }

        break;
    }
  }

  public function save(string $table = ''): bool
  {
    $status = false;
    $data = $this->getData();

    if (empty($table)) {
      $table = $this->table;
    }

    if ($this->id == 0) {
      // Insert
      if ($this->isDuplicate()) {
        // Duplicate
        $this->setError('Duplicate', 'The ' . $this->name . ' already exists.');
      } else {
        // Insert
        if (($id = $this->db->insertSingleTableGetId($this->table, $data)) !== false) {
          $this->id = $id;
          $status = true;
        }
      }
    } else {
      $status = $this->db->updateSingleTable($table, $data);
    }

    return $status;
  }

  public function getData(): array
  {
    //$debug = '';
    $data = [];

    //print_r($this);
    $reflect = new \ReflectionClass($this);
    $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

    $this->excludeKeys[] = 'error';

    //foreach ($properties as $p) {
    for ($i = 0; $i < count($properties); $i++) {

      $key = $properties[$i]->name;
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

            if ($this->$key->format('Y') != '-0001') {
              $data[$key] = $this->$key->format('Y-m-d H:i:s');
            } else {
              $data[$key] = '0000-00-00 00:00:00';
            }

            break;

          default:

            if (strpos($type, "Adept\\Data\\") !== false) {
              $this->$key->save();

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

  public function getLinked(string $table, string $namespace): array
  {
    $objs = [];

    $ids = $this->db->getObjects(
      "SELECT `" . $table . "` FROM `" . $this->table . '_' . $table . "` WHERE `" . $this->table . "` = ?",
      [$this->id]
    );

    for ($i = 0; $i < count($ids); $i++) {
      $objs[] = new $namespace($this->db, $ids[$i]->$table);
    }

    return $objs;
  }

  public function map(string $table, array $data)
  {
    $keys = '';
    $vals = '';

    if (!$this->db->isDuplicate($table, $data)) {
      foreach ($data as $k => $v) {
        $keys .= "`$k`, ";
        $vals .= '?, ';

        $params[] = $v;
      }

      $keys = substr($keys, 0, strlen($keys) - 2);
      $vals = substr($vals, 0, strlen($vals) - 2);

      $query = "INSERT INTO `$table` ($keys) VALUES ($vals)";

      $this->db->insert($query, $params);
    }
  }

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

  protected function isDuplicate(string $table = ''): bool
  {
    if (empty($table)) {
      $table = $this->table;
    }

    return $this->db->isDuplicate($table, $this->getData());
  }

  protected function setError(string $title, string $message)
  {
    $this->error[] = (object)[
      'title' => $title,
      'message' => $message
    ];
  }
}
