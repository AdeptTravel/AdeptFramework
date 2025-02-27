<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Div;
use Adept\Document\HTML\Elements\Form\DropDown;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Block;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Email;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Get;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Post;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Secure;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Sitemap;
use Adept\Document\HTML\Elements\Form\DropDown\Route\Area;
use Adept\Document\HTML\Elements\Form\DropDown\Route\Component;
use Adept\Document\HTML\Elements\Form\DropDown\Route\Host;
use Adept\Document\HTML\Elements\Form\DropDown\Route\Template;
use Adept\Document\HTML\Elements\Form\DropDown\Route\Type;
use Adept\Document\HTML\Elements\Form\DropDown\Route\View;
use Adept\Document\HTML\Elements\Form\Filter;
use Adept\Document\HTML\Elements\Form\Textbox\Search;
use Adept\Document\HTML\Elements\P;
use Adept\Document\HTML\Elements\Table\Sortable;
use Adept\Helper\Arrays;

// Shortcuts
$app = Application::getInstance();
$get = Application::getInstance()->session->request->data->get;

// Data
$table = $this->getTable();

// Filter Element
$filter = new Filter();

$filter->children[] = new Search([
  'name'        => 'route',
  'placeholder' => 'Search',
  'value'       => $get->getString('route', '')
]);

$filter->children[] = new Dropdown([
  'name'        => 'status',
  'placeholder' => 'Status',
  'allowEmpty'   => true,
  'emptyDisplay' => '-- Status --',
  'value'       => (string)(($get->exists('status')) ? $get->getString('status', 0) : ''),
  'values'      => Arrays::ValueToArray(['Active', 'Block', 'Inactive', 'Trash'])
]);

if (count(Application::getInstance()->conf->site->host) > 1) {
  $filter->children[] = new Host([
    'name'        => 'host',
    'placeholder' => 'Host',
    'emptyValue'  => '-- Host --',
    'value'       => (($get->exists('host')) ? $get->getString('host') : Application::getInstance()->conf->site->host[0])
  ]);
}

$filter->children[] = new Type([
  'name'        => 'type',
  'placeholder' => 'Type',
  'emptyValue'  => '-- Type --',
  'value'       => (($get->exists('type')) ? $get->getString('type') : '')
]);

$filter->children[] = new Component([
  'name'        => 'component',
  'placeholder' => 'Component',
  'emptyValue'  => '-- Component --',
  'value'       => (($get->exists('component')) ? $get->getString('component') : '')
]);

$filter->children[] = new Area([
  'name'        => 'area',
  'placeholder' => 'Area',
  'emptyValue'  => '-- Area --',
  'value'       => (($get->exists('area')) ? $get->getString('area') : '')
]);

$filter->children[] = new View([
  'name'        => 'view',
  'placeholder' => 'View',
  'emptyValue'  => '-- View --',
  'value'       => (($get->exists('view')) ? $get->getString('view') : '')
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
  'allowEmpty'   => true,
  'emptyDisplay' => '-- Sitemap --',
  'value'       => (string)(($get->exists('sitemap')) ? $get->getInt('sitemap', 0) : '')
]);

$filter->children[] = new Get([
  'name'        => 'allowGet',
  'placeholder' => 'Get',
  'allowEmpty'   => true,
  'emptyDisplay' => '-- Get --',
  'value'       => (string)(($get->exists('get')) ? $get->getInt('get', 0) : '')
]);

$filter->children[] = new Post([
  'name'        => 'allowPost',
  'placeholder' => 'Post',
  'allowEmpty'   => true,
  'emptyDisplay' => '-- Post --',
  'value'       => (string)(($get->exists('post')) ? $get->getInt('post', 0) : '')
]);

$filter->children[] = new Email([
  'name'        => 'allowEmail',
  'placeholder' => 'Email',
  'allowEmpty'   => true,
  'emptyDisplay' => '-- Email --',
  'value'       => (string)(($get->exists('email')) ? $get->getInt('email', 0) : '')
]);

$filter->children[] = new Secure([
  'name'        => 'isSecure',
  'placeholder' => 'Secure',
  'allowEmpty'   => true,
  'emptyDisplay' => '-- Secure --',
  'value'       => (string)(($get->exists('secure')) ? $get->getInt('secure', 0) : '')
]);

echo $filter->getBuffer();

$sortable = new Sortable([], $table->getData());

$sortable->addCol('id', 'ID');
$sortable->addCol('status', 'Status', ['fa-solid', 'fa-circle-check']);

if (count(Application::getInstance()->conf->site->host) > 1) {
  $sortable->addCol('host', 'Host');
}
$sortable->addCol('route', 'Route', ['main'], true);
$sortable->addCol('type', 'Type');
$sortable->addCol('component', 'Component');
$sortable->addCol('area', 'Area');
$sortable->addCol('view', 'Option');
$sortable->addCol('template', 'Template');
$sortable->addCol('sitemap', 'Sitemap', ['fa-solid', 'fa-sitemap']);
$sortable->addCol('allowGet', 'Get', ['fa-solid', 'fa-upload']);
$sortable->addCol('allowPost', 'Post', ['fa-solid', 'fa-upload']);
$sortable->addCol('allowEmail', 'Email', ['fa-solid', 'fa-envelope']);
$sortable->addCol('isSecure', 'Secure', ['fa-solid', 'fa-lock']);
$sortable->addCol('isCacheable', 'Cache', ['fa-solid', 'fa-hard-drive']);

echo $sortable->getBuffer();
