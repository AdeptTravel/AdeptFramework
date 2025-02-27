<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form\DropDown;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Secure;
use Adept\Document\HTML\Elements\Form\DropDown\Route\Host;
use Adept\Document\HTML\Elements\Form\Filter;
use Adept\Document\HTML\Elements\Form\Textbox\Search;
use Adept\Document\HTML\Elements\P;
use Adept\Document\HTML\Elements\Table\Sortable;
use Adept\Helper\Arrays;

// Shortcuts

$get = Application::getInstance()->session->request->data->get;

// Data
$table = $this->getTable();

// Filter Element
$filter = new Filter();

$filter->children[] = new Search([
  'name'        => 'title',
  'placeholder' => 'Search',
  'value'       => $get->getString('title', '')
]);

$filter->children[] = new Host([
  'name'        => 'host',
  'placeholder' => 'Host',
  'emptyValue'  => '-- Host --',
  'value'       => (($get->exists('host')) ? $get->getString('host') : Application::getInstance()->conf->site->host[0])
]);

$filter->children[] = new Dropdown([
  'name'        => 'status',
  'placeholder' => 'Status',
  'allowEmpty'   => true,
  'emptyDisplay' => '-- Status --',
  'value'       => (string)(($get->exists('status')) ? $get->getInt('status', 0) : ''),
  'values'      => Arrays::ValueToArray(['Active', 'Inactive', 'Trash'])
]);

$filter->children[] = new Secure([
  'name'        => 'secure',
  'placeholder' => 'Secure',
  'allowEmpty'   => true,
  'emptyDisplay' => '-- Secure --',
  'value'       => (string)(($get->exists('secure')) ? $get->getInt('secure', 0) : '')
]);

echo $filter->getBuffer();

$sortable = new Sortable([], $table->getData());

$sortable->addCol('id', 'ID');
$sortable->addCol('status', 'Status', ['fa-solid', 'fa-circle-check']);
$sortable->addCol('title', 'Menu', ['main'], true);
$sortable->addCol('isSecure', 'Secure', ['fa-solid', 'fa-lock']);
$sortable->addCol('host', 'Host');
$sortable->addCol('createdAt', 'Created');

echo $sortable->getBuffer();
