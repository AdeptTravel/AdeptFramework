<?php

/**
 * General exception
 *
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Exceptions;

defined('_ADEPT_INIT') or die();

/**
 * General exception
 *
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */
class Exception extends \Exception
{
  function __construct(
    string $type,
    string $message,
    string $namespace = '',
    string $classname = '',
    string $method = ''
  ) {

    if (DEBUG) {
      echo '<style>dl{display: grid;grid-template-columns:min-content auto ;grid-template-rows: auto;}dt,dd{padding: 0.5em 0 0.5em 0;}dt {grid-column-start: 1;font-weight:bold;text-align:right;}dd{grid-column-start:2;}dt+dd{}</style>';
      echo '<dl>';
      echo '<dt>Type</dt>';
      echo '<dd>' . $type . '</dd>';

      if (!empty($namespace)) {
        echo '<dt>Namespace</dt>';
        echo '<dd>' . $namespace . '</dd>';
      }

      if (!empty($classname)) {
        echo '<dt>Class</dt>';
        echo '<dd>' . $classname . '</dd>';
      }

      if (!empty($method)) {
        echo '<dt>Method</dt>';
        echo '<dd>' . $method . '</dd>';
      }

      echo '<dt>File</dt>';
      echo '<dd>' . $this->getFile() . '</dd>';
      echo '<dt>Line</dt>';
      echo '<dd>' . $this->getLine() . '</dd>';
      //echo '<dt>Message</dt>';
      //echo '<dd>' . $this->getMessage() . '-' . $message . '</dd>';
      echo '</dl>';

      if (!empty($this->getMessage())) {
        echo '<p>' . $this->getMessage() . '</p>';
      }


      echo '<p>' . $message . '</p>';
    }

    //debug_print_backtrace();

    parent::__construct();
  }
}
