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
   * Undocumented variable
   *
   * @var string
   */
  public string $url;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $scheme;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $host;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $path;

  /**
   * Undocumented variable
   *
   * @var array
   */
  public array $parts;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $file;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $extension;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $type;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $mime;

  /**
   * Undocumented variable
   *
   * @var bool
   */
  public bool $block;

  /**
   * Undocumented variable
   *
   * @var string
   */
  public string $created;
}
