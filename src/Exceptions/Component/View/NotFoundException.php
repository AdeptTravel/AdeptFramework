<?php

/**
 * Component View Not Found Exception
 *
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Exceptions\Component\View;

defined('_ADEPT_INIT') or die();

/**
 * Module Not Found Exception
 *
 * @author Brandon J. Yaniz (brandon@adept.travel)
 * @copyright 2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license BSD 2-Clause; See LICENSE.txt
 */
class NotFoundException extends \Exception
{
  function __construct(string $component, string $view, string $namespace, string $file)
  {

    $out = '';

    if (DEBUG) {
      $out .= '<style>dl{display: grid;grid-template-columns:min-content auto ;grid-template-rows: auto;}dt,dd{padding: 0.5em 0 0.5em 0;}dt {grid-column-start: 1;font-weight:bold;text-align:right;}dd{grid-column-start:2;}dt+dd{}</style>';
      $out .= '<dl>';
      $out .= '<dt>Component</dt>';
      $out .= '<dd>' . $component . '</dd>';

      $out .= '<dt>View</dt>';
      $out .= '<dd>' . $view . '</dd>';

      $out .= '<dt>Namespace</dt>';
      $out .= '<dd>' . $namespace . '</dd>';

      $out .= '<dt><File/dt>';
      $out .= '<dd>' . $file . '</dd>';


      $out .= '<dt>File</dt>';
      $out .= '<dd>' . $this->getFile() . '</dd>';
      $out .= '<dt>Line</dt>';
      $out .= '<dd>' . $this->getLine() . '</dd>';
      $out .= '<dt>Message</dt>';
      $out .= '<dd>' . $this->getMessage() . ' - The view was not found.</dd>';
      $out .= '</dl>';
    }

    //debug_print_backtrace();

    parent::__construct($out);
  }
}
