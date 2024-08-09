<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form;
use Adept\Document\HTML\Elements\Form\Filter;
use Adept\Document\HTML\Elements\P;
use Adept\Document\HTML\Elements\Div;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Status;
use Adept\Document\HTML\Elements\Form\DropDown\Route\Component;
use Adept\Document\HTML\Elements\Form\DropDown\Route\Option;
use Adept\Document\HTML\Elements\Form\DropDown\Route\Template;
use Adept\Document\HTML\Elements\Table\Sortable;

// Shortcuts

$app      = Application::getInstance();
$head     = $app->html->head;
$list     = $this->items->getList();
$request  = $app->session->request;
$get      = $request->data->get;
$path     = $request->url->path;
$sort     = $get->getString('sort', '');
$dir      = $get->getString('dir', 'asc');

$head->css->addFile('form.table.css');
$head->css->addFile('form.filter.css');
$head->javascript->addFile('form.table.js');

$filter = new Filter();

$filter->children[] = new Status([
  'name'        => 'status',
  'placeholder' => 'Status',
  'value'       => (string)(($get->exists('status')) ? $get->getInt('status', 0) : '')
]);

$filter->children[] = new Component([
  'name'        => 'component',
  'placeholder' => 'Component',
  'emptyValue'  => '-- Component --',
  'value'       => (($get->exists('component')) ? $get->getInt('component') : '')
]);

$filter->children[] = new Option([
  'name'        => 'option',
  'placeholder' => 'Option',
  'emptyValue'  => '-- Option --',
  'value'       => (($get->exists('option')) ? $get->getInt('option') : '')
]);

$filter->children[] = new Template([
  'name'        => 'template',
  'placeholder' => 'Template',
  'emptyValue'  => '-- Template --',
  'value'       => (($get->exists('template')) ? $get->getInt('template') : '')
]);


echo $filter->getBuffer();

$sortable = new Sortable([], $this->items->getList());

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
