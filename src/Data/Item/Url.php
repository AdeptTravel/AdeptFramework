<?php

namespace AdeptCMS\Data\Item;

defined('_ADEPT_INIT') or die('No Access');

class Url extends \AdeptCMS\Base\Data\Item
{
  use \AdeptCMS\Traits\Asset;

  /**
   * The full url with QueryString
   *
   * @var string
   */
  public $raw = '';

  /**
   * The filtered URL
   *
   * @var string
   */
  public $url = '';

  /**
   * The scheme ie. HTTP|HTTPS
   *
   * @var string
   */
  public $scheme = '';

  /**
   * The host name
   *
   * @var string
   */
  public $host = '';

  /**
   * The path
   *
   * @var string
   */
  public $path = '';

  /**
   * The path seperated into an array
   *
   * @var array
   */
  public $parts = [];

  /**
   * The file for the request, index.html is default
   *
   * @var string
   */
  public $file = '';

  /**
   * The extension of the request ie. html|css etc.
   *
   * @var string
   */
  public $extension = '';

  /**
   * Type of request 
   *
   * @var string
   */
  public $type = '';

  /**
   * Mime type of the request
   *
   * @var string
   */
  public $mime = '';

  /**
   * Is the URL blocked
   *
   * @var bool
   */
  public $block = false;

  /**
   * The datetime the url was created
   *
   * @var \DateTime
   */
  public $created;


  /**
   * Undocumented variable
   *
   * @var array
   */
  public $querystring = [];

  /**
   * Undocumented function
   *
   * @param \AdeptCMS\Application\Database $db
   * @param integer $id
   */
  public function __construct(\AdeptCMS\Application\Database $db, int|string $id = 0)
  {
    $this->excludeField[] = 'raw';

    parent::__construct($db, $id);

    // URL or ID was not specificed, get the current URL
    if (empty($id)) {
      $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";

      $host = (!empty($_SERVER['HTTP_HOST']))
        ? $_SERVER['HTTP_HOST']
        : $_SERVER['SERVER_NAME'];

      $url = $scheme . '://' . $host . $_SERVER['REQUEST_URI'];

      // Check if scheme or host are missing
      if (($pos = strpos($url, '/') == 0) && $pos !== false) {
        // Missing host
        if (($pos = strpos($url, '//') == 0) && $pos !== false) {
          $url = $scheme . ':' . $url;
        } else {
          $url = $scheme . '://' . $host . $url;
        }
      }

      $url = filter_var($url, FILTER_SANITIZE_URL);

      if (!filter_var($url, FILTER_VALIDATE_URL)) {
        throw new \AdeptCMS\Exceptions\InvalidUrl();
      }

      // Remove anchor
      if ($pos = strpos($url, '#')) {
        $url = substr($url, 0, $pos);
      }

      // Remove query
      if ($pos = strpos($url, '?')) {
        $url = substr($url, 0, $pos);
        $this->querystring = filter_input_array(INPUT_GET);
      }
    }

    $this->raw = $this->url;

    if (!empty($this->querystring)) {
      $this->raw .= '?' . http_build_query($this->querystring);
    }

    parent::__construct($db, (is_numeric($id)) ? $id : $url);

    if ($this->id == 0) {

      // Set URL
      $this->url = $url;

      // Set scheme
      $pos = strpos($url, '://');
      $this->scheme = substr($url, 0, $pos);


      // Set host
      $pos += 3;

      $this->host = substr($url, $pos, strpos($url, '/', $pos) - $pos);

      // Set path
      $pos += strlen($this->host) + 1;
      $this->path = substr($url, $pos);

      if (substr($this->path, -1, 1) == '/') {
        $this->path = substr($this->path, 0, strlen($this->path) - 1);
      }

      // Set parts array
      if (strlen($this->path) > 1) {
        $this->parts = explode('/', $this->path);
      }

      // Set index of last element
      $last = count($this->parts) - 1;

      // Set file (if specificed) and extension
      if ($last >= 0 && strpos($this->parts[$last], '.') !== false) {
        $this->file = $this->parts[$last];
        $this->extension = substr($this->parts[$last], strrpos($this->parts[$last], '.') + 1);
      } else {
        $this->extension = 'html';
      }

      $info = $this->getFormatInfo($this->extension);

      if ($info !== false) {
        $this->type = $info->type;
        $this->mime = $info->mime;
      } else {
        // TODO: Format error
        die('Url::__construct() Format Error');
      }

      $this->save();
    }
  }

  public function toString(): string
  {
    return $this->url;
  }

  public function getQuery(string $key): string
  {
    $out = '';

    if (array_key_exists($key, $this->querystring)) {
      $out = filter_var($this->querystring[$key], FILTER_UNSAFE_RAW);
    }

    return $out;
  }

  public function setQuery(string $key, string $value)
  {
    $this->querystring[$key] = $value;
    $this->raw = $this->url . '?' . http_build_query($this->querystring);
  }

  public function delQuery(string $key)
  {
    if (array_key_exists($key, $this->querystring)) {
      unset($this->querystring[$key]);
    }
  }
}
