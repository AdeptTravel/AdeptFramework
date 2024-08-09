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

  protected string $table = 'Route';

  public array $pathways = [];

  public function __construct(Url $url)
  {
    // Clean the extension off the path
    $path = $url->path;
    $ext  = empty($url->extension) ? 'html' : $url->extension;
    $len  = strlen($ext);

    if (substr($path, -$len) == $ext) {
      $path = substr($path, 0, - (strlen($ext) + 1));
    }

    parent::__construct($path);

    $ext = empty($url->extension) ? 'html' : $url->extension;

    if ($this->id == 0 || !isset($this->$ext) || !$this->$ext) {
      $this->block = true;
    }

    if ($this->html && empty($this->template)) {
      $this->template = \Adept\Application::getInstance()->conf->site->template;
    }

    // Build pathway
    //$query = ''

    //SELECT b.title, a.route
    //FROM route AS a
    //INNER JOIN meta AS b on a.id = b.route;

    // TODO: Pull data from the meta table for each part of the path.  for
    // example if a path is /destinations/france/paris we would pull the meta
    // title for:
    //  /destinations
    //  /destinations/france
    //  /destinations/france/paris
    // 
    // This will all be assembeled into an array of objects with a title and
    // route for each object

  }
}
