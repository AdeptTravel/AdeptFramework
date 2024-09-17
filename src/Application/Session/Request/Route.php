<?php

/**
 * \Adept\Application\Session\Request\Route
 *
 * The route object
 *
 * @package    AdeptFramework.Application
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Application\Session\Request;

defined('_ADEPT_INIT') or die();

use \Adept\Data\Item\Url;

/**
 * \Adept\Application\Session\Request\Route
 *
 * The route object
 *
 * @package    AdeptFramework.Application
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Route extends \Adept\Data\Item\Route
{

  public function __construct(Url $url)
  {
    // Clean the extension off the path
    $route = $url->path;
    $ext   = empty($url->extension) ? 'html' : $url->extension;
    $len   = strlen($ext);

    if (substr($route, -$len) == $ext) {
      $route = substr($route, 0, - (strlen($ext) + 1));
    }

    if ($this->loadFromIndex($route)) {

      if (!isset($this->$ext) || !$this->$ext) {
        $this->block = true;
      }
    }

    if ($this->html && empty($this->template)) {
      $this->template = \Adept\Application::getInstance()->conf->site->template;
    }
  }
}
