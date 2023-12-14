<?php

/**
 * \Adept\Application\Session\Data
 *
 * @package Adept
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Application\Session;

defined('_ADEPT_INIT') or die();

use \Adept\Application\Session\Data\Client;
use \Adept\Application\Session\Data\Server;

/**
 * \Adept\Application\Session\Data
 *
 * @package Adept
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */
class Data
{
  /**
   * Undocumented variable
   *
   * @var \Adept\Application\Session\Data\Client
   */
  public Client $client;

  /**
   * Undocumented variable
   *
   * @var \Adept\Application\Session\Data\Server
   */
  public Server $server;

  public function __construct()
  {
    $this->client = new Client();
    $this->server = new Server();
  }
}
