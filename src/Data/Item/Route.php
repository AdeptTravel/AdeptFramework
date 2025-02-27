<?php

/**
 * \Adept\Data\Item\Route
 *
 * The route data item
 *
 * @package    AdeptFramework.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Data\Item;

use Adept\Application;

defined('_ADEPT_INIT') or die();

/**
 * \Adept\Data\Item\Route
 *
 * The route data item
 *
 * @package    AdeptFramework.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Route extends \Adept\Abstract\Data\Item
{
  protected string $table = 'Route';
  protected string $index = 'route';

  protected array  $uniqueKeys = [
    'route'
  ];

  /**
   * The host or domain name associated with the route
   *
   * @var string
   */
  public string $host;

  /**
   * The route
   *
   * @param string
   */
  public string $route;

  public string $type;

  /**
   * The component
   *
   * @var string
   */
  public string $component;

  public string $area;

  /**
   * The component option aka what area of the component should be loaded
   *
   * @var string
   */
  public string $view;

  /**
   * The view template
   *
   * @var string
   */
  public string $template;

  /**
   * Allow access to the HTML component
   *
   * @var bool
   */
  public bool $html = false;

  /**
   * Allow access to the JSON component
   *
   * @var bool
   */
  public bool $json = false;

  /**
   * Allow access to the XML component
   *
   * @var bool
   */
  public bool $xml = false;

  /**
   * Allow access to the CSV component
   *
   * @var bool
   */
  public bool $csv = false;

  /**
   * Allow access to the PDF component
   *
   * @var bool
   */
  public bool $pdf = false;

  /**
   * Allow access to the ZIP component
   *
   * @var bool
   */
  public bool $zip = false;

  /**
   * Is the route in the sitemap
   *
   * @param bool
   */
  public bool $sitemap = false;

  /**
   * Allow get data
   *
   * @var bool
   */
  public bool $allowGet = false;

  /**
   * Allow post data
   *
   * @var bool
   */
  public bool $allowPost = false;

  /**
   * Allow the route to send emails
   *
   * @var bool
   */
  public bool $allowEmail = false;

  /**
   * Is the route publically viewable
   *
   * @var bool
   */
  public bool $isSecure = false;

  /**
   * Can the route be cached
   *
   * @var bool
   */
  public bool $isCacheable = false;

  public array $params;

  /**
   * State of the route, ie. Published|Unpublished
   *
   * @param int
   */
  public string $status = 'Active';

  public function save(): bool
  {
    // Remove the / from the begining and end of a string
    $this->route = trim($this->route, '/');

    if (
      !isset($this->originalData->route) ||
      (isset($this->originalData->route) && $this->originalData->route != $this->route)
    ) {
      // Route changed or new route
      //die('<pre>' . print_r($this, true));

      if (empty($this->route)) {
        // Route is empty
        $this->setError('Failed', 'The route is empty');
      } else if ($this->routeExists($this->route)) {
        // Route or redirect already exists
        $this->setError('Failed', 'The route already exists.');
      }
    }

    return parent::save();
  }

  public function formatSegment(string $segment): string
  {
    // Step 1: Convert to lower case
    $segment = strtolower($segment);
    // Step 2: Remove all symbols except for the dash
    $segment = preg_replace('/[^a-z0-9\s\-]/', '', $segment);

    // Step 3: Replace spaces with a single minus sign
    $segment = preg_replace('/\s+/', '-', $segment);

    // Step 4: Ensure there are no consecutive minus signs
    $segment = preg_replace('/-+/', '-', $segment);

    // Step 5: Trim leading or trailing minus signs
    $segment = trim($segment, '-');

    return $segment;
  }

  public function routeExists(string $route): bool
  {
    $db = Application::getInstance()->db;

    $id = $db->getInt("SELECT id FROM Route WHERE route = ?", [$route]);

    if ($id == 0) {
      $id = $db->getInt("SELECT id FROM Redirect WHERE route = ?", [$route]);
    }

    return ($id > 0);
  }
}
