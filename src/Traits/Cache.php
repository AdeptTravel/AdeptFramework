<?php

namespace Adept\Trait;

use \Adept\Application;

defined('_ADEPT_INIT') or die();

trait Cache
{

  protected array $cacheExclude = [];
  protected int $cachePropertyType = \ReflectionProperty::IS_PUBLIC;


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

    if (Application::getInstance()->conf->system->cache) {

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

      // Delete all table cache files related the the database table of this item
      $path = str_replace("Data/Item/", "Data/Table/", $path);

      array_map([$this, 'cacheDelete'], array_filter((array) glob($path . '*')));
    }
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

  protected function getData(bool $sql = true): object
  {
    $data       = new \stdClass();
    $reflection = new \ReflectionClass($this);
    $properties = $reflection->getProperties($this->cachePropertyType);

    $this->excludeKeys[] = 'error';

    for ($i = 0; $i < count($properties); $i++) {

      $key  = $properties[$i]->name;
      $type = $properties[$i]->getType();

      if ($key == 'id' && $this->$key == 0) {
        continue;
      }

      if (in_array($key, ['createdAt', 'data'])) {
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
              $data->$key = trim($this->$key);
            }

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

            $val = $data->$key = $this->$key->format('Y-m-d H:i:s');

            if ($this->$key->format('Y') != '-0001' && $val != '0000-01-01 00:00:00') {
              $data->$key = $val;
            } else {
              $data->$key = '0000-00-00 00:00:00';
            }

            break;

          default:

            if (strpos($type, "Adept\\Data\\") !== false) {
              //$this->$key->save();
              $data->$key = $this->$key->id;
            } else {
              // Encode object as JSON
              $data->$key = json_encode($this->$key);
            }

            break;
        }
      }
    }

    return $data;
  }
}
