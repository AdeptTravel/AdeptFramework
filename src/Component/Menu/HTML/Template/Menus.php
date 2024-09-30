<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form\DropDown;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Secure;
use Adept\Document\HTML\Elements\Form\Filter;
use Adept\Document\HTML\Elements\Form\Textbox\Search;
use Adept\Document\HTML\Elements\P;
use Adept\Document\HTML\Elements\Table\Sortable;
use Adept\Helper\Arrays;

// Shortcuts
$app = Application::getInstance();
$get = $request->data->get;

// Data
$table = $this->getTable();

// Filter Element
$filter = new Filter();

$filter->children[] = new Search([
  'name'        => 'title',
  'placeholder' => 'Search',
  'value'       => $get->getString('title', '')
]);

$filter->children[] = new Dropdown([
  'name'        => 'status',
  'placeholder' => 'Status',
  'value'       => (string)(($get->exists('status')) ? $get->getInt('status', 0) : ''),
  'values'      => Arrays::ValueToArray(['Active', 'Inactive', 'Trash'])
]);

$filter->children[] = new Secure([
  'name'        => 'isSecure',
  'placeholder' => 'Secure',
  'value'       => (string)(($get->exists('secure')) ? $get->getInt('secure', 0) : '')
]);

echo $filter->getBuffer();

$sortable = new Sortable([], $table->getData());

$sortable->addCol('id', 'ID');
$sortable->addCol('status', 'Status', ['fa-solid', 'fa-circle-check']);
$sortable->addCol('title', 'Menu', ['main'], true);
$sortable->addCol('secure', 'Secure', ['fa-solid', 'fa-lock']);
$sortable->addCol('created', 'Created');

echo $sortable->getBuffer();
