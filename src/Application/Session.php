<?php

/**
 * \Adept\Application\Session
 *
 * Stores information about the current session
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
 * \Adept\Application\Session
 *
 * Stores information about the current session
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 3-Clause; See LICENSE.txt
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
   * Undocumented variable
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
   * Session Token is used for apps, and when user doesn't want to be logged out
   *
   * @var string
   */
  public string $token = '';

  public string $status;

  /**
   * Constructor
   *
   * @param \Adept\Application\Database $db
   * @param \Adept\Abstract\Configuration $conf
   */
  public function __construct()
  {
    session_start();

    $this->data   = new Data();
    $this->auth   = new Authentication($this->data);
    $this->token  = $this->data->server->getString('session.token', session_id(), 32);

    $this->id = $this->data->server->getInt('session.id', 0);

    if ($this->id == 0) {
      $session = new \Adept\Data\Item\Session();

      if (!$session->loadFromIndex($this->token)) {
        //$session->userId = $this->auth->user->id;
        $session->token = $this->token;

        if ($session->save()) {

          $this->id = $session->id;

          $this->data->server->set('session.id', $this->id);
          $this->data->server->set('session.token', $this->token);
          $this->data->server->set('session.status', 'Allow');

          $this->request = new Request($this->id);
        } else {
          die('Session Error');
          //\Adept\error(debug_backtrace(), 'Session ID', 'No Session ID is available');
        }
      } else {
        $this->id = $session->id;
      }
    }

    $this->request = new Request($this->id);

    $time = $this->data->server->getInt('session.timestamp', 0, 11);

    // Check for timeout, which is 20 minutes
    // TODO: Set the timeout time in the config file
    if (
      $this->auth->user->id > 0
      && empty($this->token)
      && time() > ($time + (20 * 60))
    ) {

      $this->data->server->set('auth.userid', 0);

      $redirect = (!empty($this->request->url->path))
        ? '?redirect=' . $this->request->url->path
        : '';

      $this->request->redirect('/login' . $redirect, 302);
    }

    $this->data->server->set('session.timestamp', time());
    $this->data->server->set('session.token', $this->token);

    $this->status   = $this->data->server->getString('session.status');

    if ($this->request->route->isSecure && !$this->auth->status) {

      $redirect = (!empty($this->request->url->path))
        ? '?redirect=' . $this->request->url->path
        : '';

      $this->request->redirect('/login' . $redirect, false);
    }
  }

  public function setBlock()
  {
    if ($this->data->server->getString('session.status') != 'Block' && $this->id > 0) {
      $session = new \Adept\Data\Item\Session();
      $session->loadFromID($this->id);
      $session->status = 'Block';
      $session->save();
    }
  }
}
