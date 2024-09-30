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

use Adept\Application\Session\Request\Data;
use Adept\Application\Session\Request\Redirect;
use Adept\Application\Session\Request\Route;
use Adept\Data\Item\IPAddress;
use Adept\Data\Item\Url;
use Adept\Data\Item\Useragent;

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

  public int $id;

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
  protected int $sessionId;

  /**
   * @var \Adept\Data\Item\IPAddress
   */
  public IPAddress $ipAddress;


  /**
   * A reference to the Request data item
   *
   * @var \Adept\Data\Item\Request
   */
  public \Adept\Data\Item\Request $request;

  /**
   * @var \Adept\Application\Session\Request\Route
   */
  public Route $route;

  /**
   *  @var \Adept\Application\Session\Request\Redirect
   */
  public Redirect $redirect;

  /**
   * HTTP Status Code
   * 
   * @var int
   */
  public string $code;

  /**
   * The URL the request came in on
   *
   * @var \Adept\Data\Item\Url
   */
  public Url $url;

  /**
   * Date and time the record was created
   *
   * @var string
   */
  public string $createdOn;

  /**
   * The useragent used for the current request.  This is in Request and not
   * Session because we have don't log me out features and app integration.
   * As browsers and apps get updated we can track the changes over time.
   * TODO: Use this data to verify that the browser family and OS hasn't
   *       changed, if so set a session block.
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
    $this->sessionId  = $session;
    $this->url        = new Url(true);
    $this->ipAddress  = new IPAddress(true);
    $this->useragent  = new Useragent(true);
    $this->route      = new Route($this->url);
    $this->data       = new Data();

    $this->request              = new \Adept\Data\Item\Request();
    $this->request->sessionId   = $this->sessionId;
    $this->request->ipAddressId = $this->ipAddress->id;
    $this->request->useragentId = $this->useragent->id;
    $this->request->urlId       = $this->url->id;

    if ($this->route->id == 0) {
      $this->redirect = new Redirect($this->url);

      if ($this->redirect->id > 0) {
        $this->code = $this->redirect->code;
        $this->request->redirectId = $this->redirect->id;
        $this->redirect($this->redirect->redirect, $this->code);
      } else {
        $this->setStatus(404);
        $this->data->get->purge();
        $this->data->post->purge();
      }
    } else {
      $this->request->routeId = $this->route->id;
      $this->data = new Data();
      $this->code = 200;

      //
      // Security Checks
      //
      if (!$this->route->allowGet) {
        $this->data->get->purge();
      }

      if (!$this->route->allowPost) {
        $this->data->post->purge();
      }
    }
  }

  public function __destruct()
  {
    $this->save();
  }

  public function redirect(string $url, $code = 301)
  {
    $this->save();
    http_response_code($this->code);
    header('Location:' . $url, true, $code);
    die();
  }

  public function setStatus(int $status)
  {
    $this->code = $status;

    if ($status != 200) {

      $this->route->component = 'Error';
      $this->route->view = $status;

      http_response_code($status);
    }
  }

  public function save()
  {
    $this->request->code = $this->code;

    if ($this->code == 200) {
      $this->request->status = 'Active';
    } else if ($this->code == 403) {
      $this->request->status = 'Block';
    } else {
      $this->request->status = 'Error';
    }

    $this->request->save();
  }
}
