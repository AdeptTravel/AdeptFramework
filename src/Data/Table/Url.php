<?php

namespace Adept\Data\Table;

defined('_ADEPT_INIT') or die();

/**
 * Undocumented class
 */
class Url extends \Adept\Abstract\Data\Items
{

  public string $sort     = 'url';

  /**
   * The full url with QueryString
   *
   * @var string
   */
  public string $raw;

  /**
   * The filtered URL
   *
   * @var string
   */
  public string $url;

  /**
   * The scheme ie. HTTP|HTTPS
   *
   * @var string
   */
  public string $scheme;

  /**
   * The host name
   *
   * @var string
   */
  public string $host;

  /**
   * The path
   *
   * @var string
   */
  public string $path;

  /**
   * The path seperated into an array
   *
   * @var array
   */
  public array $parts;

  /**
   * The file for the request, index.html is default
   *
   * @var string
   */
  public string $file;

  /**
   * The extension of the request ie. html|css etc.
   *
   * @var string
   */
  public string $extension;

  /**
   * Type of request 
   *
   * @var string
   */
  public string $type;

  /**
   * Mime type of the request
   *
   * @var string
   */
  public string $mime;
}
