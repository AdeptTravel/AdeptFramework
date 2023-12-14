<?php

namespace Adept\Document\HTML\Body;

defined('_ADEPT_INIT') or die();

use \Adept\Application;

class Menu
{
  /**
   * Undocumented variable
   *
   * @var \Adept\Application
   */
  protected Application $app;

  /**
   * Undocumented variable
   *
   * @var array
   */
  protected array $menu;

  /**
   * Undocumented function
   *
   * @param  \Adept\Application $app
   */
  public function __construct(Application &$app)
  {
    $this->app = $app;
    $this->menu = ['main' => []];
    /*
    $query  = "SELECT a.id, b.alias, a.parent, c.route, a.url, a.title, a.image, a.fa";
    $query .= " FROM menu_item AS a";
    $query .= " INNER JOIN menu AS b ON a.menu = b.id";
    $query .= " INNER JOIN route AS c ON a.route = c.id";
    $query .= " WHERE a.status = 1 AND b.status = 1";
    $query .= " AND a.publish_start < NOW()";
    $query .= " AND a.publish_end > NOW() OR a.publish_end = '0000-00-00 00:00:00'";
    $query .= " ORDER BY b.id ASC, a.order ASC";
    */

    $query  = "SELECT a.id, b.alias, a.parent, c.route, a.url, a.title, a.image, a.image_alt, a.fa";
    $query .= " FROM menu_item AS a";
    $query .= " INNER JOIN menu AS b ON a.menu = b.id";
    $query .= " LEFT JOIN route AS c  ON a.route = c.id";
    $query .= " WHERE a.status = 1  AND b.status = 1";
    $query .= " AND (a.publish_start < NOW()";
    $query .= " AND (a.publish_end > NOW() OR a.publish_end = '0000-00-00 00:00:00'))";
    $query .= " ORDER BY b.id ASC, a.order ASC";

    $data = $app->db->getObjects($query, []);

    if ($data !== false && count($data) > 0) {
      for ($i = 0; $i < count($data); $i++) {
        $this->menu[$data[$i]->alias][] = (object)[
          'title' => $data[$i]->title,
          'url' => (!empty($data[$i]->url)) ? $data[$i]->url : $data[$i]->route,
          'image' => $data[$i]->image,
          'fa' => $data[$i]->fa
        ];
      }
    }
  }

  public function getBuffer(string $menu): string
  {
    $html = '<nav class="' . $menu . '"><ul>';

    if (array_key_exists($menu, $this->menu)) {
      $items = $this->menu[$menu];
      for ($i = 0; $i < count($items); $i++) {
        $html .= '<li><a href="' . $items[$i]->url . '">';
        if (!empty($items[$i]->fa)) {
        }

        if (!empty($items[$i]->image)) {
          $html .= '<img src="' . $items[$i]->image . '"';

          if (!empty($items[$i]->title)) {
            $html .= 'alt="' . $items[$i]->title . '"';
          }

          $html .= '>';
        }

        if (!empty($items[$i]->image_alt)) {
          $html .= $items[$i]->image_alt;
        } else if (!empty($items[$i]->title)) {
          $html .= $items[$i]->title;
        }

        $html .= '</a></li>';
      }
    }

    $html .= '</ul></nav>';

    return $html;
  }
}
