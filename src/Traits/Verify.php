<?php

namespace AdeptCMS\Traits;

defined('_ADEPT_INIT') or die();

/**
 * \AdeptCMS\Traits\Verify
 *
 * Functions to assit with viewing or manipulating the filesystem
 *
 * @package AdeptCMS
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */
trait Verify
{
  public function verifyDateTime(string $time)
  {
    //0000-00-00 00:00:00
    //    4  7     12 15
    return (strlen($time) == 19
      && substr($time, 4, 1) == '-'
      && substr($time, 7, 1) == '-'
      && substr($time, 12, 1) == ':'
      && substr($time, 15, 1) == ':');
  }
}
