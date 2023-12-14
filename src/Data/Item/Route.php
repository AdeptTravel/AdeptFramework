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

defined('_ADEPT_INIT') or die();

use \Adept\Application\Database;

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

  protected string $name = 'Route';
  protected string $table = 'route';

  /**
   * The route
   *
   * @param string
   */
  public string $route = '';

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $redirect = '';

  public string $category = '';

  /**
   * The component
   *
   * @var string
   */
  public string $component = '';

  /**
   * The component option aka what area of the component should be loaded
   *
   * @var string
   */
  public string $option = '';

  /**
   * The view template
   *
   * @var string
   */
  public string $template = '';

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
  public bool $get = false;

  /**
   * Allow post data
   *
   * @var bool
   */
  public bool $post = false;

  /**
   * Allow the route to send emails
   *
   * @var bool
   */
  public bool $email = false;

  /**
   * Is the route publically viewable
   *
   * @var bool
   */
  public bool $public = false;

  /**
   * State of the route, ie. Published|Unpublished
   *
   * @param int
   */
  public int $status = 0;

  /**
   * The route is in the block list
   *
   * @param bool
   */
  public bool $block = false;

  protected function isDuplicate(string $table = ''): bool
  {
    return ($this->db->getInt(
      'SELECT `id` FROM `route` WHERE `route` = ?',
      [$this->route]
    ) > 0);
  }

  public function formatSegment(string $segment): string
  {
    $segment = strtolower($segment);
    $segment = preg_replace('/[^0-9a-z-]/', '-', $segment);
    $segment = str_replace('--', '-', $segment);

    $parts = explode('-', $segment);
    $count = count($parts);

    for ($i = 0; $i < $count; $i++) {
      if (empty($parts[$i])) {
        unset($parts[$i]);
      }
    }

    $segment = implode('-', $parts);

    return $segment;
  }
}
