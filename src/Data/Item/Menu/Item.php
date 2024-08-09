<?php

/**
 * \Adept\Data\Item\Menu\Item
 *
 * The menu item data item
 *
 * @package    AdeptFramework.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Data\Item\Menu;

defined('_ADEPT_INIT') or die();

use \Adept\Application\Database;
use \Adept\Application\Session\Request\Data\Post;

/**
 * \Adept\Data\Item\Menu\Item
 *
 * The menu item data item
 *
 * @package    AdeptFramework.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Item extends \Adept\Abstract\Data\Item
{
  public int    $menu;
  public int    $parent = 0;
  public int    $route;
  public string $url = '';
  public string $title = '';
  public string $image = '';
  public string $imageAlt = '';
  public string $fa = '';
  public string $css = '';
  public string $params = '';
  public int    $order = 0;
  public int    $status = 0;

  public \DateTime $created;

  public function getMenu(): \Adept\Data\Item\Menu
  {
    return new \Adept\Data\Item\Menu($this->menu);
  }

  public function getParent(): \Adept\Data\Item\Menu\Item
  {
    return new \Adept\Data\Item\Menu\Item($this->parent);
  }

  public function getRoute(): \Adept\Data\Item\Route
  {
    return new \Adept\Data\Item\Route($this->route);
  }
}
