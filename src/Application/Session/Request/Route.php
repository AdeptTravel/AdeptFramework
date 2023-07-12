<?php

/**
 * \AdeptCMS\Application\Session\Request\Route
 *
 * The route object
 *
 * @package    AdeptCMS.Application
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace AdeptCMS\Application\Session\Request;

defined('_ADEPT_INIT') or die();

/**
 * \AdeptCMS\Application\Session\Request\Route
 *
 * The route object
 *
 * @package    AdeptCMS.Application
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Route extends \AdeptCMS\Data\Item\Route
{
  use \AdeptCMS\Traits\Asset;
  use \AdeptCMS\Traits\FileSystem;

  public function __construct(
    \AdeptCMS\Application\Database &$db,
    \AdeptCMS\Base\Configuration &$conf,
    \AdeptCMS\Data\Item\Url $url
  ) {

    parent::__construct($db, $url->path);

    if ($this->id == 0) {

      $this->route = $url->path;
      $this->area = ($url->parts[0] == 'admin') ? 'Admin' : 'Site';

      // Check if admin area, if so 
      if (
        $this->area == 'Admin'
        && count($url->parts) >= 2
        && $match = $this->matchPath(FS_COMPONENT . $url->parts[1] . '/'  . 'Admin')
      ) {

        $option = $url->parts[2];

        if (($pos = strpos($option, '.')) !== false) {
          $option = substr($option, 0, $pos);
        }

        $match = str_replace(FS_COMPONENT, '', $match);

        $this->type = 'Component';
        $this->component = substr(
          $match,
          0,
          strpos($match, '/')
        );

        $this->option = $option;

        $this->save();
      } else {
        //echo '<p>' . $this->area . '</p>';
        //echo '<p>' . count($url->parts) . '</p>';
        //echo '<p>' . $this->matchPath(FS_COMPONENT . $url->parts[1] . '/'  . 'Admin') . '</p>';

        die('Route error');
      }
    }
  }
}
