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

use \Adept\Application\Session\Request\Data;
use \Adept\Data\Item\IPAddress;
use \Adept\Application\Session\Request\Route;
use \Adept\Data\Item\Url;
use \Adept\Data\Item\Useragent;

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
   * @var \Adept\Data\Item\Useragent
   */
  public \Adept\Data\Item\Useragent $useragent;

  /**
   * Construct
   *
   * @param  int $session - The current session id
   */
  public function __construct(int $session)
  {
    $this->session    = $session;
    $this->url        = new Url(true);
    $this->ip         = new IPAddress(true);
    $this->useragent  = new Useragent(true);
    $this->route      = new Route($this->url);
    $this->data       = new Data();
    $this->status     = 200;
    $this->milisec    = floor((microtime(true) - time()) * 1000);

    if (!empty($this->route->redirect)) {
      $this->redirect($this->route->redirect);
    }

    if ($this->route->id == 0) {
      $this->setStatus(404);
    }
  }

  public function __destruct()
  {
    $this->save();
  }

  public function redirect(string $url, bool $permanent = true)
  {
    $this->status = ($permanent) ? 301 : 302;
    $this->save();
    http_response_code($this->status);
    header('Location:' . $url, true, $this->status);
    die();
  }

  public function setStatus(int $status)
  {
    $this->status = $status;

    if ($status != 200) {

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

  public function save()
  {
    $request = new \Adept\Data\Item\Request();
    $request->session = $this->session;
    $request->ipaddress = $this->ip->id;
    $request->useragent = $this->useragent->id;
    $request->route = $this->route->id;
    $request->url = $this->url->id;
    $request->code = $this->status;
    $request->milisec = $this->milisec;
    $request->save();
  }
}
