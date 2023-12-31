<?php

/**
 * \Adept\Application\Session\Request
 *
 * Stores information about the current request
 *
 * @package Adept
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Application\Session;

defined('_ADEPT_INIT') or die();

use \Adept\Abstract\Configuration;
use \Adept\Application\Session\Request\Data;
use \Adept\Application\Database;
use \Adept\Data\Item\IPAddress;
use \Adept\Application\Session\Request\Route;
use \Adept\Data\Item\Url;
use \Adept\Data\Item\UserAgent;

/**
 * \Adept\Application\Session\Request
 *
 * Stores information about the current request
 *
 * @package Adept
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */
class Request
{
  /**
   * A reference to the site configuration
   *
   * @var \Adept\Abstract\Configuration
   */
  protected Configuration $conf;

  /**
   * @var \Adept\Application\Database
   */
  protected Database $db;

  /**
   * The current session id
   *
   * @var \Adept\Application\Session\Request\Data
   */
  public Data $data;

  /**
   * Current session ID
   *
   * @var int
   */
  protected int $session;

  /**
   * @var \Adept\Data\Item\IPAddress
   */
  public IPAddress $ip;

  /**
   * @var \Adept\Application\Session\Request\Route
   */
  public Route $route;

  /**
   * HTTP Status Code
   * 
   * @var int
   */
  public int $status;

  /**
   * The URL the request came in on
   *
   * @var \Adept\Data\Item\Url
   */
  public Url $url;

  public int $milisec = 0;

  /**
   * The useragent used for the current request.  This is in Request and not
   * Session because we have don't log me out features and app integration.
   * As browsers and apps get updated we can track the changes over time.
   * TODO: Use this data to verify that the browser family andOS hasn't
   *  changed, if so set a session block.
   *
   * @var \Adept\Data\Item\UserAgent
   */
  public \Adept\Data\Item\UserAgent $useragent;

  /**
   * Constructor
   *
   * @param \Adept\Application\Database $db
   * @param \Adept\Abstract\Configuration $conf
   */
  public function __construct(
    Database &$db,
    Configuration &$conf,
    int $session
  ) {

    $this->conf = $conf;
    $this->db = $db;
    $this->session = $session;
    $this->url = new Url($db);
    $this->ip = new IPAddress($db);
    $this->useragent = new UserAgent($db);
    $this->route = new Route($db, $conf, $this->url);
    $this->data = new Data();
    $this->status = 200;
    $this->milisec = floor((microtime(true) - time()) * 1000);

    //if (empty($this->route->route)) {
    if ($this->route->id == 0) {
      $this->setStatus(404);
    }
  }

  public function __destruct()
  {
    $query = "INSERT INTO `request`";
    $query .= '(`session`, `ipaddress`, `useragent`, `route`, `url`, `code`, `milisec`)';
    $query .= 'VALUES';
    $query .= '(?,?,?,?,?,?,?)';

    $params = [
      $this->session,
      $this->ip->id,
      $this->useragent->id,
      $this->route->id,
      $this->url->id,
      $this->status,
      $this->milisec
    ];

    $this->db->insert($query, $params);
  }

  public function redirect(string $url, bool $permanent = true)
  {
    $this->status = ($permanent) ? 301 : 302;
    $this->__destruct();
    http_response_code($this->status);
    header('Location:' . $url, true, $this->status);
    die();
  }

  public function setStatus(int $status)
  {
    $this->status = $status;

    if ($status != 200) {
      $this->route->category = 'Core';
      $this->route->component = 'Error';
      $this->route->option = $status;

      http_response_code($status);
      /*
      if ($this->format == 'HTML') {
        header('Location: https://' . $this->conf->site->url . '/' . $status);
      } else {
        \Adept\error(get_class($this), __FUNCTION__, __LINE__, 'Status Error');
        \Adept\error(debug_backtrace(), 'Status error');
      }
      */
    }
  }
}
