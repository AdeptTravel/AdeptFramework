<?php

/**
 * \Adept\Data\Item\Menu
 *
 * The menu data item
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
 * \Adept\Data\Item\Menu
 *
 * The menu data item
 *
 * @package    AdeptFramework.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Menu extends \Adept\Abstract\Data\Item
{
  protected string $table = 'Module';

  public int       $id;
  public string    $template;
  public string    $area;
  public string    $module;
  public string    $title;
  public bool      $titleShow;
  public string    $css;
  public object    $params;
  public object    $conditions;      // ENUM('Active', 'Inactive', 'Trash') NOT NULL DEFAULT 'Active',
  public \DateTime $activeOn;
  public \DateTime $createdAt;
  public \DateTime $updatedAt;
  public int       $sortOrder;
}
