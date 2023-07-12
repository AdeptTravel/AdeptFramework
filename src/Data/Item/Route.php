<?php

/**
 * \AdeptCMS\Data\Item\Route
 *
 * The route data item
 *
 * @package    AdeptCMS.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace AdeptCMS\Data\Item;

defined('_ADEPT_INIT') or die();

/**
 * \AdeptCMS\Data\Item\Route
 *
 * The route data item
 *
 * @package    AdeptCMS.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Route extends \AdeptCMS\Base\Data\Item
{
  /**
   * The type of route ie. Compoennt|Redirect|Asset
   *
   * @param string
   */
  public $type = '';

  /**
   * The area, ie. Admin|Site
   *
   * @param string
   */
  public $area = '';

  /**
   * The route
   *
   * @param string
   */
  public $route = '';

  /**
   * The component
   *
   * @var string
   */
  public $component = '';

  /**
   * Undocumented variable
   *
   * @var int
   */
  public $itemid;

  /**
   * The component option aka what area of the component should be loaded
   *
   * @var string
   */
  public $option = '';

  /**
   * URL to redirect the request to
   *
   * @var string
   */
  public $redirect = '';

  public $robots = '';

  /**
   * Is the route in the sitemap
   *
   * @param bool
   */
  public $sitemap = false;

  /**
   * Status of the route, ie. Published|Unpublished
   *
   * @param bool
   */
  public $status = false;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $created;

  /**
   * The route is in the block list
   *
   * @param bool
   */
  public $block = false;

  public function __construct(\AdeptCMS\Application\Database $db, int|string $id = 0, object|null $obj = null)
  {
    $this->table = 'route';

    parent::__construct($db, $id, $obj);
  }

  public function isRoute(string $route): bool
  {
    return ($this->db->getInt(
      "SELECT COUNT(*) FROM route WHERE route = ?",
      [$route]
    ) == 1);
  }

  public function loadFromRoute(string $route)
  {
  }

  public function save(): bool
  {
    // Cleanup any unnecessary public variables before save
    if ($this->type == 'Component') {
      unset($this->redirect);
    } else if ($this->type == 'Redirect') {
      unset($this->component);
      unset($this->option);
    } else if ($this->type == 'Error') {
      unset($this->redirect);
    } else {
      die(get_class($this) . '::save() - Route type unsupported.<pre>' . print_r($this, true));
    }

    return parent::save();
  }
}
