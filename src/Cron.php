<?php

/**
 * \Adept\Application
 *
 * The application object
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace Adept;

defined('_ADEPT_INIT') or die();

require_once('Global.php');

use \Adept\Abstract\Configuration;
use \Adept\Application\Database;
use \Adept\Application\Session;

/**
 * \Adept\Application
 *
 * The application object
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Cron
{
  /**
   * The configuration object
   * 
   * @var \Adept\Abstract\Configuration;
   */
  public Configuration $conf;

  public \anlutro\cURL\cURL $cURL;

  /**
   * The database object
   *
   * @var \Adept\Application\Database
   */
  public Database $db;

  /**
   * Constructor
   *
   * @param \Adept\Abstract\Configuration $conf
   */
  public function __construct(Configuration $conf)
  {
    $this->db = new Database($conf);
    $this->conf = $conf;
    $this->cURL = new \anlutro\cURL\cURL();
    $this->cURL->setDefaultHeaders([
      'User-Agent' => 'Adept Travel Framework'
    ]);
  }
}
