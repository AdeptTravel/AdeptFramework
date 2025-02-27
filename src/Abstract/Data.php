<?php

namespace Adept\Abstract;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Application\Session\Request\Data\Post;

abstract class Data
{
  /**
   * Allow caching?
   *
   * @var bool
   */
  protected string $cache;

  abstract protected function getCacheFile(): string;

  public function cachePurge()
  {
    // Delete Item cache file
    if (!empty($this->cache) && file_exists($this->cache)) {
      unlink($this->cache);
    }

    if (strpos($this->cache, 'Adept/Data/Record/') !== false) {
      $path = str_replace('Adept/Data/Record/', 'Adept/Data/Table/', $this->cache);

      // Delete Items cache files
      array_map('unlink', array_filter((array) glob($path . '*')));
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

    if (empty($this->cacheFile)) {
      if ($this->cacheFile = $this->id . '.php');
    }

    file_put_contents($this->cachePath . $this->cacheFile, $cache);
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
