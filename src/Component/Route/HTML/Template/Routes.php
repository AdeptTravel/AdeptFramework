<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Div;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Block;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Email;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Get;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Post;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Secure;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Sitemap;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Status;
use Adept\Document\HTML\Elements\Form\DropDown\Route\Component;
use Adept\Document\HTML\Elements\Form\DropDown\Route\Option;
use Adept\Document\HTML\Elements\Form\DropDown\Route\Template;
use Adept\Document\HTML\Elements\Form\Filter;
use Adept\Document\HTML\Elements\Form\Textbox\Search;
use Adept\Document\HTML\Elements\P;
use Adept\Document\HTML\Elements\Table\Sortable;


// Shortcuts
$app = Application::getInstance();
$get = $request->data->get;

// Data
$table = $this->getTable();

// Filter Element
$filter = new Filter();

$filter->children[] = new Search([
  'name'        => 'route',
  'placeholder' => 'Search',
  'value'       => $get->getString('route', '')
]);

$filter->children[] = new Status([
  'name'        => 'status',
  'placeholder' => 'Status',
  'value'       => (string)(($get->exists('status')) ? $get->getInt('status', 0) : '')
]);

$filter->children[] = new Component([
  'name'        => 'component',
  'placeholder' => 'Component',
  'emptyValue'  => '-- Component --',
  'value'       => (($get->exists('component')) ? $get->getString('component') : '')
]);

$filter->children[] = new Option([
  'name'        => 'option',
  'placeholder' => 'Option',
  'emptyValue'  => '-- Option --',
  'value'       => (($get->exists('option')) ? $get->getString('option') : '')
]);

$filter->children[] = new Template([
  'name'        => 'template',
  'placeholder' => 'Template',
  'emptyValue'  => '-- Template --',
  'value'       => (($get->exists('template')) ? $get->getString('template') : '')
]);

$filter->children[] = new Sitemap([
  'name'        => 'sitemap',
  'placeholder' => 'Sitemap',
  'value'       => (string)(($get->exists('sitemap')) ? $get->getInt('sitemap', 0) : '')
]);

$filter->children[] = new Get([
  'name'        => 'get',
  'placeholder' => 'Get',
  'value'       => (string)(($get->exists('get')) ? $get->getInt('get', 0) : '')
]);

$filter->children[] = new Post([
  'name'        => 'post',
  'placeholder' => 'Post',
  'value'       => (string)(($get->exists('post')) ? $get->getInt('post', 0) : '')
]);

$filter->children[] = new Email([
  'name'        => 'email',
  'placeholder' => 'Email',
  'value'       => (string)(($get->exists('email')) ? $get->getInt('email', 0) : '')
]);

$filter->children[] = new Secure([
  'name'        => 'secure',
  'placeholder' => 'Secure',
  'value'       => (string)(($get->exists('secure')) ? $get->getInt('secure', 0) : '')
]);

$filter->children[] = new Block([
  'name'        => 'block',
  'placeholder' => 'Block',
  'value'       => (string)(($get->exists('block')) ? $get->getInt('block', 0) : '')
]);

echo $filter->getBuffer();

$sortable = new Sortable([], $table->getData());
$sortable->addCol('id', 'ID');
$sortable->addCol('status', 'Status', ['fa-solid', 'fa-circle-check']);
$sortable->addCol('route', 'Route', ['main'], true);
$sortable->addCol('component', 'Component');
$sortable->addCol('option', 'Option');
$sortable->addCol('template', 'Template');
$sortable->addCol('sitemap', 'Sitemap', ['fa-solid', 'fa-sitemap']);
$sortable->addCol('get', 'Get', ['fa-solid', 'fa-upload']);
$sortable->addCol('post', 'Post', ['fa-solid', 'fa-upload']);
$sortable->addCol('email', 'Email', ['fa-solid', 'fa-envelope']);
$sortable->addCol('secure', 'Secure', ['fa-solid', 'fa-lock']);
$sortable->addCol('block', 'Block', ['fa-solid', 'fa-shield']);

echo $sortable->getBuffer();
