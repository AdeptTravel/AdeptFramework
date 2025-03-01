<?php

namespace Adept\Abstract;

// Prevent direct access to the script
defined('_ADEPT_INIT') or die();

/**
 * \Adept\Abstract\GetVars
 *
 * Gets variables from GET, POST, or SESSION
 *
 * @package    AdeptFramework
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 * @version    1.0.0
 */
abstract class GetVars
{
  /**
   * The type of data source ('Get', 'Post', 'Server')
   *
   * @var string
   */
  protected string $type;

  /**
   * Sets a value in the data source
   *
   * @param string          $key The key to set
   * @param bool|int|string $val The value to set
   *
   * @return void
   */
  public function set(string $key, bool|int|string $val)
  {
    switch ($this->type) {
      case 'Server':
        $_SESSION[$key] = $val;
        break;
      case 'Post':
        $_POST[$key] = $val;
        break;
      default:
        // Other types are not handled
        break;
    }
  }

  /**
   * Retrieves a sanitized value from the data source
   *
   * @param string $key     The key to retrieve
   * @param string $default The default value if the key doesn't exist
   * @param int    $limit   The maximum length of the value
   * @param bool   $html    Whether to allow HTML content
   *
   * @return string The sanitized value
   */
  public function get(string $key, string $default, int $limit = 64, bool $html = false): string
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

    $val = $this->clean($val, $limit, $html);

