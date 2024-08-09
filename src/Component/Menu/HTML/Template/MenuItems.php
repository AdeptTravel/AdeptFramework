<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form\Filter;
use Adept\Document\HTML\Elements\P;
use Adept\Document\HTML\Elements\Div;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Status;
use Adept\Document\HTML\Elements\Form\DropDown\Menu;
use Adept\Document\HTML\Elements\Form\DropDown\Menu\Item;

// Shortcuts
$app      = Application::getInstance();
$head     = $app->html->head;
$request  = $app->session->request;
$get      = $request->data->get;
$path     = $request->url->path;
$sort     = $get->getString('sort', '');
$dir      = $get->getString('dir', 'asc');

$this->items->recursive = true;
$list = $this->items->getList();

$filter = new Filter();
$filter->children[] = new Status([
  'name'        => 'status',
  'placeholder' => 'Status',
  'value'       => (string)(($get->exists('status')) ? $get->getInt('status', 0) : '')
]);

$filter->children[] = new Menu([
  'name'        => 'menu',
  'placeholder' => 'Menu',
  'emptyValue'  => '-- Menu --',
  'value'       => (($get->exists('menu')) ? $get->getInt('menu') : '')
]);

$filter->children[] = new Item([
  'name' => 'parent',
  'placeholder' => 'Parent'
]);

echo $filter->getBuffer();

for ($i = 0; $i < count($list); $i++) {
  if (!empty($list[$i]->route)) {
    $list[$i]->url = '/' . $list[$i]->route;
  }
}

$sortable = new \Adept\Document\HTML\Elements\Table\Sortable(['reorder' => true], $list);
$sortable->reorder = true;
$sortable->recursive = true;

$sortable->addCol('status', 'Status', ['fa-solid', 'fa-circle-check']);
$sortable->addCol('title', 'Menu Item', ['main'], true);
$sortable->addCol('url', 'URL');
$sortable->addCol('menu', 'Menu');
$sortable->addCol('id', 'ID');

echo $sortable->getBuffer();
