<?php

namespace Adept\Module\Menu\Helper;

defined('_ADEPT_INIT') or die();

use Adept\Document\HTML\Elements\A;
use Adept\Document\HTML\Elements\I;
use Adept\Document\HTML\Elements\Img;
use Adept\Document\HTML\Elements\Li;
use Adept\Document\HTML\Elements\Nav;
use Adept\Document\HTML\Elements\Span;
use Adept\Document\HTML\Elements\Ul;

class Menu
{

  public static function getMenu(array $items, int $parent = 0): Ul|null
  {
    $menu = new Ul();

    for ($i = 0; $i < count($items); $i++) {
      if ($items[$i]->parent == $parent) {
        $menu->children[] = Menu::getItem($i, $items);
      }
    }

    if (empty($menu->children)) {
      $menu = null;
    }

    return $menu;
  }

  public static function getItem(int $index, array $items): Li
  {
    $item = $items[$index];

    $li = new Li();
    $url = '';

    if (!empty($item->route)) {
      $url = $item->route;
    } else if (!empty($item->url)) {
      $url = $item->url;
    }

    if (!empty($url)) {
      $a = new A([
        'href' => $url,
        'text' => $item->title
      ]);

      //if (!empty($item->fa)) {
      $li->children[] = new I(['css' => explode(' ', $item->fa)]);
      //}

      if (!empty($item->image)) {
        $a->children[] = new Img([
          'alt' => ((!empty($item->imageAlt)) ? $item->alt : $items[$i]->title)
        ]);
      }

      $li->children[] = $a;
    } else {
      $li->children[] = new I(['css' => explode(' ', $item->fa)]);
      $li->children[] = new Span(['text' => $item->title]);
    }

    if (($submenu = Menu::getMenu($items, $item->id)) != null) {
      $li->children[] = $submenu;
    }

    return $li;
  }
}
