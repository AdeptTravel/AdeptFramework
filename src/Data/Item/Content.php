<?php

/**
 * \Adept\Data\Item\Content
 *
 * The menu item data item
 *
 * @package    AdeptFramework.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */

namespace Adept\Data\Item;

defined('_ADEPT_INIT') or die();

use \Adept\Application\Database;
use \Adept\Application\Session\Request\Data\Post;

/**
 * \Adept\Data\Item\Content
 *
 * The menu item data item
 *
 * @package    AdeptFramework.Data
 * @author     Brandon J. Yaniz (brandon@adept.travel)
 * @copyright  2021-2024 The Adept Traveler, Inc., All Rights Reserved.
 * @license    BSD 2-Clause; See LICENSE.txt
 */
class Content extends \Adept\Abstract\Data\Item
{

  protected string $table = 'Content';
  protected string $index = 'route';

  protected array  $excludeKeys = ['path'];

  public int       $parent = 0;
  public int       $route;
  public string    $type;
  public string    $subtype;
  public string    $title;
  public string    $summary;
  public string    $content;
  public int       $image = 0;
  public object    $seo;
  public object    $media;
  public object    $params;
  public int       $status = 1;
  public \DateTime $publish;
  public \DateTime $archive;
  public \DateTime $created;
  public \DateTime $modified;
  public int       $order;

  public array     $path;

  public function getParent(): \Adept\Data\Item\Content
  {
    return new \Adept\Data\Item\Content($this->db, $this->parent);
  }

  public function getRoute(): \Adept\Data\Item\Route
  {
    return new \Adept\Data\Item\Route($this->route);
  }

  public function generateRoute(string $title, string $path) {}

  protected function getQuery(string $col = 'id'): string
  {
    $query  = 'WITH RECURSIVE ContentPath AS (';
    $query .= '  SELECT';
    $query .= '    id,';
    $query .= '    parent,';
    $query .= "    JSON_ARRAY(CONCAT(route, ':', title)) AS path";
    $query .= '  FROM';
    $query .= '    Content';
    $query .= '  WHERE';
    $query .= '    parent = 0';
    $query .= '  UNION ALL';
    $query .= '  SELECT';
    $query .= '    c.id,';
    $query .= '    c.parent,';
    $query .= "    JSON_ARRAY_APPEND(cp.path, '$', CONCAT(c.route, ':', c.title)) AS path";
    $query .= '  FROM';
    $query .= '    Content c';
    $query .= '  INNER JOIN';
    $query .= '    ContentPath cp ON c.parent = cp.id';
    $query .= ')';
    $query .= 'SELECT';
    $query .= '  c.*,';
    $query .= '  cp.path';
    $query .= 'FROM';
    $query .= '  Content c';
    $query .= 'LEFT JOIN';
    $query .= '  ContentPath cp ON c.id = cp.id;';

    return $query;
  }
}
