<?php

/**
 * \Adept\Helper\Arrays
 *
 * Functions to make working with arrays easier
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Helper;

defined('_ADEPT_INIT') or die();

/**
 * \Adept\Helper\Arrays
 *
 * Functions to make working with arrays easier
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */ class Arrays
{
  public static function ValueToArray(array $original)
  {

    $updated = [];

    for ($i = 0; $i < count($original); $i++) {
      $updated[$original[$i]] = $original[$i];
    }

    return $updated;
  }
}
