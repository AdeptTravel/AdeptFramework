<?php

defined('_ADEPT_INIT') or die();

use Adept\Application;
use Adept\Data\Item\Menu;
use Adept\Data\Table\Menu\Item;
use Adept\Document\HTML\Elements\Nav;
use Adept\Module\Menu\Helper\Menu as Helper;

if (!empty($args['menu'])) {

  $items = new Item();
  $items->menuTitle = $args['menu'];
  $items->status = 'Active';
  $data = $items->getData();
  //die('<pre>' . print_r($data, true));
  if (!empty($data)) {
    $nav = new Nav();
    $nav->children[] = Helper::getMenu($data);
    echo $nav->getBuffer();
  }
}
