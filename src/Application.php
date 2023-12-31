<?php

/**
 * \Adept\Application
 *
 * The application object
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace Adept;

defined('_ADEPT_INIT') or die();

require_once('Global.php');

use \Adept\Abstract\Configuration;
use \Adept\Application\Database;
use \Adept\Application\Email;
use \Adept\Application\Session;

/**
 * \Adept\Application
 *
 * The application object
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Application
{
  /**
   * The configuration object
   * 
   * @var \Adept\Abstract\Configuration;
   */
  public Configuration $conf;

  /**
   * The database object
   *
   * @var \Adept\Application\Database
   */
  public Database $db;

  /**
   * Undocumented variable
   *
   * @var \Adept\Application\Email
   */
  public Email $email;

  /**
   * The session object
   *
   * @var \Adept\Application\Session
   */
  public Session $session;

  /**
   * Constructor
   *
   * @param \Adept\Abstract\Configuration $conf
   * @param \Adept\Application\Database $this->db
   * @param \Adept\Application\Session $this->session
   */
  public function __construct(Configuration $conf)
  {
    $this->conf = $conf;

    // This is a basic config/security check.  We want to make sure that the 
    // host matches the site conf.  It might not because of a misconfigured
    // webserver, or the user is being "cute".  Whatever the reason we won't
    // waste time on them.

    $url = filter_var((!empty($_SERVER['HTTP_HOST']))
      ? $_SERVER['HTTP_HOST']
      : $_SERVER['SERVER_NAME'], FILTER_SANITIZE_URL);

    // If the requiested url dosn't match the configured url we stop
    if ($url !== $conf->site->url) {
      http_response_code(400);
      \Adept\Error::halt(
        E_ERROR,
        "The URL $url doesn't match the allowed url " . $conf->site->url,
        __FILE__,
        __LINE__
      );
      //\Adept\error(debug_backtrace(), 'Invalid URL', "The URL $url doesn't match the allowed url $conf->site->url");
    }

    $this->db = new Database($conf);
    $this->session = new Session($this->db, $conf);

    if ($this->session->request->route->email) {
      $this->email = new \Adept\Application\Email($conf);
    }

    // Check various block lists
    if (
      $this->session->block
      || $this->session->request->ip->block
      || $this->session->request->route->block
      || $this->session->request->url->block
      || $this->session->request->useragent->block
    ) {
      $message  = '<div>Session ' . $this->session->block . '</div>';
      $message .= '<div>Route  ' . $this->session->request->route->block . '</div>';
      $message .= '<div>Url ' . $this->session->request->url->block . '</div>';
      $message .= '<div>IPAddress ' . $this->session->request->ip->block . '</div>';
      $message .= '<div>UserAgent ' . $this->session->useragent->block . '</div>';

      \Adept\error(debug_backtrace(), 'Blocked', $message);

      $this->session->setBlock(true);
      $this->session->request->setStatus(400);
      die();
    }
  }
}
