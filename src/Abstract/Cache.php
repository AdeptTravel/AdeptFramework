<?php

namespace Adept;

defined('_ADEPT_INIT') or die();

abstract class Cache
{


  protected $file;

  /**
   * The data object
   *
   * @var object
   */
  protected $data;

  /**
   * The path to store the cache file in
   *
   * @var string
   */
  protected $path;

  public function __construct(string $namespace)
  {
    $this->path = FS_CACHE . str_replace("\\", '/', $namespace) . '/';

    if (!file_exists($this->path)) {
      mkdir($this->path, 0755, true);
    }
  }

  public function getData(): object
  {
    return $this->data;
  }

  public function getJSON(): string
  {
    return json_encode($this->data);
  }

  protected function saveCache($file, $data)
  {
    switch ($this->format) {
      case 'json':
        $data = $this->getJSON();
        break;
      default:
        $data = $this->data;
        break;
    }
    $file = $this->path . $file;

    file_put_contents($file, $data);
  }

  protected function loadCache($file): string
  {
    $status = false;

    $file = $this->path . $file;

    if (!empty($file) && file_exists($file)) {
      $this->data = json_decode(file_get_contents($file));
      $status = (isset($this->data) && is_object($this->data));
    }

    return $status;
  }

  protected function clearCache()
  {
    if (!empty($this->file) && file_exists($this->file)) {
      unlink($this->file);
    }
  }
}