    return $val;
  }

  /**
   * Checks if the data source is empty
   *
   * @return bool Returns true if empty, false otherwise
   */
  public function isEmpty(string $key = ''): bool
  {
    $status = true;

    $ref = [];

    switch ($this->type) {
      case 'Get':
        $ref = &$_GET;
        break;

      case 'Post':
        $ref = &$_POST;
        break;

      case 'Server':
        $ref = &$_SERVER;
        break;

      default:
        break;
    }

    if (empty($key)) {
      $status = empty($ref);
    } else if (array_key_exists($key, $ref)) {
      $status = empty($ref[$key]);
    }

    return $status;
  }

  /**
   * Checks if a key exists in the data source
   *
   * @param string $key The key to check
   *
   * @return bool Returns true if the key exists, false otherwise
   */
  public function exists(string $key): bool
  {
    $status = false;
    switch ($this->type) {
      case 'Get':
        $status = array_key_exists($key, $_GET);
        //$exists = (isset($_GET[$key]) && (!empty($_GET[$key]) || $_GET[$key] == 0));
        break;
      case 'Post':
        $status = array_key_exists($key, $_POST);
        //$exists = (isset($_POST[$key]) && !empty($_POST[$key]));
        break;
      case 'Server':
        $status = array_key_exists($key, $_SERVER);
        //$exists = (isset($_SESSION[$key]) && (!empty($_SESSION[$key]) || $_SESSION[$key] == 0));
        break;
      default:
        break;
    }


    return $status;
  }

  /**
   * Purges all data from the data source
   *
   * @return void
   */
  public function purge()
  {
    switch ($this->type) {
      case 'Get':
        $_GET = [];
        break;
      case 'Post':
        $_POST = [];
        break;
      case 'Server':
        // Optionally, you can unset specific session variables or destroy the session
        // $_SESSION = [];
        break;
      default:
        break;
    }
  }

  /**
   * Deletes a key from the data source
   *
   * @param string $key The key to delete
   *
   * @return void
   */
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
   * Retrieves a string containing only letters
   *
   * @param string $key     The key to retrieve
   * @param string $default The default value if the key doesn't exist
   * @param int    $limit   The maximum length of the value
   *
   * @return string The sanitized string
   */
  public function getLetters(string $key, string $default = '', int $limit = 64): string
  {
    $raw = $this->get($key, $default, $limit);

    $val = strip_tags($raw);
    $val = addslashes($val);
    $val = preg_replace('/[^a-zA-Z]/', '', $val);

    if ($raw != $val) {
      \Adept\Error::halt(E_ERROR, "Variable mismatch - $key - $raw != $val", __FILE__, __LINE__);
    }

    return $val;
  }

  /**
   * Retrieves a boolean value
   *
   * @param string $key     The key to retrieve
   * @param bool   $default The default value if the key doesn't exist
   * @param int    $limit   The maximum length of the value
   *
   * @return bool The boolean value
   */
  public function getBool(string $key, bool $default = false, int $limit = 3): bool
  {
    $raw = $this->get($key, (string)$default, $limit);

    $val = $default;

    if ($raw === '1' || $raw === 'on') {
      $val = true;
    } elseif ($raw === '0' || $raw === 'off') {
      $val = false;
    }

    return $val;
  }

  /**
   * Retrieves an integer value
   *
   * @param string $key     The key to retrieve
   * @param int    $default The default value if the key doesn't exist
   * @param int    $limit   The maximum length of the value
   *
   * @return int The integer value
   */
  public function getInt(string $key, int $default = 0, int $limit = 9): int
  {
    $raw = $this->get($key, (string)$default, $limit);

    if ($raw === 'on') {
      $raw = '1';
    }

    $val = filter_var($raw, FILTER_SANITIZE_NUMBER_INT);

    if ($raw != $val) {
      \Adept\Error::halt(E_ERROR, "Variable mismatch - $key - $raw != $val", __FILE__, __LINE__);
    }

    return (int)$val;
  }

  /**
   * Retrieves a string containing only alphanumeric characters
   *
   * @param string $key     The key to retrieve
   * @param string $default The default value if the key doesn't exist
   * @param int    $limit   The maximum length of the value
   *
   * @return string The sanitized string
   */
  public function getAlphaNumeric(string $key, string $default = '', int $limit = 64): string
  {
    $raw = $this->get($key, $default, $limit);

    $val = strip_tags($raw);
    $val = addslashes($val);
    $val = preg_replace('/[^0-9a-zA-Z]/', '', $val);

    if ($raw != $val) {
      \Adept\Error::halt(E_ERROR, "Variable mismatch - $key - $raw != $val", __FILE__, __LINE__);
    }

    return $val;
  }

  public function getHtml(string $key, string $default = '')
  {
    $raw = $this->get($key, $default, 0, true);

    $purifier = new \HTMLPurifier(\HTMLPurifier_Config::createDefault());
    // Sanitize the input HTML
    return $purifier->purify($raw);
  }

  /**
   * Retrieves a sanitized string
   *
   * @param string $key     The key to retrieve
   * @param string $default The default value if the key doesn't exist
   * @param int    $limit   The maximum length of the value
   *
   * @return string The sanitized string
   */
  public function getString(string $key, string $default = '', int $limit = 0): string
  {
    $raw = $this->get($key, $default, $limit);

    $val = strip_tags($raw);
    $val = addslashes($val);

    if ($raw != $val) {
      \Adept\Error::halt(E_ERROR, "Variable mismatch - $key - $raw != $val", __FILE__, __LINE__);
    }

    return $val;
  }

  /**
   * Retrieves a name string (letters, numbers, hyphens, and spaces)
   *
   * @param string $key     The key to retrieve
   * @param string $default The default value if the key doesn't exist
   * @param int    $limit   The maximum length of the value
   *
   * @return string The sanitized name string
   */
  public function getName(string $key, string $default = '', int $limit = 16): string
  {
    $raw = $this->getString($key, $default, $limit);

    $val = trim($raw);
    $val = preg_replace('/[^0-9a-zA-Z- ]/', '', $val);

    return $val;
  }

  /**
   * Retrieves an address string (letters, numbers, hyphens, and spaces)
   *
   * @param string $key     The key to retrieve
   * @param string $default The default value if the key doesn't exist
   * @param int    $limit   The maximum length of the value
   *
   * @return string The sanitized address string
   */
  public function getAddress(string $key, string $default = '', int $limit = 32): string
  {
    $raw = $this->getString($key, $default, $limit);
    $val = preg_replace('/[^0-9a-zA-Z- ]/', '', $raw);

    return $val;
  }

  /**
   * Retrieves a DateTime object from date components
   *
   * @param string $prefix The prefix for date input fields
   *
   * @return \DateTime|null The DateTime object or null if invalid
   */
  public function getDate(string $prefix = ''): ?\DateTime
  {
    if (!empty($prefix)) {
      $prefix .= '_';
    }

    $d = $this->getInt($prefix . 'day', 0, 2);
    $m = $this->getInt($prefix . 'month', 0, 2);
    $y = $this->getInt($prefix . 'year', 0, 4);

    if (
      $y > 1900 && $y <= date('Y') + 10 &&
      $m > 0 && $m <= 12 &&
      $d > 0 && $d <= 31
    ) {
      return new \DateTime("$y-$m-$d");
    }

    return null;
  }

  /**
   * Retrieves a DateTime object from a date/time string
   *
   * @param string $key     The key to retrieve
   * @param string $default The default value if the key doesn't exist
   *
   * @return \DateTime The DateTime object
   */
  public function getDateTime(string $key, string $default = ''): \DateTime
  {
    $raw = $this->getString($key, $default);
    $val = new \DateTime('0000-01-01 00:00:00');

    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw)) {
      // Format: YYYY-MM-DD
      $val = \DateTime::createFromFormat('Y-m-d', $raw);
    } elseif (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $raw) && $raw != '0000-00-00 00:00:00') {
      // Format: YYYY-MM-DD HH:MM:SS
      $val = \DateTime::createFromFormat('Y-m-d H:i:s', $raw);
    } elseif (preg_match('/^\d{2}:\d{2}:\d{2}$/', $raw)) {
      // Format: HH:MM:SS
      $val = \DateTime::createFromFormat('H:i:s', $raw);
      // If the format is only time, we need to add a default date
      $val->setDate(0000, 1, 1); // Set to Unix epoch start date
    }

    return $val;
  }

  /**
   * Retrieves a sanitized email address
   *
   * @param string $key     The key to retrieve
   * @param string $default The default value if the key doesn't exist
   * @param int    $limit   The maximum length of the value
   *
   * @return string The sanitized email address
   */
  public function getEmail(string $key, string $default = '', int $limit = 64): string
  {
    $raw = $this->getString($key, $default, $limit);

    return filter_var(
      trim($raw),
      FILTER_SANITIZE_EMAIL
    );
  }

  /**
   * Retrieves a sanitized phone number
   *
   * @param string $key     The key to retrieve
   * @param string $default The default value if the key doesn't exist
   * @param int    $limit   The maximum length of the value
   *
   * @return string The sanitized phone number
   */
  public function getPhone(string $key, string $default = '', int $limit = 15): string
  {
    $raw = $this->getString($key, $default, $limit);
    $val = preg_replace('/[^0-9x]/', '', $raw);

    return $val;
  }

  /**
   * Retrieves a sanitized URL
   *
   * @param string $key     The key to retrieve
   * @param string $default The default value if the key doesn't exist
   * @param int    $limit   The maximum length of the value
   *
   * @return string The sanitized URL
   */
  public function getUrl(string $key, string $default = '', int $limit = 256): string
  {
    $raw = $this->get($key, $default, $limit);

    return filter_var(
      $raw,
      FILTER_SANITIZE_URL
    );
  }

  /**
   * Retrieves a sanitized file path
   *
   * @param string $key     The key to retrieve
   * @param string $default The default value if the key doesn't exist
   * @param int    $limit   The maximum length of the value
   *
   * @return string The sanitized file path
   */
  public function getPath(string $key, string $default = '', int $limit = 128): string
  {
    $raw = $this->getString($key, $default, $limit);
    return preg_replace('/[^A-Za-z0-9\/.\-_]/', '', $raw);
  }

  /**
   * Retrieves all sanitized data as an associative array
   *
   * @return array The sanitized data array
   */
  public function getArray(): array
  {
    $arr = [];
    $raw = [];

    switch ($this->type) {
      case 'Get':
        $raw = $_GET;
        break;
      case 'Post':
        $raw = $_POST;
        break;
      case 'Server':
        $raw = $_SESSION;
        break;
      default:
        break;
    }

    foreach ($raw as $k => $v) {
      $key = preg_replace("/[^A-Za-z0-9_]/", '', $k);
      $val = $this->clean($v);
      $arr[$key] = $val;
    }

    return $arr;
  }

  /**
   * Cleans a value by stripping tags, trimming, and limiting length
   *
   * @param string $val   The value to clean
   * @param int    $limit The maximum length of the value
   * @param bool   $html  Whether to allow HTML content
   *
   * @return string The cleaned value
   */
  public function clean(
    string $val,
    int $limit = 0,
    bool $html = false
  ): string {
    // Security checks
    if (strpos($val, '<?php') !== false) {
      $val = '';
    }

    if (!$html) {
      $val = strip_tags($val);
    } else {
      // Optionally, use HTML Purifier for advanced HTML sanitization
      // $val = HTMLPurifier::purify($val);
    }


    $val = trim($val);

    if ($limit > 0) {
      $val = substr($val, 0, $limit);
    }

    return $val;
  }
}
