<?php

namespace Adept\Data\Table;

defined('_ADEPT_INIT') or die();

class Route extends \Adept\Abstract\Data\Table
{
  protected string $table = 'Route';
  protected array $like = ['route'];

  public string $sort = 'route';

  protected array $empty = ['redirect'];
  public string $route;
  public string $category;
  public string $component;
  public string $option;
  public string $template;

  /**
   * Allow access to the HTML component
   *
   * @var bool
   */
  public bool $html;

  /**
   * Allow access to the JSON component
   *
   * @var bool
   */
  public bool $json;

  /**
   * Allow access to the XML component
   *
   * @var bool
   */
  public bool $xml;

  /**
   * Allow access to the CSV component
   *
   * @var bool
   */
  public bool $csv;

  /**
   * Allow access to the PDF component
   *
   * @var bool
   */
  public bool $pdf;

  /**
   * Allow access to the ZIP component
   *
   * @var bool
   */
  public bool $zip;

  /**
   * Route is in the sitemap
   *
   * @var bool
   */
  public bool $sitemap;

  /**
   * Route is allowed access to GET data
   *
   * @var bool
   */
  public bool $get;

  /**
   * Route is allowed access to POST data
   *
   * @var bool
   */
  public bool $post;

  /**
   * Route is allowed to send email
   *
   * @var bool
   */
  public bool $email;

  /**
   * Route is secured (requires login)
   *
   * @var bool
   */
  public bool $secure;

  /**
   * Route is allowed to cache the request
   *
   * @var bool
   */
  public bool $cache;

  /**
   * Route is blocked
   *
   * @var bool
   */
  public bool $block;

  protected function getItem(int $id): \Adept\Data\Item\Route
  {
    $item = new \Adept\Data\Item\Route($id);
    $item->loadFromId($id);
    return $item;
  }
}
