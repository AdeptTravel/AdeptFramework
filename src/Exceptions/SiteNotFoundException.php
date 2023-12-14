<?php

/**
 * SiteNotFoundException
 *
 * Requested website not found exception.
 *
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Exceptions;

defined('_ADEPT_INIT') or die();

/**
 * SiteNotFoundException
 *
 * File not found exception.
 *
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */
class SiteNotFoundException extends \Exception
{
  function __construct()
  {
    $out = '<h1>Website not configured</h1>';

    parent::__construct($out);
  }
}
