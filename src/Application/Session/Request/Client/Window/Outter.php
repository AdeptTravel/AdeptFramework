<?php

/**
 * \Adept\Application\Session\Request\Route
 *
 * The route object
 *
 * @package    AdeptFramework.Application
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Application\Session\Request;

defined('_ADEPT_INIT') or die();

use \Adept\Abstract\Configuration;
use \Adept\Application\Database;
use \Adept\Data\Item\Url;

/**
 * \Adept\Application\Session\Request\Route
 *
 * The route object
 *
 * @package    AdeptFramework.Application
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Client
{
  public int $width = 0;
  public int $height = 0;
}
