<?php

defined('_ADEPT_INIT') or die('No Access');

use Adept\Application;
use Adept\Document\HTML\Elements\Form\Filter;
use Adept\Document\HTML\Elements\Form\DropDown;
use Adept\Document\HTML\Elements\Form\DropDown\Content\Author;
use Adept\Document\HTML\Elements\Form\DropDown\Content\Category;
use Adept\Document\HTML\Elements\Form\DropDown\Content\Tag;
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

$filter->children[] = new Category([
  'name'         => 'categoryId',
  'placeholder'  => 'Category',
  'value'        => (string)(($get->exists('categoryId')) ? $get->getString('categoryId', 0) : ''),
  'allowEmpty'   => true,
  'emptyDisplay' => '-- Category --',
]);

$filter->children[] = new Author([
  'name'         => 'authorId',
  'placeholder'  => 'Author',
  'value'        => (string)(($get->exists('authorId')) ? $get->getString('authorId', 0) : ''),
  'allowEmpty'   => true,
  'emptyDisplay' => '-- Author --',
]);

echo $filter->getBuffer();

$sortable = new Sortable([
  'reorder' => true
], $table->getData());

$sortable->addCol('status', 'Status', ['fa-solid', 'fa-circle-check']);
$sortable->addCol('title', 'Title', ['main'], true);
$sortable->addCol('created', 'Created');
$sortable->addCol('id', 'ID');

echo $sortable->getBuffer();
