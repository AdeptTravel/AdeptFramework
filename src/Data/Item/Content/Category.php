<?php

/**
 * \Adept\Data\Item\Content\Category
 *
 * The content category data item
 *
 * @package    AdeptFramework.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Data\Item\Content;

defined('_ADEPT_INIT') or die();

/**
 * \Adept\Data\Item\Content\Category
 *
 * The content category data item
 *
 * @package    AdeptFramework.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Category extends \Adept\Data\Item\Content
{
  protected string $table = 'Content';
  public string $type = 'Category';
}
