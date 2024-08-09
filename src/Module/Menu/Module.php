<?php

defined('_ADEPT_INIT') or die();

use Adept\Application;
use Adept\Data\Item\Menu;
use Adept\Data\Items\Menu\Item;
use Adept\Document\HTML\Elements\Nav;
use Adept\Module\Menu\Helper\Menu as Helper;

if (!empty($args['menu'])) {

  $menu = new Menu($args['menu']);

  if ($menu->id > 0) {
    $items = new Item();
    $items->menu = $menu->id;
    $items->recursive = true;
    $list = $items->getList();


    if (!empty($list)) {
      $nav = new Nav();
      $nav->children[] = Helper::getMenu($list);
      echo $nav->getBuffer();
    }
  }
}

/*
if (!empty($items)) {
  $buffer = '<nav class="' . $args['menu'] . '"><ul>';

  //if (array_key_exists($index, $this->menu)) {
  //$items = $this->menu[$index];
  for ($i = 0; $i < count($items); $i++) {
    $buffer .= '<li><a href="';
    $buffer .= (!empty($items[$i]->url)) ? $items[$i]->url : '/' . $items[$i]->route;
    $buffer .= '">';
    if (!empty($items[$i]->fa)) {
    }

    if (!empty($items[$i]->image)) {
      $buffer .= '<img src="' . $items[$i]->image . '"';

      if (!empty($items[$i]->title)) {
        $buffer .= 'alt="' . $items[$i]->title . '"';
      }

      $buffer .= '>';
    }

    if (!empty($items[$i]->image_alt)) {
      $buffer .= $items[$i]->image_alt;
    } else if (!empty($items[$i]->title)) {
      $buffer .= $items[$i]->title;
    }

    $buffer .= '</a></li>';
  }
  //}

  $buffer .= '</ul></nav>';

  echo $buffer;
}

die('List<pre>' . print_r($items, true));
/*
if (array_key_exists('menu', $args)) {
  $query  = "SELECT a.id, a.parent, c.route, a.url, a.title, a.image, a.imageAlt, a.fa";
  $query .= " FROM MenuItem AS a";
  $query .= " INNER JOIN Menu AS b ON a.menu = b.id";
  $query .= " LEFT JOIN Route AS c  ON a.route = c.id";
  $query .= " WHERE a.status = 1  AND b.status = 1";
  $query .= " AND (a.publishStart < NOW()";
  $query .= " AND (a.publishEnd > NOW() OR a.publishEnd = '0000-00-00 00:00:00'))";
  $query .= " AND b.title = ?";
  $query .= " ORDER BY b.id ASC, a.order ASC";

  //die($this->app->db->getQueryDebug($query, [$args['menu']]));

  $items = $this->app->db->getObjects($query, [$args['menu']]);
  //die('<pre>' . print_r($items, true));
  $buffer = '<nav class="' . $args['menu'] . '"><ul>';

  //if (array_key_exists($index, $this->menu)) {
  //$items = $this->menu[$index];
  for ($i = 0; $i < count($items); $i++) {
    $buffer .= '<li><a href="';
    $buffer .= (!empty($items[$i]->url)) ? $items[$i]->url : '/' . $items[$i]->route;
    $buffer .= '">';
    if (!empty($items[$i]->fa)) {
    }

    if (!empty($items[$i]->image)) {
      $buffer .= '<img src="' . $items[$i]->image . '"';

      if (!empty($items[$i]->title)) {
        $buffer .= 'alt="' . $items[$i]->title . '"';
      }

      $buffer .= '>';
    }

    if (!empty($items[$i]->image_alt)) {
      $buffer .= $items[$i]->image_alt;
    } else if (!empty($items[$i]->title)) {
      $buffer .= $items[$i]->title;
    }

    $buffer .= '</a></li>';
  }
  //}

  $buffer .= '</ul></nav>';

  echo $buffer;
}
*/