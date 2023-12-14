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

use \Adept\Abstract\Configuration;
use \Adept\Application\Session\Authentication;
use \Adept\Application\Database;
use \Adept\Application\Session\Data;
use \Adept\Application\Session\Request;
use \Adept\Data\Item\Url;
use \Adept\Data\Item\User;
use \Adept\Data\Item\UserAgent;

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
   * Reference to the database object
   *
   * @var \Adept\Application\Database
   */
  protected Database $db;

  /**
   * Reference to the Authentication object
   *
   * @var \Adept\Application\Session\Authentication
   */
  public Authentication $auth;

  /**
   * Is the session blocked
   *
   * @var boolean
   */
  public $block = false;

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

  /**
   * Constructor
   *
   * @param \Adept\Application\Database $db
   * @param \Adept\Abstract\Configuration $conf
   */
  public function __construct(Database &$db, Configuration &$conf)
  {
    session_start();

    $this->db     = $db;
    $this->data   = new Data();
    $this->auth   = new Authentication($db, $this->data);
    $this->token  = $this->data->server->getString('session.token', '', 32);

    $id   = $this->data->server->getInt('session.id', 0);

    if ($id == 0) {
      $id = $db->insertSingleTableGetId(
        'session',
        [
          'user' => $this->auth->user->id,
          'token' => $this->token
        ]
      );

      if ($id !== false) {
        $this->id = $id;
        $this->data->server->set('session.id', $id);
        $this->data->server->set('session.block', false);
      } else {
        \Adept\error(debug_backtrace(), 'Session ID', 'No Session ID is available');
      }
    } else {

      $time = $this->data->server->getInt('session.timestamp', 0, 11);

      // Check for timeout, which is 20 minutes
      // TODO: Set the timeout time in the config file
      if (
        $this->auth->user->id > 0
        && empty($this->token)
        && time() > ($time + (20 * 60))
      ) {
        $url = new Url($db);

        $this->data->server->set('auth.userid', 0);
        $this->data->server->set('redirect', $url->path);
        header('Location: /login', true);
        die();
      }
    }

    $this->data->server->set('session.timestamp', time());
    $this->data->server->set('session.token', $this->token);

    $this->request = new Request($db, $conf, $this->id);
    $this->block = $this->data->server->getBool('session.block');

    if (!$this->request->route->public && !$this->auth->status) {

      $redirect = (!empty($this->request->url->path))
        ? '?redirect=' . $this->request->url->path
        : '';

      $this->request->redirect('/login' . $redirect, false);
    }

    //
    // Security Checks
    //

    if (
      !$this->request->route->get
      || ($this->request->route->get
        && !$this->request->route->public
        && !$this->auth->status)
    ) {
      $this->request->data->get->purge();
    }

    if (
      !$this->request->route->post
      || ($this->request->route->post
        && !$this->request->route->public
        && !$this->auth->status)
    ) {
      $this->request->data->post->purge();
    }
  }

  public function setBlock(bool $block)
  {
    if ($this->data->server->getBool('session.block') != $block && $this->id > 0) {
      $this->data->server->set('session.block', $block);

      $this->db->update(
        'UPDATE `session` SET `block` = ? WHERE `id` = ?',
        [$block, $this->id]
      );
    }
  }
}
