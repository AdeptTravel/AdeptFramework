<?php

/**
 * \AdeptCMS\Application
 *
 * The application object
 *
 * @package    AdeptCMS
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace AdeptCMS;

defined('_ADEPT_INIT') or die();

/**
 * \AdeptCMS\Application
 *
 * The application object
 *
 * @package    AdeptCMS
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Application
{
  /**
   * The configuration object
   * 
   * @var \AdeptCMS\Base\Configuration
   */
  public $conf;

  /**
   * The database object
   *
   * @var \AdeptCMS\Application\Database
   */
  public $db;

  /**
   * The session object
   *
   * @var \AdeptCMS\Application\Session
   */
  public $session;

  /**
   * Constructor
   *
   * @param \AdeptCMS\Base\Configuration $conf
   * @param \AdeptCMS\Application\Database $this->db
   * @param \AdeptCMS\Application\Session $this->session
   */
  public function __construct(\AdeptCMS\Base\Configuration $conf)
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
      \AdeptCMS\error(get_class($this), __FUNCTION__, 'URL doesnt match');
    }

    $this->db = new \AdeptCMS\Application\Database($conf);
    $this->session = new \AdeptCMS\Application\Session($this->db, $conf);

    /*
    // Check various block lists
    if (
      $this->session->block
      || $this->session->request->route->block
      || $this->session->request->url->block
      || $this->session->request->ip->block
      || $this->session->useragent->block
    ) {
      $message  = '<div>Session ' . $this->session->block . '</div>';
      $message .= '<div>Route  ' . $this->session->request->route->block . '</div>';
      $message .= '<div>Url ' . $this->session->request->url->block . '</div>';
      $message .= '<div>IPAddress ' . $this->session->request->ip->block . '</div>';
      $message .= '<div>UserAgent ' . $this->session->useragent->block . '</div>';
      \AdeptCMS\error(get_class($this), __FUNCTION__, $message);
      $this->session->setBlock(true);
      $this->session->request->setStatus(400);
      die();
    }
    */
  }
}
