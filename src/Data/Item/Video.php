<?php

/**
 * \AdeptCMS\Data\Item\Video
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
 * \AdeptCMS\Data\Item\Video
 *
 * The content data item
 *
 * @package    AdeptCMS.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2023 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Video extends \AdeptCMS\Base\Data\Item
{

  /**
   * Relative path and filename
   *
   * @var string
   */
  public $file;

  /**
   * Youtube ID
   *
   * @var string
   */
  public $youtube;

  /**
   * @var string
   */
  public $description;

  /**
   * MIME Type of video file
   *
   * @var [type]
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
   * File size of the video
   *
   * @var int
   */
  public $size;

  /**
   * The duration of the video in seconds.
   *
   * @var int
   */
  public $duration;

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
