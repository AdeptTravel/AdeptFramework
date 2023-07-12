<?php

/**
 * \AdeptCMS\Data\Item\Content\SEO
 *
 * The route data item
 *
 * @package    AdeptCMS.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace AdeptCMS\Data\Item\Content;

defined('_ADEPT_INIT') or die();

/**
 * \AdeptCMS\Data\Item\Content\SEO
 *
 * The route data item
 *
 * @package    AdeptCMS.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class SEO
{
  /**
   * Undocumented variable
   *
   * @var string
   */
  public $description;

  /**
   * Undocumented variable
   *
   * @var \AdeptCMS\Data\Item\Content\SEO\Twitter
   */
  public $twitter;

  /**
   * Undocumented variable
   *
   * @var \AdeptCMS\Data\Item\Content\SEO\OpenGraph
   */
  public $opengraph;
}
