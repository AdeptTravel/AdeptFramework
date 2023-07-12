<?php

/**
 * \AdeptCMS\Traits\FileSystem
 *
 * Functions to assit with viewing or manipulating the filesystem
 *
 * @package    AdeptCMS
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 3-Clause; See LICENSE.txt
 */

namespace AdeptCMS\Traits;

defined('_ADEPT_INIT') or die();

/**
 * \AdeptCMS\Traits\FileSystem
 *
 * Functions to assit with viewing or manipulating the filesystem
 *
 * @package    AdeptCMS
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 3-Clause; See LICENSE.txt
 */
trait FileSystem
{

  /**
   * Attempts to match the namespace to the corresponding file.
   *
   * @param string $namespace
   * @return string Namespace if matched, empty if not
   */
  public function matchNamespace(string $namespace): string
  {

    if (!empty($path = $this->convertNamespaceToPath($namespace))) {
      $path .= '.php';

      $namespace = '';

      $path = $this->matchPath($path);


      if (!empty($path)) {
        $namespace = $this->convertPathToNamespace($path);
      }
    }

    return $namespace;
  }

  public function matchPath(string $path): string|bool
  {
    $result = false;

    // Check for security violation

    if (strpos($path, FS_PATH) === false) {
      die();
    }

    if (!file_exists($path)) {
      $path = substr($path, strlen(FS_PATH) - 1);
      $path = substr($path, 1, strlen($path) - ((substr($path, -1) == '/') ? 2 : 1));
      $parts = explode('/', $path);
      $path = FS_PATH;

      foreach ($parts as $part) {
        if (empty($path = $this->matchDir($path, $part))) {
          break;
        }
      }

      if (!empty($path) && file_exists($path)) {
        $result = $path;
      }
    } else {
      $result = $path;
    }

    return $result;
  }
  /**
   * Match a string to it's case sensitive directory within a given path
   *
   * @param string $path
   * @param string $search
   * 
   * @return string Case sensitive match, empty if not found
   */
  public function matchDir(string $path, string $search): string
  {
    $result = '';

    if (file_exists($path)) {

      foreach (scandir($path) as $fs) {
        if ($fs == '.' || $fs == '..' || is_file($fs)) {
          continue;
        }

        $search = strtolower($search);
        $compare = strtolower($fs);

        if ($compare == $search) {
          $result = $path . $fs;

          if (is_dir($result)) {
            $result .= '/';
          }

          break;
        }
      }
    }

    return $result;
  }

  /**
   * Match a string to it's case sensitive file within a given path
   *
   * @param string $path
   * @param string $search
   * 
   * @return string Case sensitive match, empty if not found
   */
  public function matchFile(string $path, string $search): string
  {
    $result = '';

    if (file_exists($path)) {
      foreach (scandir($path) as $fs) {
        if ($fs == '.' || $fs == '..' || is_dir($path . $fs)) {
          continue;
        }

        $search = strtolower($search);
        $compare = strtolower($fs);

        if ($compare == $search) {
          $result = $path . $fs;

          break;
        }
      }
    }

    return $result;
  }

  /**
   * Matches a single asset based on a search params
   *
   * @param string $template  The template currently in use
   * @param string $search    The URI to match
   * @param string $area      The area to search in, ie. Admin or Site
   * @param string $type      Type of asset, ie CSS, JavaScrip, etc.
   * @return object|boolean   REturn either an object with details or false if not found
   * 
   */
  public function matchAsset(string $template, string $search, string $area, string $type): object|bool
  {

    $result = false;
    $component = '';

    if ($area == 'Admin') {
      $search = str_replace('admin/', '', $search);
    }

    // Searh order, Component - Media - Template - Assets
    // Media contains user uploaded content
    // Templates contain template assets, can be overriden by files in media
    // Assets contain core system assets which can be overriden by files in template or media

    // Convert search string into array of directories and the file
    $parts = explode('/', $search);

    if ($parts[1] == 'component') {
      $component = $parts[2];
      $parts = array_slice($parts, 2);
    }

    $file = urldecode(array_pop($parts));
    $path = implode('/', array_slice($parts, (($parts[0] == 'component') ? 2 : 1)));

    $paths = [];

    if (!empty($component)) {
      $paths[] = FS_COMPONENT . $component . '/' . $area . '/Asset/' . $type . '/' . $path;
    }

    $paths[] = FS_TEMPLATE . $template . '/' . $type . '/' . $path;

    if (!empty($area)) {
      $paths[] = FS_ASSET . $area . '/' . $type . '/' . $path;
    }

    $paths[] = FS_ASSET . 'Global/' . $type . '/' . $path;
    $paths[] = FS_ASSET . 'Public/' . $type . '/' . $path;

    foreach ($paths as $path) {
      $path = $this->matchPath($path);

      if (!empty($path) && $match = $this->findAsset($path, $file)) {
        $result = new \stdClass();
        $result->path = $path;
        $result->file = $match;

        break;
      }
    }

    return $result;
  }

  public function findAsset(string $path, string $search): string
  {
    $result = '';
    $search = strtolower($search);

    foreach (scandir($path) as $fs) {
      if ($fs == '.' || $fs == '..') {
        continue;
      }

      $compare = strtolower($fs);

      if (($compare == $search) || (substr($compare, 2, 1) == '-' && substr($compare, 3) == $search)) {

        $result = $path . $fs;

        if (is_dir($result)) {
          $result .= '/';
        }

        break;
      }
    }

    return $result;
  }

  /**
   * Saves a file, creating the directory structure if not currently available
   *
   * @param string $file The full absolute path and filename
   * @param string $data The data to save
   * 
   * @return void
   */
  public function saveFile(string $file, string $data)
  {
    $path = substr($file, 0, strrpos($file, '/'));

    if (!file_exists($path)) {
      mkdir($path, 0755, true);
    }

    file_put_contents($file, $data);
  }

  public function convertPathToNamespace(string $path): string
  {
    $namespace = '';
    //  s/inet/web/adept.travel/dev/Components/User/Admin/Model/Login.php
    $path = str_replace(FS_PATH, '', $path);
    $path = str_replace('.php', '', $path);
    $parts = explode('/', $path);

    if ($parts[0] == 'Library') {
      unset($parts[0]);
      $path = implode('/', $parts);
    }

    $namespace = "\\" . str_replace('/', "\\", $path);

    return $namespace;
  }

  /**
   * Converts a namespace to a path
   *
   * @param string $namespace
   * 
   * @return string
   */
  public function convertNamespaceToPath(string $namespace): string
  {
    $path = '';

    if (substr($namespace, 0, 1) == "\\") {
      $namespace = substr($namespace, 1);
    }

    if (substr($namespace, -1) == "\\") {
      $namespace = substr($namespace, 0, strlen($namespace) - 1);
    }

    $parts = explode("\\", $namespace);

    if (in_array($parts[0], [
      'AdeptCMS',
      'Component',
      'Event',
      'Extension',
      'Module',
      'Package',
      'Plugin'
    ])) {
      $path = FS_PATH . implode('/', $parts);
    }

    $path .= '/';

    return $path;
  }

  public function convertPathToClass(string $path): string
  {
    $namespace = $this->convertPathToNamespace($path);
    $parts = explode("\\", $namespace);
    return $parts[count($parts) - 1];
  }
}
