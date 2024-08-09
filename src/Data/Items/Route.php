<?php

namespace Adept\Data\Items;

defined('_ADEPT_INIT') or die();

class Route extends \Adept\Abstract\Data\Items
{

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

  public bool $sitemap;
  public bool $get;
  public bool $post;
  public bool $email;
  public bool $public;
  public bool $block;

  //public function getList(string $query = '', array $where = [], array $params = []): array
  public function getList(string $query = '', array $where = [], array $params = [], bool $recursive = false): array
  {
    $query = 'SELECT a.* FROM `Route` AS `a`';

    return parent::getList($query, $where, $params);
  }
}
