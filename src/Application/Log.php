<?php

/**
 * \Adept\Application\Log
 *
 * Handles system logs
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Application;

defined('_ADEPT_INIT') or die();

use \Adept\Application\Session\Authentication;
use \Adept\Application\Session\Data;
use \Adept\Application\Session\Request;

/**
 * \Adept\Application\Log
 *
 * Handles system logs
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 3-Clause; See LICENSE.txt
 */
class Log
{
  public function __construct() {}

  protected function log(string $file, string $data)
  {
    file_put_contents(FS_LOG . $file, $data, FILE_APPEND);
  }

  public function logError() {}
  public function logWarning() {}
  public function logPost() {}
  public function logGet() {}

  public function logQuery(string $query, array $params, string $debug)
  {
    $this->log(
      'query.log',
      "\nDatabase Query: $query\nParams: " . print_r($params, true) . "\n\nDebug Query: $debug\n"
    );
  }
}
