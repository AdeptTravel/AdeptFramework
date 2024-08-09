<?php

namespace Adept\Data\Items\Menu;

defined('_ADEPT_INIT') or die();

use \Adept\Application;

class Item extends \Adept\Abstract\Data\Items
{
  public string $sort = 'order';

  public int $menu;
  public int $parent;
  public int $route;
  public string $url;
  public string $title;
  public string $image;
  public string $imageAlt;
  public string $fa;
  public string $css;
  public string $params;
  public int $order;

  protected function getRecursiveQuery(): string
  {
    $query  = "WITH RECURSIVE cte AS (";
    $query .= "  SELECT ";
    $query .= "    i.id AS `id`,";
    $query .= "    i.menu AS menu,";
    $query .= "    m.title AS `menutitle`,";
    $query .= "    i.parent,";
    $query .= "    r.route AS `route`,";
    $query .= "    i.url,";
    $query .= "    i.title,";
    $query .= "    i.image,";
    $query .= "    i.imageAlt,";
    $query .= "    i.fa,";
    $query .= "    i.css,";
    $query .= "    i.params,";
    $query .= "    i.status,";
    $query .= "    i.`order`,";
    $query .= "    CAST(i.order AS CHAR(200)) AS `path`,";
    $query .= "    0 AS `level`";
    $query .= "  FROM ";
    $query .= "    `MenuItem` AS i";
    $query .= "  LEFT JOIN ";
    $query .= "    `Route` AS r ON i.route = r.id";
    $query .= "  INNER JOIN ";
    $query .= "     `Menu` AS m ON i.menu = m.id";
    $query .= "  WHERE ";
    $query .= "     i.parent = 0";

    $query .= " UNION ALL";

    $query .= " SELECT ";
    $query .= "   `i`.`id` AS `id`,";
    $query .= "    i.menu AS menu,";
    $query .= "   `m`.`title` AS `menutitle`,";
    $query .= "   `i`.`parent`,";
    $query .= "   `r`.`route` AS `route`,";
    $query .= "   `i`.`url`,";
    $query .= "   `i`.`title`,";
    $query .= "   `i`.`image`,";
    $query .= "   `i`.`imageAlt`,";
    $query .= "   `i`.`fa`,";
    $query .= "   `i`.`css`,";
    $query .= "   `i`.`params`,";
    $query .= "   `i`.`status`,";
    $query .= "   `i`.`order`,";
    $query .= "   CONCAT(`cte`.`path`, '/', `i`.`order`) AS `path`,";
    $query .= "   `cte`.`level` + 1 AS `level`";
    $query .= " FROM ";
    $query .= "     `MenuItem` i";
    $query .= " LEFT JOIN ";
    $query .= "     `Route` `r` ON `i`.`route` = `r`.`id`";
    $query .= " INNER JOIN ";
    $query .= "     `Menu` `m` ON `i`.`menu` = `m`.`id`";
    $query .= " INNER JOIN ";
    $query .= "     `cte` ON `i`.`parent` = `cte`.`id`";
    $query .= " )";
    $query .= " ";

    $query .= " SELECT *";
    $query .= " FROM ";
    $query .= " `cte`";

    return $query;
  }
}
