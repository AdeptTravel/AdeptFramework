<?php

namespace Adept\Application\Session;

// Prevent direct access to the script
defined('_ADEPT_INIT') or die();

use \Adept\Application\Session\Data\Client;
use \Adept\Application\Session\Data\Server;

/**
 * \Adept\Application\Session\Data
 *
 * Manages session data, including client and server data stores.
 *
 * @package    Adept
 * @author     Brandon J.
 * Yaniz (brandon@adept.travel)
 * @copyright  2021-2024
 * The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 * @version    1.0.0
 */
class Data
{
  /**
   * Client-side session data
   *
   * @var \Adept\Application\Session\Data\Client
   */
  public Client $client;

  /**
   * Server-side session data
   *
   * @var \Adept\Application\Session\Data\Server
   */
  public Server $server;

  /**
   * Constructor
   *
   * Initializes the client and server data stores.
   */
  public function __construct()
  {
    $this->client = new Client();
    $this->server = new Server();
  }
}
