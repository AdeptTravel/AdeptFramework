<?php

defined('_ADEPT_INIT') or die();

use \Adept\Application;


if (array_key_exists('menu', $args)) {
  $query  = "SELECT a.id, a.parent, c.route, a.url, a.title, a.image, a.image_alt, a.fa";
  $query .= " FROM MenuItem AS a";
  $query .= " INNER JOIN Menu AS b ON a.menu = b.id";
  $query .= " LEFT JOIN Route AS c  ON a.route = c.id";
  $query .= " WHERE a.status = 1  AND b.status = 1";
  $query .= " AND (a.publishStart < NOW()";
  $query .= " AND (a.publishEnd > NOW() OR a.publishEnd = '0000-00-00 00:00:00'))";
  $query .= " AND b.title = ?";
  $query .= " ORDER BY b.id ASC, a.order ASC";


$items = new \Adept\Data\Table\Menu\Item($this->app->db);
$items->load();

if (count($items->data) > 0) {

  //$items = $this->app->db->getObjects($query, [$args['menu']]);
  $html = '<nav class="' . $args['menu'] . '"><ul>';

  for ($i = 0; $i < count($items->data); $i++) {
    $item = &$items->data[$i];
    if ($item->parent == 0) {
      $html .= getMenuItemHTML($items->data, $item);
    }
  }

  $html .= '</ul></nav>';

  echo $html;
}

function getMenuItemHTML(array $items, object $item): string
{
  $html  = '<li>';
  $html .= '<a href="';

  if (!empty($item->url) || !empty($item->route)) {
    $html .= (!empty($item->url)) ? $item->url : '/' . $item->route;
  } else {
    $html .= '#';
  }

  $html .= '">';

  if (!empty($item->fa)) {
    $html .= '<i class="' . $item->fa . '"></i>';
  }

  if (!empty($item->image)) {
    $html .= '<img src="' . $item->image . '"';

    if (!empty($item->title)) {
      $html .= 'alt="' . $item->title . '"';
    }

    $html .= '>';
  }

  if (!empty($item->image_alt)) {
    $html .= $item->image_alt;
  } else if (!empty($item->title)) {
    $html .= $item->title;
  }

  if (!empty($item->url) || !empty($item->route)) {
    $html .= '</a>';
  }

  $hasChildren = false;

  for ($i = 0; $i < count($items); $i++) {
    if ($item->parent == $item->id) {
      if (!$hasChildren) {
        $html .= '<ul>';
        $hasChildren = true;
      }
      $html .= getMenuItemHTML($items, $item);
    }
  }

  if ($hasChildren) {
    $html .= '</ul>';
  }

  $html .= '</li>';

  return $html;
}
