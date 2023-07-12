<?php

/**
 * \AdeptCMS\Data\Item\IPAddress
 *
 * IP Address object
 *
 * @package    AdeptCMS
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace AdeptCMS\Data\Item;

defined('_ADEPT_INIT') or die();

/**
 * \AdeptCMS\Data\Item\IPAddress
 *
 * IP Address object
 *
 * @package    AdeptCMS
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class IPAddress extends \AdeptCMS\Base\Data\Item
{

  public $ipaddress = '';
  public $encoded;
  public $block = false;
  public $created = '0000-00-00 00:00:00';

  public function __construct(\AdeptCMS\Application\DataBase &$db, int|string $id = 0)
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

    parent::__construct(
      $db,
      (is_numeric($id)) ? $id : $ipaddress
    );

    if (!$this->loadCache()) {
      if (!empty($ipaddress)) {
        $this->ipaddress = $ipaddress;
        $this->encoded = inet_pton($ipaddress);

        $this->save();
      }
    }
  }

  public function toString(): string
  {
    return $this->ipaddress;
  }
}
