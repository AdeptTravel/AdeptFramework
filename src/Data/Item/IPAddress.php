<?php

/**
 * \Adept\Data\Item\IPAddress
 *
 * IP Address object
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Data\Item;

defined('_ADEPT_INIT') or die();

use \Adept\Application\DataBase;

/**
 * \Adept\Data\Item\IPAddress
 *
 * IP Address object
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class IPAddress extends \Adept\Abstract\Data\Item
{

  protected string $name = 'IP Address';

  public string $ipaddress = '';
  public string $encoded;
  public bool $block = false;
  public string $created = '0000-00-00 00:00:00';

  /**
   * Undocumented function
   *
   * @param  \Adept\Application\DataBase $db
   * @param  int                         $id
   */
  public function __construct(DataBase &$db, int|string $id = 0)
  {
    if (empty($id)) {
      $ipaddress = '';

      if (!empty($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        // Cloudflair
        $ipaddress = $_SERVER["HTTP_CF_CONNECTING_IP"];
      } else if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // Primary
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
      } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Get proxy list
        $list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

        // There has to be a better way, this looks like amature hour
        foreach ($list as $ip) {
          if (!empty($ip)) {
            // First IP
            $ipaddress = $ip;
            break;
          }
        }
      } else if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
      } else if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
      } else if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
      } else if (!empty($_SERVER['HTTP_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
      } else if (!empty($_SERVER['REMOTE_ADDR'])) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
      }

      $ipaddress = filter_var($ipaddress, FILTER_VALIDATE_IP);
    }

    parent::__construct($db, (((is_numeric($id) && $id > 0) || !empty($id)) ? $id : $ipaddress));

    //if (!$this->loadCache()) {
    //if (!empty($ipaddress)) {
    if ($this->id == 0) {
      $this->ipaddress = $ipaddress;
      $this->encoded = inet_pton($ipaddress);

      $this->save();
    }
    //}
  }

  protected function validate(): bool
  {
    return true;
  }
}
