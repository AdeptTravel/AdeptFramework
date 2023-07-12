<?php

/**
 * \AdeptCMS\Application\Session
 *
 * Stores information about the current session
 *
 * @package    AdeptCMS
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace AdeptCMS\Application;

defined('_ADEPT_INIT') or die();

/**
 * \AdeptCMS\Application\Session
 *
 * Stores information about the current session
 *
 * @package    AdeptCMS
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 3-Clause; See LICENSE.txt
 */
class Session
{
  /**
   * Reference to the database object
   *
   * @var \AdeptCMS\Application\Database
   */
  protected $db;

  /**
   * Reference to the Authentication object
   *
   * @var \AdeptCMS\Application\Session\Authentication
   */
  public $auth;

  /**
   * Session ID
   *
   * @var int
   */
  public $id = 0;

  /**
   * The request object
   *
   * @var \AdeptCMS\Application\Session\Request
   */
  public $request;

  /**
   * The UserAgent object
   *
   * @var \AdeptCMS\Data\Item\UserAgent
   */
  public $useragent;

  /**
   * Is the session blocked
   *
   * @var boolean
   */
  public $block = false;

  /**
   * Constructor
   *
   * @param \AdeptCMS\Application\Database $db
   * @param \AdeptCMS\Base\Configuration $conf
   */
  public function __construct(
    \AdeptCMS\Application\Database &$db,
    \AdeptCMS\Base\Configuration &$conf
  ) {

    session_start();

    $this->db = $db;
    $this->auth = new \AdeptCMS\Application\Session\Authentication($db);
    $this->useragent = new \AdeptCMS\Data\Item\UserAgent($db);
    $this->block = isset($_SESSION['session.block']);

    if (
      isset($_SESSION['session.id'])
      && (time() - (int)$_SESSION['session.timestamp']) < (20 * 60)
    ) {
      $this->id = $_SESSION['session.id'];
      $_SESSION['session.timestamp'] = time();
    } else {

      session_reset();

      $id = $db->insertSingleTableGetId(
        'session',
        [
          'user' => $this->auth->id,
          'useragent' => $this->useragent->id
        ]
      );

      if ($id !== false) {
        $this->id = $id;
        $_SESSION['session.id'] = $id;
        $_SESSION['session.block'] = false;
        $_SESSION['session.timestamp'] = time();
      } else {
        \AdeptCMS\error(get_class($this), __FUNCTION__, 'Session Failure');
      }
    }

    $this->request = new \AdeptCMS\Application\Session\Request($db, $conf);
  }

  public function setBlock(bool $block)
  {
    if ($_SESSION['session.block'] != $block && $this->id > 0) {
      $_SESSION['session.block'] = $block;

      $this->db->update(
        'UPDATE `session` SET `block` = ? WHERE `id` = ?',
        [$block, $this->id]
      );
    }
  }

  public function reset()
  {
    //session_reset();
    foreach ($_SESSION as $k => $v) {
      //if (substr($k, 0, 4) == 'auth') {
      unset($_SESSION[$k]);
      //}
    }
  }
}
