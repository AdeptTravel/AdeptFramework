<?php

/**
 * \Adept\Application\Session\Request\Data\Post
 *
 * Data used for the current request
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Application\Session\Request\Data;

defined('_ADEPT_INIT') or die();

/**
 * \Adept\Application\Session\Request\Data\Post
 *
 * Data used for the current request
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Post extends \Adept\Abstract\GetVars
{
  protected string $type = 'Post';
}
