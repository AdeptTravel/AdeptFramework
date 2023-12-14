<?php

/**
 * \Adept\Abstract\GetVars
 *
 * Get's variable from Get or Post
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Abstract;

defined('_ADEPT_INIT') or die();

use \Adept\Application\Session\Request\Route;

/**
 * \Adept\Abstract\GetVars
 *
 * Get's variable from Get or Post
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
abstract class GetVars
{
  /**
   * Undocumented variable
   *
   * @var string
   */
  protected string $type;

  /**
   * Undocumented function
   *
   * @param  string          $key
   * @param  bool|int|string $val
   *
   * @return void
   */
  public function set(string $key, bool|int|string $val)
  {
    //echo "<p>Setting $this->type - $key = $val</p>";
    switch ($this->type) {
      case 'Server':
        $_SESSION[$key] = $val;
        break;
      default:
        break;
    }

    //die("Session: <pre>" . print_r($_SESSION, true));
  }

  /**
   * Get the unfiltered raw value
   *
   * @param  string $key
   *
   * @return string
   */
  public function getRaw(string $key, string $default, int $limit = 64): string
  {
    $val = $default;

    switch ($this->type) {
      case 'Get':
        if (isset($_GET[$key])) {
          $val = $_GET[$key];
        }
        break;

      case 'Post':
        if (isset($_POST[$key])) {
          $val = $_POST[$key];
        }
        break;

      case 'Server':
        if (isset($_SESSION[$key])) {
          $val = $_SESSION[$key];
        }
        break;

      default:
        $val = $default;
        break;
    }

    // Security checks
    if (strpos($val, '<?php')) {
      $val = '';
    }

    $val = trim($val);

    if ($limit > 0) {
      $val = substr($val, 0, $limit);
    }

    return $val;
  }

  public function isEmpty(): bool
  {
    $empty = true;

    switch ($this->type) {
      case 'Get':
        $empty = empty($_GET);
        break;
      case 'Post':
        $empty = empty($_POST);
        break;
      case 'Server':
        $empty = empty($_SESSION);
        break;
      default:
        break;
    }

    return $empty;
  }

  public function exists(string $key): bool
  {
    $empty = false;

    switch ($this->type) {
      case 'Get':
        $empty = !empty($_GET[$key]);
        break;
      case 'Post':
        $empty = !empty($_POST[$key]);
        break;
      case 'Server':
        $empty = !empty($_SESSION[$key]);
        break;
      default:
        break;
    }

    return $empty;
  }

  public function purge()
  {
    switch ($this->type) {
      case 'Get':
        unset($_GET);
        break;
      case 'Post':
        unset($_POST);
        break;
      case 'Server':
        //unset($_SESSION);
        break;
      default:
        break;
    }
  }

  public function del(string $key)
  {
    switch ($this->type) {
      case 'Get':
        unset($_GET[$key]);
        break;
      case 'Post':
        unset($_POST[$key]);
        break;
      case 'Server':
        unset($_SESSION[$key]);
        break;
      default:
        break;
    }
  }

  /**
   * Get letters only
   *
   * @param  string $key
   * @param  string $default
   *
   * @return string
   */
  public function getLetters(string $key, string $default = '', int $limit = 64): string
  {
    $raw = $this->getRaw($key, (string)$default, $limit);

    $val = strip_tags($raw);
    $val = addslashes($val);
    $val = preg_replace('/[^a-zA-Z]/', '', $val);

    if ($raw != $val) {
      //\Adept\Error::halt(debug_backtrace(), "Variable mismatch - $key - $raw != $val");
      \Adept\Error::halt(E_ERROR, "Variable mismatch - $key - $raw != $val", __FILE__, __LINE__);
    }

    return $val;
  }

  public function getBool(string $key, bool $default = false, int $limit = 1): bool
  {
    $raw = $this->getRaw($key, (string)$default, $limit);

    $val = $default;

    if ($raw == '1' || $raw == 1) {
      $val = true;
    } else if ($raw == '0' || $raw == 0) {
      $val = false;
    }

    return $val;
  }

  /**
   * Get numbers only
   *
   * @param  string $key
   * @param  int    $default
   *
   * @return int
   */
  public function getInt(string $key, int $default = 0, int $limit = 9): int
  {
    $raw = $this->getRaw($key, (string)$default, $limit);

    $val = filter_var($raw, FILTER_SANITIZE_NUMBER_INT);

    if ($raw != $val) {
      \Adept\Error::halt(E_ERROR, "Variable mismatch - $key - $raw != $val", __FILE__, __LINE__);
    }

    return $val;
  }

  /**
   * Get only letters and numbers
   *
   * @param  string $key
   * @param  string $default
   *
   * @return string
   */
  public function getAlphaNumeric(string $key, string $default = '', int $limit = 64): string
  {
    $raw = $this->getRaw($key, (string)$default, $limit);

    $val = strip_tags($raw);
    $val = addslashes($val);
    $val = preg_replace('/[^0-9a-zA-Z]/', '', $val);

    if ($raw != $val) {
      \Adept\Error::halt(E_ERROR, "Variable mismatch - $key - $raw != $val", __FILE__, __LINE__);
    }

    return $val;
  }

  /**
   * Get a sanitized string
   *
   * @param  string $key
   * @param  string $default
   *
   * @return string
   */
  public function getString(string $key, string $default = '', int $limit = 64): string
  {
    $raw = $this->getRaw($key, $default, $limit);

    $val = strip_tags($raw);
    $val = addslashes($val);

    if ($raw != $val) {
      \Adept\Error::halt(E_ERROR, "Variable mismatch - $key - $raw != $val", __FILE__, __LINE__);
    }

    return $val;
  }

  /**
   * Gets a string with letters, numbers, and the '-' character
   *
   * @param  string $key
   * @param  string $default
   *
   * @return string
   */
  public function getName(string $key, string $default = '', int $limit = 16): string
  {
    $raw = $this->getString($key, $default, $limit);

    $val = trim($raw);
    $val = preg_replace('/[^0-9a-zA-Z- ]/', '', $val);

    return $val;
  }

  /**
   * Gets a string with letters, numbers, and the '-' character
   *
   * @param  string $key
   * @param  string $default
   *
   * @return string
   */
  public function getAddress(string $key, string $default = '', int $limit = 32): string
  {
    $raw = $this->getString($key, $default, $limit);
    $val = preg_replace('/[^0-9a-zA-Z- ]/', '', $raw);

    return $val;
  }

  public function getDate(string $prefix = ''): \DateTime|null
  {
    $date = null;

    if (!empty($prefix)) {
      $prefix .= '_';
    }

    $d = $this->getInt($prefix . 'day', 0, 2);
    $m = $this->getInt($prefix . 'month', 0, 2);
    $y = $this->getInt($prefix . 'year', 0, 4);

    if (
      $y > 1900 && $y <= date('Y') + 10
      && $m > 0 && $m < 13
      && $d > 0 && $d < 31
    ) {
      $date = new \DateTime("$y-$m-$d");
    }

    return $date;
  }

  /**
   * Get a filtered string for an email address
   *
   * @param  string $key
   * @param  string $default
   *
   * @return string
   */
  public function getEmail(string $key, string $default = '', int $limit = 64): string
  {
    $raw = $this->getString($key, $default, $limit);

    return filter_var(
      trim($raw),
      FILTER_SANITIZE_EMAIL
    );
  }

  public function getPhone(string $key, string $default = '', $limit = 15): string
  {
    $raw = $this->getString($key, $default, $limit);

    $val = preg_replace('/[^0-9x]/', '', $val);

    return $val;
  }

  public function getUrl(string $key, string $default = '', int $limit = 256): string
  {
    $raw = $this->getRaw($key, (string)$default, $limit);

    return filter_var(
      $raw,
      FILTER_SANITIZE_URL
    );
  }

  public function getPath(string $key, string $default = '', $limit = 128): string
  {
    $raw = $this->getString($key, $default, $limit);
    return preg_replace('/[^A-Za-z0-9\/.\-_]/', '', $raw);
  }
}
