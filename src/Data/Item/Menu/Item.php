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
  protected string $table = 'MenuItem';
  protected string $index = 'title';

  protected array $joinInner = [
    'Menu' => 'menuId'
  ];

  protected array $joinLeft = [
    'Route' => 'routeId',
    'Url' => 'urlId'
  ];

  public int    $menuId;
  public int    $parentId;
  public int    $routeId;
  public int    $urlId;
  public string $imageId;
  public string $type;
  public string $title;
  public string $css;
  public string $params;
  public string $activeOn;
  public int    $displayOrder;
}
