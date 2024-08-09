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

  protected string $indexCol = 'title';

  public string $title = '';
  public string $css = '';
  public bool   $secure = false;

  /**
   * The status of the data object: published, unpublished, trashed, lost, archived, etc.
   *
   * @var int
   */
  public int $status = 1;

  public string $created;
}
