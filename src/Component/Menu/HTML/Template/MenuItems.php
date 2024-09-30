<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form\Filter;
use Adept\Document\HTML\Elements\Form\DropDown;
use Adept\Document\HTML\Elements\Form\DropDown\Menu;
use Adept\Document\HTML\Elements\Form\DropDown\Menu\Item;
use Adept\Helper\Arrays;

// Shortcuts
$app = Application::getInstance();
$get = $request->data->get;

// Data
$table = $this->getTable();
$data = $table->getData();

$filter = new Filter();

$filter->children[] = new Dropdown([
  'name'        => 'status',
  'placeholder' => 'Status',
  'value'       => (string)(($get->exists('status')) ? $get->getString('status', 0) : ''),
  'values'      => Arrays::ValueToArray(['Active', 'Block', 'Inactive', 'Trash'])
]);

$filter->children[] = new Menu([
  'name'        => 'menuId',
  'placeholder' => 'Menu',
  'emptyValue'  => '-- Menu --',
  'value'       => (($get->exists('menu')) ? $get->getInt('menu') : '')
]);

$filter->children[] = new Item([
  'name' => 'parentId',
  'placeholder' => 'Parent'
]);

echo $filter->getBuffer();

for ($i = 0; $i < count($data); $i++) {
  if (!empty($data[$i]->route)) {
    $data[$i]->url = '/' . $data[$i]->routeRoute;
  }
}

$sortable = new \Adept\Document\HTML\Elements\Table\Sortable(['reorder' => true], $data);
$sortable->reorder = true;
$sortable->recursive = true;
$sortable->select = true;

$sortable->addCol('status', 'Status', ['fa-solid', 'fa-circle-check']);
$sortable->addCol('title', 'Menu Item', ['main'], true);
$sortable->addCol('url', 'URL');
$sortable->addCol('menuTitle', 'Menu');
$sortable->addCol('id', 'ID');

echo $sortable->getBuffer();
