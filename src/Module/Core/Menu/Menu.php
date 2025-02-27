<?php

namespace Adept\Module\Core\Menu;

defined('_ADEPT_INIT') or die();

use Adept\Application;
use Adept\Data\Table\Menu\Item;
use Adept\Document\HTML\Elements\Nav;
use Adept\Module\Menu\Helper\Menu as Helper;
use Adept\Document\HTML\Elements\A;
use Adept\Document\HTML\Elements\Li;
use Adept\Document\HTML\Elements\Span;
use Adept\Document\HTML\Elements\Ul;

class Menu extends \Adept\Abstract\Document\HTML\Module
{
  public string $menu = '';

  public function getBuffer(): string
  {

    $buffer = '';

    if (!empty($this->menu)) {

      $data = Application::getInstance()->html->menu->getMenu($this->menu);

      if (!empty($data)) {
        $nav = new Nav();
        $nav->children[] = $this->getMenu($data);
        $buffer .= $nav->getBuffer();
      }
    }

    return $buffer;
  }

  public function getMenu(array $items, int $parent = 0): Ul|null
  {
    $menu = new Ul();

    for ($i = 0; $i < count($items); $i++) {
      if ($items[$i]->parentId == $parent) {
        $menu->children[] = $this->getItem($i, $items);
      }
    }

    if (empty($menu->children)) {
      $menu = null;
    }

    return $menu;
  }

  public function getItem(int $index, array $items): Li
  {
    $item = $items[$index];

    $li = new Li();
    $url = '';

    if (!empty($item->routeRoute)) {
      $url = $item->routeRoute;
    } else if (!empty($item->url)) {
      $url = $item->url;
    }

    if (!empty($url)) {
      $a = new A([
        'href' => $url,
        'text' => $item->title
      ]);

      $li->children[] = $a;
    } else {
      $li->children[] = new Span(['text' => $item->title]);
    }

    if (($submenu = $this->getMenu($items, $item->id)) != null) {
      $li->children[] = $submenu;
    }

    return $li;
  }
}
