<?php

/**
 * \Adept\Error
 *
 * The application object
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace Adept;

defined('_ADEPT_INIT') or die();

/**
 * \Adept\Error
 *
 * Error class
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Error
{

  /**
   * Undocumented function
   *
   * @param  int    $level
   * @param  string $message
   * @param  string $file
   * @param  int    $line
   * @param  array  $context
   *
   * @return void
   */
  public static function halt(int $level, string $message, string $file = '', int $line = 0, array $context = [])
  {
    echo '<h1>';
    switch ($level) {
      case E_ERROR:
        echo 'Error';
        break;
      case E_WARNING:
        echo 'Warning';
        break;
      case E_NOTICE:
        echo 'Notice';
        break;
      case E_USER_ERROR:
        echo 'User Error';
        break;
      case E_USER_WARNING:
        echo 'User Warning';
        break;
      case E_USER_NOTICE:
        echo 'User Notice';
        break;
      case E_STRICT:
        echo 'Strict';
        break;
      case E_ALL:
        echo 'All';
        break;
      default:
        break;
    }

    echo '</h1>';
    //string $message, string $file = '', int $line = 0, array $context = [])
    if (!empty($file) || !empty($line)) {
      echo '<div>';

      if (!empty($file)) {
        echo $file;
      }

      if (!empty($file)) {
        if (!empty($file)) {
          echo ': ';
        }
        echo $line;
      }

      echo '</div>';
    }
    echo "<p>$message</p>";

    echo '<pre>' .  print_r(debug_backtrace(), true) . '</pre>';

    die();
  }
}
