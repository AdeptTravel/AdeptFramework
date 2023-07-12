<?php

namespace AdeptCMS\Traits;

defined('_ADEPT_INIT') or die();

/**
 * \AdeptCMS\Traits\Cache
 *
 * Functions to assit with viewing or manipulating the filesystem
 *
 * @package AdeptCMS
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */
trait Cache
{
  protected function saveCache()
  {
    if (!empty($this->cache)) {
      $path = FS_CACHE . str_replace("\\", '/', get_class($this)) . '/';
      $file = $path . $this->cache;

      if (!file_exists($path)) {
        mkdir($path, 0755, true);
      }

      file_put_contents($file, json_encode($this->data));
    }
  }

  protected function loadCache(): bool
  {
    $status = false;

    if (!empty($this->id)) {
      $path = FS_CACHE . str_replace("\\", '/', get_class($this)) . '/';
      $file = $path . $this->cache;

      if (file_exists($file)) {
        $this->data = json_decode(file_get_contents($file));
        $status = (isset($this->data) && is_object($this->data));
      }
    }

    return $status;
  }

  protected function clearCache(bool $directory = false)
  {
    $path = FS_CACHE . str_replace("\\", '/', get_class($this)) . '/';
    $file = $path . (($directory) ? '' : $this->cache);

    if (file_exists($file)) {
      unlink($file);
    }
  }
}
