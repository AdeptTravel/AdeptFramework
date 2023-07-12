<?php

/**
 * \AdeptCMS\Data\Item\Image
 *
 * The content data item
 *
 * @package    AdeptCMS.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2023 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace AdeptCMS\Data\Item;

defined('_ADEPT_INIT') or die();

/**
 * \AdeptCMS\Data\Item\Image
 *
 * The content data item
 *
 * @package    AdeptCMS.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2023 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Image extends \AdeptCMS\Base\Data\Item
{

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $file;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $description;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public $caption;

  /**
   * The mime type of the image
   *
   * @var string
   */
  public $type;

  /**
   * @var int
   */
  public $width;

  /**
   * @var int
   */
  public $height;

  /**
   * @var string
   */
  public $created;

  /**
   * @var string
   */
  public $modified;

  /**
   * Undocumented variable
   *
   * @var int
   */
  public $status;
}
