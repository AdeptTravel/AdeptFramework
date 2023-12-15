<?php

/**
 * \Adept\Model\Item\IPAddress
 *
 * IP Address object
 *
 * @package Adept
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Model\Items;

defined('_ADEPT_INIT') or die();

/**
 * \Adept\Model\Item\IPAddress
 *
 * IP Address object
 *
 * @package Adept
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */
class IPAddress extends \Adept\Abstract\Model\Item
{
  public function __construct(\Adept\Application\Database &$db, string $ipaddress = '')
  {
    parent::__construct($db);

    $this->init();

    if (empty($ipaddress)) {
      if (!empty($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        // Cloudflair
        $ipaddress = $_SERVER["HTTP_CF_CONNECTING_IP"];
      } else if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // Primary
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
      } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Get proxy list
        $list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

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
    }

    $this->cache = FS_SITE_CACHE . 'IpAddress/' . hash('md5', $ipaddress) . '.json';

    if (!$this->loadCache()) {
      if (!empty($ipaddress) && filter_var($ipaddress, FILTER_VALIDATE_IP)) {
        $this->data->ipaddress = $ipaddress;
        $this->data->encoded = inet_pton($ipaddress);

        $this->save();
      }
    }
  }

  public function init()
  {
    $this->data = (object) [
      'id' => 0,
      'ipaddress' => '',
      'encoded' => '',
      'block' => false,
      'created' => '0000-00-00 00:00:00'
    ];
  }

  public function toString(): string
  {
    return $this->data->ipaddress;
  }

  public function getId(): int
  {
    return $this->data->id;
  }

  public function getIpAddress(): string
  {
    return $this->data->ipaddress;
  }

  public function getBlocked(): bool
  {
    return $this->data->blocked;
  }

  public function getCreated(): string
  {
    return $this->data->created;
  }

  public function isEmpty(): bool
  {
    return !empty($this->data->ipaddress);
  }

  protected function validate(): bool
  {
    return $this->isEmpty();
  }

  public function save(): bool
  {
    $status = false;

    if ($this->validate()) {
      $data = $this->data;

      if ($data->id == 0) {
        unset($data->id);
      }

      $data = (array)$data;

      if (($id = $this->db->insertSingleTableGetId('ipaddress', $data)) !== false) {
        $this->data->id = $id;
        $this->saveCache();
        $status = true;
      }
    }

    return $status;
  }
}
