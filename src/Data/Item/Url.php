<?php

namespace Adept\Data\Item;

defined('_ADEPT_INIT') or die('No Access');

use \Adept\Application\Database;

class Url extends \Adept\Abstract\Data\Item
{

  protected string $table = 'Url';
  protected string $index = 'url';

  protected array $excludeKeys = ['raw'];

  /**
   * The full url with QueryString
   *
   * @var string
   */
  public string $raw = '';

  /**
   * The filtered URL
   *
   * @var string
   */
  public string $url = '';

  /**
   * The scheme ie. HTTP|HTTPS
   *
   * @var string
   */
  public string $scheme = '';

  /**
   * The host name
   *
   * @var string
   */
  public string $host = '';

  /**
   * The path
   *
   * @var string
   */
  public string $path = '';

  /**
   * The path seperated into an array
   *
   * @var array
   */
  public array $parts = [];

  /**
   * The file for the request, index.html is default
   *
   * @var string
   */
  public string $file = '';

  /**
   * The extension of the request ie. html|css etc.
   *
   * @var string
   */
  public string $extension = '';

  /**
   * Type of request 
   *
   * @var string
   */
  public string $type = '';

  /**
   * Mime type of the request
   *
   * @var string
   */
  public string $mime = '';

  /**
   * Status - Active, Block, Honeypot
   *
   * @var string
   */
  public string $status = 'Active';

  /**
   * Undocumented function
   *
   * @param \Adept\Application\Database $db
   * @param integer $val
   */
  public function __construct(bool $current = false)
  {
    parent::__construct();

    if ($current) {
      $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443)
        ? "https"
        : "http";

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
        throw new \Adept\Exceptions\InvalidUrl();
      }

      // Remove anchor
      if ($pos = strpos($url, '#')) {
        $url = substr($url, 0, $pos);
      }

      // Remove query
      if ($pos = strpos($url, '?')) {
        $url = substr($url, 0, $pos);
      }

      // Remove trailing /
      if (substr($url, -1) == '/') {
        $url = substr($url, 0, -1);
      }

      if (!$this->loadFromIndex($url)) {
        // Set URL
        $this->url = $url;

        $parsed = parse_url($url);

        $this->scheme = $parsed['scheme'];
        $this->host = $parsed['host'];
        $this->path = (!empty($parsed['path'])) ?  substr($parsed['path'], 1) : '';

        // Set parts array
        if (!empty($this->path)) {
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
          // Generated on .php and other unknown files.  Within this system it's
          // most likly they are trying to somthing bad.  Let's kill everything
          // and maybe mark the URL as blocked for future bad actors.
          //\Adept\error(debug_backtrace(), 'URL format error', "No idea");
          \Adept\Error::halt(E_ERROR, 'No idea whats going on', __FILE__, __LINE__);
        }

        $this->save();
      }
    }
  }

  public function getFormatInfo(string $extension): object|bool
  {
    $info = (object)[
      'type' => '',
      'mime' => '',
    ];

    $fa = [ //fas or fab
      'Archive' => 'fa-file-archive',
      'Audio' => 'fa-file-audio',
      'CSS' => 'fa-css3-alt',
      'CSV' => 'fa-file-csv',
      'Font' => 'fa-font',
      'HTML' => 'fa-html5',
      'Image' => 'fas fa-image',
      'JSON' => 'fa-js',
      'JavaScript' => 'fa-js',
      'PDF' => 'fa-file-pdf',
      'Text' => 'fa-file-alt',
      'Video' => 'fa-file-video',
      'XML' => 'fa-file-code'
    ];

    // Set type & mime
    // Note: If a type is added make sure it's included in the db tables enum
    switch ($extension) {
      case "css":
        $info->type = "CSS";
        $info->mime = "text/css";
        break;

      case "csv":
        $info->type = "CSV";
        $info->mime = "text/csv";
        break;

      case "eot":
        $info->type = "Font";
        $info->mime = "application/vnd.ms-fontobject";
        break;

      case "gif":
        $info->type = "Image";
        $info->mime = "image/gif";
        break;

      case "html":
        $info->type = "HTML";
        $info->mime = "text/html";
        break;

      case "ico":
        $info->type = "Image";
        $info->mime = "image/vnd.microsoft.icon";
        break;

      case "jpg":
      case "jpeg":
        $info->type = "Image";
        $info->mime = "image/jpeg";
        break;

      case "js":
        $info->type = "JavaScript";
        $info->mime = "text/javascript";
        break;

      case "json":
        $info->type = "JSON";
        $info->mime = "application/json";
        break;

      case "otf":
        $info->type = "Font";
        $info->mime = "font/otf";
        break;

      case "pdf":
        $info->type = "PDF";
        $info->mime = "application/pdf";
        break;

      case "png":
        $info->type = "Image";
        $info->mime = "image/png";
        break;

      case "svg":
        $info->type = "Image";
        $info->mime = "image/svg+xml";
        break;

      case "ttf":
        $info->type = "Font";
        $info->mime = "font/ttf";
        break;

      case "txt":
        $info->type = "Text";
        $info->mime = "text/plain";
        break;

      case "webp":
        $info->type = "Image";
        $info->mime = "image/webp";
        break;

      case "woff":
        $info->type = "Font";
        $info->mime = "font/woff";
        break;

      case "woff2":
        $info->type = "Font";
        $info->mime = "font/woff2";
        break;

      case "xml":
        $info->type = "XML";
        $info->mime = "application/xml";
        break;

      case 'zip':
        $info->type = "Archive";
        $info->mime = "application/zip";
        break;

      case 'gz':
        $info->type = "Archive";
        $info->mime = "application/gzip";
        break;

      case 'mp3':
        $info->type = "Audio";
        $info->mime = "audio/mpeg";
        break;

      case 'mp4':
        $info->type = "Video";
        $info->mime = "video/mp4";
        break;

      default:
        $info = false;
        break;
    }

    if ($info !== false) {
      $info->fa = $fa[$info->type];
    }


    return $info;
  }
}
