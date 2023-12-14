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
class Route extends \Adept\Data\Item\Route
{
  public function __construct(Database &$db, Configuration &$conf, Url $url)
  {
    parent::__construct($db, $url->path);

    if (empty($this->template)) {
      $this->template = $conf->site->template;
    }
  }
}
