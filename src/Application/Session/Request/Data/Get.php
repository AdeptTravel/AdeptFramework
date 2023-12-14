<?php

/**
 * \Adept\Application\Session\Request\Data\Get
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

use \Adept\Application\Session\Request\Route;

/**
 * \Adept\Application\Session\Request\Get
 *
 * Data used for the current request
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Get extends \Adept\Abstract\GetVars
{
  protected string $type = 'Get';
}
