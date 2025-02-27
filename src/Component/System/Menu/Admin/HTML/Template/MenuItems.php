<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form\Filter;
use Adept\Document\HTML\Elements\Form\DropDown;
use Adept\Document\HTML\Elements\Form\DropDown\Menu;
use Adept\Document\HTML\Elements\Form\DropDown\Menu\Item;
use Adept\Helper\Arrays;

// Shortcuts
$get = Application::getInstance()->session->request->data->get;

// Data
$table = $this->getTable();
$data = $table->getData();

$filter = new Filter();

$filter->children[] = new Dropdown([
  'name'        => 'status',
  'placeholder' => 'Status',
  'allowEmpty'   => true,
  'emptyDisplay' => '-- Status --',
  'value'       => (string)(($get->exists('status')) ? $get->getString('status', 0) : ''),
  'values'      => Arrays::ValueToArray(['Active', 'Block', 'Inactive', 'Trash'])
]);

$filter->children[] = new Menu([
  'name'        => 'menu',
  'placeholder' => 'Menu',
  'allowEmpty'   => true,
  'emptyValue'  => '-- Menu --',
  'value'       => $get->getString('menu', '')
]);

$filter->children[] = new Item([
  'name'        => 'parent',
  'placeholder' => 'Parent',
  'allowEmpty'   => true,
  'emptyDisplay' => '-- Parent --',
  'value'       => $get->getString('parent', '')
]);

$filter->children[] = new Dropdown([
  'name'        => 'level',
  'placeholder' => 'Level',
  'allowEmpty'   => true,
  'emptyDisplay' => '-- Level --',
  'value'       => (string)(($get->exists('level')) ? $get->getString('level', 0) : ''),
  'values'      => [0 => '1', 1 => '2', 2 => '3', 3 => '4', 4 => '5', 5 => '6', 6 => '7', 7 => '8', 8 => '9']
]);

echo $filter->getBuffer();

for ($i = 0; $i < count($data); $i++) {
  if (!empty($data[$i]->route)) {
    $data[$i]->url = '/' . $data[$i]->routeRoute;
  }
}

$sortable = new \Adept\Document\HTML\Elements\Table\Sortable([
  'reorder'   => true,
  'recursive' => true,
  'select'    => true
], $table->getData());

$sortable->addCol('status', 'Status', ['fa-solid', 'fa-circle-check']);
$sortable->addCol('type', 'Type');
$sortable->addCol('title', 'Menu Item', ['main'], true);
$sortable->addCol('link', 'Link');
$sortable->addCol('menuTitle', 'Menu');
$sortable->addCol('id', 'ID');

echo $sortable->getBuffer();
