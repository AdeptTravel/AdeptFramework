<?php

namespace AdeptCMS\Base\Data;

defined('_ADEPT_INIT') or die('No Access');

abstract class Item
{
  /**
   * The database object
   *
   * @var \AdeptCMS\Application\Database
   */
  protected $db;

  /**
   * Undocumented variable
   *
   * @var array
   */
  protected $index = [];

  /**
   * Cache file
   *
   * @var string
   */
  protected $cache;

  /**
   * ID of the record in the database table
   *
   * @var int
   */
  public $id;

  /**
   * Name of the associated database table
   *
   * @var string
   */
  protected $table;

  /**
   * List of public variabled to exclude from saving into db
   *
   * @var array
   */
  protected $excludeField = [];

  /**
   * An array of error messages usually used when saving the data.
   *
   * @var array
   */
  public $errors;

  public function __construct(
    \AdeptCMS\Application\Database $db,
    int|string $id = 0,
    object|null $obj = null
  ) {

    $this->db = $db;
    $this->index = [];

    $parts = explode('\\', strtolower(get_class($this)));
    $parts = array_splice($parts, (($parts[0] == 'adeptcms') ? 3 : 5));

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

  protected function init()
  {
  }

  public function load(
    int|string $id,
    string $col = 'id'
  ): bool {

    $status = false;

    if (!$status) {

      $query  = 'SELECT * FROM `' . $this->table . '` AS a';
      $query .= ' WHERE `' . $col . '` = ?';

      if (($obj = $this->db->getObject($query, [$id])) !== false) {
        $status = true;
        $this->loadFromObj($obj);
      }
    }

    return $status;
  }

  public function loadFromObj(object $obj)
  {
    foreach ($obj as $k => $v) {

      if (!empty($v)) {
        if (strlen($v) == 19 && \DateTime::createFromFormat('Y-m-d H:i:s', $v) !== false) {
          $this->$k = new \DateTime($v);
        } else if (
          (substr($v, 0, 1) == '{' && substr($v, -1, 1) == '}')
          || (substr($v, 0, 1) == '[' && substr($v, -1, 1) == ']')
        ) {
          $this->$k = json_decode($v);
        } else {
          $this->$k = $v;
        }
      }
    }
  }

  public function save(): bool
  {
    $status = false;

    if (($id = $this->db->insertSingleTableGetId($this->table, $this->getData())) !== false) {
      $this->id = $id;
      $this->saveCache();
      $status = true;
    } else {
      $this->errors[] = 'Error saving a thing.';
    }

    return $status;
  }

  public function connectParent(string $parent, int $id): bool
  {
    $status = false;
    $table =  $parent . '_' . $this->table;

    $data[$parent] = $id;
    $data[$this->table] = $this->id;

    if ($this->db->insertSingleTableGetId($this->table, $data)) {
      $status = true;
    }

    return $status;
  }

  public function connectChild(string $child, int $id): bool
  {
    $status = false;
    $table =  $this->table . '_' . $child;
    $data[$this->table] = $this->id;
    $data[$child] = $id;

    if ($this->db->insertSingleTableGetId($this->table, $data)) {
      $status = true;
    }

    return $status;
  }

  public function getData(): array
  {
    $data = [];
    $reflect = new \ReflectionClass($this);
    $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);

    foreach ($properties as $p) {
      $key = $p->name;

      if (!in_array($key, $this->excludeField)) {
        if (isset($this->$key)) {
          if (is_object($this->$key)) {
            if ($this->$key instanceof \DateTime) {
              $data[$key] = $this->$key->format('Y-m-d H:i:s');
            } else if (property_exists($this->$key, 'id')) {

              if (method_exists($this->$key, 'save')) {
                $this->$key->save();
              }

              // Save ID to another data item instead of item
              $data[$key] = $this->$key->id;
            } else {
              // Encode object as JSON
              $data[$key] = json_encode($this->$key);
            }
          } else if (is_array($this->$key)) {
            $data[$key] = json_encode($this->$key);
          } else {
            $data[$key] = $this->$key;
          }
        }
      }
    }

    return $data;
  }

  protected function saveCache()
  {
    if (!empty($this->cache)) {
      $path = FS_CACHE . str_replace("\\", '/', get_class($this)) . '/';
      $file = $path . $this->cache;

      if (!file_exists($path)) {
        mkdir($path, 0755, true);
      }

      file_put_contents($file, json_encode($this->getData()));
    }
  }

  protected function loadCache(): bool
  {
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
  }
}
