<?php

/**
 * General exception
 *
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2022 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */

namespace AdeptCMS\Exceptions;

defined('_ADEPT_INIT') or die();

/**
 * General exception
 *
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2022 The Adept Traveler, Inc., All Rights Reserved.
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

    $out = '';

    if (DEBUG) {
      $out .= '<style>dl{display: grid;grid-template-columns:min-content auto ;grid-template-rows: auto;}dt,dd{padding: 0.5em 0 0.5em 0;}dt {grid-column-start: 1;font-weight:bold;text-align:right;}dd{grid-column-start:2;}dt+dd{}</style>';
      $out .= '<dl>';
      $out .= '<dt>Type</dt>';
      $out .= '<dd>' . $type . '</dd>';

      if (!empty($namespace)) {
        $out .= '<dt>Namespace</dt>';
        $out .= '<dd>' . $namespace . '</dd>';
      }

      if (!empty($classname)) {
        $out .= '<dt>Class</dt>';
        $out .= '<dd>' . $classname . '</dd>';
      }

      if (!empty($method)) {
        $out .= '<dt>Method</dt>';
        $out .= '<dd>' . $method . '</dd>';
      }

      $out .= '<dt>File</dt>';
      $out .= '<dd>' . $this->getFile() . '</dd>';
      $out .= '<dt>Line</dt>';
      $out .= '<dd>' . $this->getLine() . '</dd>';
      //$out .= '<dt>Message</dt>';
      //$out .= '<dd>' . $this->getMessage() . '-' . $message . '</dd>';
      $out .= '</dl>';

      if (!empty($this->getMessage())) {
        $out .= '<p>' . $this->getMessage() . '</p>';
      }


      $out .= '<p>' . $message . '</p>';
    }



    //debug_print_backtrace();

    parent::__construct($out);
  }
}
