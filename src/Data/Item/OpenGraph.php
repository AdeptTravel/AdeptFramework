<?php

/**
 * \AdeptCMS\Data\Item\OpenGraph
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
 * \AdeptCMS\Data\Item\OpenGraph
 *
 * The OpenGraph data item
 *
 * @package    AdeptCMS.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class OpenGraph extends \AdeptCMS\Base\Data\Item
{
  /**
   * Undocumented variable
   *
   * @var string
   */
  public $title;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $type;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $description;

  /**
   * Undocumented variable
   *
   * @var int
   */
  public $image;
}
