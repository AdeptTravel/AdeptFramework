<?php

/**
 * General exception
 *
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2016-2018 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */

namespace AdeptCMS\Exceptions;

defined('_ADEPT_INIT') or die();

/**
 * General exception
 *
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2016-2018 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */
class ConfigFileException extends \AdeptCMS\Exceptions\Exception
{
  public function __construct($message = null, $code = 0, Exception $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}
