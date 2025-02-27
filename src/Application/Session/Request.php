<?php

namespace Adept\Application\Session;

// Prevent direct access to the script
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
 * @package    Adept
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 * @version    1.0.0
 */
class Request
{
  /**
   * Unique identifier for the request
   *
   * @var int
   */
  public int $id;

  /**
   * The current session data
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
   * The IP address object for the current request
   *
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
   * The route object for the current request
   *
   * @var \Adept\Application\Session\Request\Route
   */
  public Route $route;

  /**
   * The redirect object for the current request
   *
   * @var \Adept\Application\Session\Request\Redirect
   */
  public Redirect $redirect;

  /**
   * HTTP status code of the response
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
  public string $createdAt;

  /**
   * The user agent used for the current request
   *
   * This is in Request and not Session because we have "don't log me out" features and app integration.
   * As browsers and apps get updated, we can track the changes over time.
   * TODO: Use this data to verify that the browser family and OS haven't changed; if so, set a session block.
   *
   * @var \Adept\Data\Item\Useragent
   */
  public \Adept\Data\Item\Useragent $useragent;

  /**
   * Constructor
   *
   * Initializes the Request object with session information and processes the incoming request.
   *
   * @param int $session The current session ID
   */
  public function __construct(int $session)
  {
    // Set the current session ID
    $this->sessionId = $session;
    // Initialize the URL object
    $this->url = new Url(true);
    // Initialize the IP address object
    $this->ipAddress = new IPAddress(true);
    // Initialize the user agent object
    $this->useragent = new Useragent(true);
    // Initialize the route object based on the URL
    $this->route = new Route($this->url);
    // Initialize session data
    $this->data = new Data();

    // Create a new Request data item
    $this->request = new \Adept\Data\Item\Request();
    $this->request->sessionId = $this->sessionId;
    $this->request->ipAddressId = $this->ipAddress->id;
    $this->request->useragentId = $this->useragent->id;
    $this->request->urlId = $this->url->id;

    if (empty($this->route->id)) {
      // No matching route found, attempt to handle as a redirect
      $this->redirect = new Redirect($this->url);

      if ($this->redirect->id > 0) {
        // Redirect exists, set HTTP status code and redirect
        $this->code = $this->redirect->code;
        $this->request->redirectId = $this->redirect->id;
        $this->redirect($this->redirect->redirect, $this->code);
      } else {
        // No redirect found, set status to 404 Not Found
        $this->setStatus(404);
        $this->data->get->purge();
        $this->data->post->purge();
      }
    } else {
      // Route found, process request
      $this->request->routeId = $this->route->id;
      $this->data = new Data();
      $this->code = 200;

      //
      // Security Checks
      //
      if (!$this->route->allowGet) {
        // GET requests are not allowed for this route; purge GET data
        $this->data->get->purge();
      }

      if (!$this->route->allowPost) {
        // POST requests are not allowed for this route; purge POST data
        $this->data->post->purge();
      }
    }
  }

  /**
   * Destructor
   *
   * Saves the request data when the object is destroyed.
   */
  public function __destruct()
  {
    $this->save();
  }

  /**
   * Redirects the request to a new URL
   *
   * @param string $url  The URL to redirect to
   * @param int    $code The HTTP status code for the redirection (default is 301)
   *
   * @return void
   */
  public function redirect(string $url, $code = 301)
  {
    // Save the request data before redirecting
    $this->save();
    // Set the HTTP response code
    http_response_code($this->code);
    // Send the redirect header
    header('Location:' . $url, true, $code);
    // Terminate the script
    die();
  }

  /**
   * Sets the HTTP status code for the response
   *
   * @param int $status The HTTP status code to set
   *
   * @return void
   */
  public function setStatus(int $status)
  {
    $this->code = $status;

    if ($status != 200) {
      // Update the route to point to the error component and view
      $this->route->component = 'Error';
      $this->route->view = $status;

      // Set the HTTP response code
      http_response_code($status);
    }
  }

  /**
   * Saves the request data to the database
   *
   * @return void
   */
  public function save()
  {
    // Set the HTTP status code in the request data
    $this->request->code = $this->code;

    // Determine the request status based on the HTTP code
    if ($this->code == 200) {
      $this->request->status = 'Active';
    } elseif ($this->code == 403) {
      $this->request->status = 'Block';
    } else {
      $this->request->status = 'Error';
    }

    // Save the request data item
    $this->request->save();
  }
}
