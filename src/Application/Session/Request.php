<?php

/**
 * \AdeptCMS\Application\Session\Request
 *
 * Stores information about the current request
 *
 * @package AdeptCMS
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */

namespace AdeptCMS\Application\Session;

defined('_ADEPT_INIT') or die();

/**
 * \AdeptCMS\Application\Session\Request
 *
 * Stores information about the current request
 *
 * @package AdeptCMS
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */
class Request
{

  /**
   * @var \AdeptCMS\Application\Database
   */
  protected $db;

  /**
   * A reference to the site configuration
   *
   * @var \AdeptCMS\Base\Configuration
   */
  protected $conf;

  /**
   * @var \AdeptCMS\Application\Session\Request\Data
   */
  public $data;

  /**
   * @var \AdeptCMS\Data\Item\IPAddress
   */
  public $ip;

  /**
   * @var int
   */
  public $order;

  /**
   * @var \AdeptCMS\Application\Session\Request\Route
   */
  public $route;

  /**
   * Total request bandwidth
   *
   * @var int
   */
  public $size;

  /**
   * HTTP Status Code
   * 
   * @var int
   */
  public $status;

  /**
   * The URL the request came in on
   *
   * @var \AdeptCMS\Data\Item\Url
   */
  public $url;

  /**
   * Constructor
   *
   * @param \AdeptCMS\Application\Database $db
   * @param \AdeptCMS\Base\Configuration $conf
   */
  public function __construct(
    \AdeptCMS\Application\Database &$db,
    \AdeptCMS\Base\Configuration &$conf,
  ) {

    $this->conf = $conf;
    $this->db = $db;

    if (isset($_SESSION['request.order'])) {
      $_SESSION['request.order']++;
    } else {
      $_SESSION['request.order'] = 0;
    }

    $this->url = new \AdeptCMS\Data\Item\Url($db);

    $this->data = new \AdeptCMS\Application\Session\Request\Data($db);
    $this->ip = new \AdeptCMS\Data\Item\IPAddress($db);
    $this->order = $_SESSION['request.order'];
    $this->size = 0;
    $this->route = new \AdeptCMS\Application\Session\Request\Route($db, $conf, $this->url);
    $this->status = 200;

    if (empty($this->route->route) || $this->route->type == 'Error') {
      $this->setStatus(404);
    }
  }

  public function __destruct()
  {
    $query = "INSERT INTO `request`";
    $query .= '(`session`, `ipaddress`, `route`, `url`, `data`, `size`, `code`, `order`)';
    $query .= 'VALUES';
    $query .= '(?,?,?,?,?,?,?,?)';

    $params = [
      $_SESSION['session.id'],
      $this->ip->id,
      $this->route->id,
      $this->url->id,
      $this->data->id,
      $this->size,
      $this->status,
      $this->order
    ];

    try {
      $this->db->insert($query, $params);
    } catch (\Exception $e) {
      $msg = "Query\n\n" . $query . "\n\n" . print_r($params, true) . "\n\nError: " . $e->getMessage();
      file_put_contents(FS_LOG . 'application.session.request.log', $msg);
      die('Request failed');
    }
  }

  public function setStatus(int $status)
  {
    $this->status = $status;

    if (http_response_code() != 200) {
      http_response_code($status);

      if ($this->format == 'HTML') {
        header('Location: https://' . $this->conf->site->url . '/' . $status);
      } else {
        \AdeptCMS\error(get_class($this), __FUNCTION__, 'Status Error');
      }
    }
  }
}
