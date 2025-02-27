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

use Adept\Application;
use Adept\Data\Item\Url;

defined('_ADEPT_INIT') or die();

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
    $conf = Application::getInstance()->conf;
    // Clean the extension off the path
    $route = $url->path;
    $ext   = empty($url->extension) ? 'html' : $url->extension;
    $len   = strlen($ext);

    if (substr($route, -$len) == $ext) {
      $route = substr($route, 0, - (strlen($ext) + 1));
    }

    //if ($this->loadFromIndex($route)) {
    if ($this->loadFromIndexes(['host' => $url->host, 'route' => $route])) {

      if (
        !($this->area != $conf->site->area || $this->area != 'Global') ||
        (!(!in_array($this->type, $conf->site->type) || $this->type != 'Core')) ||
        !isset($this->$ext) ||
        !$this->$ext
      ) {

        $this->status = 'Block';
      }
    }

    if ($this->html && empty($this->template)) {
      $this->template = \Adept\Application::getInstance()->conf->site->template;
    }
  }
}
