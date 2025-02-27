<?php

/**
 * \Adept\Application\Session
 *
 * Stores information about the current session
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc.,
 *              All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 * @version    1.0.0
 */

namespace Adept\Application;

// Prevent direct access to the script
defined('_ADEPT_INIT') or die();

use \Adept\Application\Session\Authentication;
use \Adept\Application\Session\Data;
use \Adept\Application\Session\Request;

/**
 * \Adept\Application\Session
 *
 * Manages session information, authentication, and request handling
 *
 * @package    AdeptFramework
 * @author     Brandon J.
 *              Yaniz (brandon@adept.travel)
 * @copyright  2021-2024
 *              The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 3-Clause; See LICENSE.txt
 * @version    1.0.0
 */
class Session
{
  /**
   * Reference to the Authentication object
   *
   * @var \Adept\Application\Session\Authentication
   */
  public Authentication $auth;

  /**
   * Holds session data
   *
   * @var \Adept\Application\Session\Data
   */
  public Data $data;

  /**
   * Session ID
   *
   * @var int
   */
  public int $id = 0;

  /**
   * The request object
   *
   * @var \Adept\Application\Session\Request
   */
  public Request $request;

  /**
   * Session token used for apps and persistent login sessions
   *
   * @var string
   */
  public string $token = '';

  /**
   * Session status (e.g., 'Allow' or 'Block')
   *
   * @var string
   */
  public string $status;

  /**
   * Constructor
   *
   * Initializes session handling, authentication, and request processing.
   */
  public function __construct()
  {
    // Start the PHP session
    session_start();

    // Initialize session data and authentication
    $this->data  = new Data();
    $this->auth  = new Authentication($this->data);

    // Get the session token or use the PHP session ID
    $this->token = $this->data->server->getString('session.token', session_id(), 32);

    // Retrieve the session ID from server data
    $this->id = $this->data->server->getInt('session.id', 0);

    if ($this->id == 0) {
      // Create a new session item
      $session = new \Adept\Data\Item\Session();

      if (!$session->loadFromIndex($this->token)) {
        // Set the session token
        $session->token = $this->token;

        if ($session->save()) {
          // Update session ID and store in server data
          $this->id = $session->id;
          $this->data->server->set('session.id', $this->id);
          $this->data->server->set('session.token', $this->token);
          $this->data->server->set('session.status', 'Allow');

          // Initialize the request object with the session ID
          $this->request = new Request($this->id);
        } else {
          // Terminate execution if session cannot be saved
          die('Session Error');
          // Alternative error handling can be implemented here
        }
      } else {
        // Session already exists; set the session ID
        $this->id = $session->id;
      }
    }

    // Initialize the request object
    $this->request = new Request($this->id);

    // Retrieve the last session timestamp
    $time = $this->data->server->getInt('session.timestamp', 0, 11);

    $count = $this->data->server->getInt('session.count', 0);
    $count++;

    $this->data->server->set('session.count', $count);

    // Check for session timeout (default is 20 minutes)
    // TODO: Make timeout duration configurable
    if (
      $this->auth->user->id > 0
      && empty($this->token)
      && time() > ($time + (20 * 60))
    ) {
      // Reset user authentication
      $this->data->server->set('auth.userid', 0);

      // Prepare redirection URL
      $redirect = (!empty($this->request->url->path))
        ? '?redirect=' . $this->request->url->path
        : '';

      // Redirect to the login page
      $this->request->redirect('/login' . $redirect, 302);
    }

    // Update session timestamp and token
    $this->data->server->set('session.timestamp', time());
    $this->data->server->set('session.token', $this->token);

    // Retrieve session status
    $this->status = $this->data->server->getString('session.status');

    // If the route is secure and the user is not authenticated, redirect to login
    if ($this->request->route->isSecure && !$this->auth->status) {
      // Prepare redirection URL
      $redirect = (!empty($this->request->url->path))
        ? '?redirect=' . $this->request->url->path
        : '';

      // Redirect to the login page
      $this->request->redirect('/login' . $redirect, false);
    }
  }

  /**
   * Set the session status to 'Block'
   *
   * Blocks the current session to prevent further access.
   *
   * @return void
   */
  public function setBlock()
  {
    if ($this->data->server->getString('session.status') != 'Block' && $this->id > 0) {
      // Load the session item and update its status
      $session = new \Adept\Data\Item\Session();
      $session->loadFromID($this->id);
      $session->status = 'Block';
      $session->save();
    }
  }
}
