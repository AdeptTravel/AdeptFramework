<?php

namespace AdeptCMS\Traits;

defined('_ADEPT_INIT') or die();

/**
 * \AdeptCMS\Traits\Route
 *
 * Methods for use when routing
 *
 * @package AdeptCMS
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */
trait Route
{
  /**
   * Route Exists
   *
   * @param string $namespace The namespace to validate
   * @return boolean
   */
  function routeExists(string $namespace): bool
  {
    $parts = explode('\\', $namespace);
    $type = $parts[1];

    unset($parts[0]);
    unset($parts[1]);

    $path = implode('/', $parts);
    unset($parts);

    switch ($type) {
      case 'Component':
        $file = FS_COMPONENT;
        break;

      case 'Event':
        $file = FS_EVENT;
        break;

      case 'Extension':
        $file = FS_EXTENSION;
        break;

      case 'Library':
        $file = FS_LIBRARY;
        break;

      case 'Module':
        $file = FS_MODULE;
        break;

      case 'Package':
        $file = FS_PACKAGE;
        break;

      case 'Plugin':
        $file = FS_PLUGIN;
        break;

      case 'AdeptCMS':
        $file = FS_LIBRARY . 'AdeptCMS/';
        break;

      default:
        // ERROR
        break;
    }

    $file .= $path . '.php';

    return file_exists($file);
  }
}
