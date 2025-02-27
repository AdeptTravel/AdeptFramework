<?php

namespace Adept\Data\Table;

defined('_ADEPT_INIT') or die();

class Route extends \Adept\Abstract\Data\Table
{
  protected string $table = 'Route';
  protected array $like = ['route'];

  public string $sort = 'route';

  /**
   * The host or domain name the route is associated with
   *
   * @var string
   */
  public string $host;

  /**
   * The route
   *
   * @param string
   */
  public string $route;

  public string $type;

  public string $area;

  /**
   * The component
   *
   * @var string
   */
  public string $component;

  /**
   * The component option aka what area of the component should be loaded
   *
   * @var string
   */
  public string $view;

  /**
   * The view template
   *
   * @var string
   */
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
   * Is the route in the sitemap
   *
   * @param bool
   */
  public bool $sitemap;

  /**
   * Allow get data
   *
   * @var bool
   */
  public bool $allowGet;

  /**
   * Allow post data
   *
   * @var bool
   */
  public bool $allowPost;

  /**
   * Allow the route to send emails
   *
   * @var bool
   */
  public bool $allowEmail;

  /**
   * Is the route publically viewable
   *
   * @var bool
   */
  public bool $isSecure;

  /**
   * Can the route be cached
   *
   * @var bool
   */
  public bool $isCacheable;


  public function getItem(int $id = 0): \Adept\Data\Item\Route
  {
    $item = new \Adept\Data\Item\Route();
    $item->loadFromId($id);
    return $item;
  }
}
