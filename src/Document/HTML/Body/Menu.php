<?php

namespace AdeptCMS\Document\HTML\Body;

defined('_ADEPT_INIT') or die();

class Menu
{
  protected $app;
  protected $menu;
  protected $route;

  public function __construct(\AdeptCMS\Application &$app)
  {
    $this->app = $app;
    $this->menu = ['main' => []];
    $this->route = [];

    $query  = "SELECT b.title as `menu_title`, b.alias, a.id, a.parent, a.title as `title`, a.image, a.url, a.route, a.params";
    $query .= " FROM menu_item AS a";
    $query .= " INNER JOIN menu AS b ON a.menu = b.id";
    $query .= " WHERE a.publish = 1 AND b.publish = 1";
    $query .= " AND a.publish < NOW()";
    $query .= " AND a.unpublish > NOW() OR a.unpublish = '0000-00-00 00:00:00'";
    $query .= " ORDER BY b.id ASC, a.order ASC";

    $data = $app->db->getObjects($query, []);

    if ($data !== false && count($data) > 0) {
      // Store keys to remove during cleanup
      $remove = [];
      $params = (object)[
        'fa' => '',
        'title_show' => true
      ];

      // Move Children
      foreach ($data as $key => $obj) {
        $json = $obj->params;
        $obj->params = $params;

        foreach (json_decode($json) as  $k => $v) {
          $obj->params->$k = $v;
        }

        if ($obj->parent > 0) {
          foreach ($data as $parent) {
            if ($parent->id == $obj->parent) {
              if (!isset($parent->children)) {
                $parent->children = [];
              }

              $parent->children[$obj->id] = $obj;

              $remove[] = $key;
              break;
            }
          }
        }

        if (!empty($obj->route)) {
          $this->route[$obj->url] = $obj->route;
        }
      }

      // Cleanup - Remove children from data array
      foreach ($remove as $key) {
        unset($data[$key]);
      }

      foreach ($data as $item) {
        if (array_key_exists($item->alias, $this->menu)) {
          $this->menu[$item->alias][] = $item;
        } else {
          $this->menu[$item->alias] = [$item];
        }
      }
    }

    //die('<pre>' . print_r($this->menu, true));
  }

  public function getItems(string $menu): array
  {
    return (array_key_exists($menu, $this->menu)) ? $this->menu[$menu] : [];
  }

  public function getRoute(string $search): string
  {
    return (array_key_exists($search, $this->route)) ? $this->route[$search] : '';
  }
}
