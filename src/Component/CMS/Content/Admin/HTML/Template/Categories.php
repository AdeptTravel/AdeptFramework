<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form\Filter;
use Adept\Document\HTML\Elements\Form\DropDown;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Status;
use Adept\Document\HTML\Elements\Table\Sortable;
use Adept\Document\HTML\Elements\Form\Textbox\Search;
use Adept\Helper\Arrays;

// Shortcuts
$get = Application::getInstance()->session->request->data->get;

// Data
$table = $this->getTable();

// Filter Element
$filter = new Filter();

$filter->children[] = new Search([
  'name'        => 'search',
  'placeholder' => 'Search',
  'value'       => $get->getString('search', '')
]);

$filter->children[] = new Dropdown([
  'name'         => 'status',
  'placeholder'  => 'Status',
  'value'        => (string)(($get->exists('status')) ? $get->getString('status', 0) : ''),
  'values'       => Arrays::ValueToArray(['Active', 'Archive', 'Inactive', 'Trash']),
  'allowEmpty'   => true,
  'emptyDisplay' => '-- Status --',
]);

echo $filter->getBuffer();

$sortable = new \Adept\Document\HTML\Elements\Table\Sortable([
  'reorder'   => true,
  'recursive' => true,
  'select'    => true
], $table->getData(true));


$sortable->addCol('status', 'Status', ['fa-solid', 'fa-circle-check']);
$sortable->addCol('title', 'Menu Item', ['main'], true);
$sortable->addCol('created', 'Created');
$sortable->addCol('id', 'ID');

echo $sortable->getBuffer();
