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
class Redirect extends \Adept\Abstract\Data\Item
{
  protected string $table = 'Redirect';
  protected string $index = 'route';

  protected array $uniqueKeys = [
    'route'
  ];

  /**
   * The route
   *
   * @param string
   */
  public string $route = '';

  /**
   * Redirect to
   *
   * @var string
   */
  public string $redirect = '';


  /**
   * The HTTP Status code, 301 - Permenent or 302 - Temprorary
   *
   * @var int
   */
  public int $code = 301;

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

    return parent::save();
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
