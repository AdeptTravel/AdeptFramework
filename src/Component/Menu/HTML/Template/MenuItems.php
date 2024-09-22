<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form\Filter;
use Adept\Document\HTML\Elements\P;
use Adept\Document\HTML\Elements\Div;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Status;
use Adept\Document\HTML\Elements\Form\DropDown\Menu;
use Adept\Document\HTML\Elements\Form\DropDown\Menu\Item;

// Shortcuts
$app = Application::getInstance();
$get = $request->data->get;

// Data
$table = $this->getTable();
$data = $table->getData();

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
//die('<pre>' . print_r($data, true));
for ($i = 0; $i < count($data); $i++) {
  if (!empty($data[$i]->route)) {
    $data[$i]->url = '/' . $data[$i]->routeRoute;
  }
}

$sortable = new \Adept\Document\HTML\Elements\Table\Sortable(['reorder' => true], $data);
$sortable->reorder = true;
$sortable->recursive = true;
$sortable->select = true;

/*
[id] => 3
            [menu] => 1
            [parent] => 2
            [route] => 18
            [url] => 
            [title] => Articles
            [image] => 
            [imageAlt] => 
            [fa] => 
            [css] => 
            [params] => 
            [order] => 0
            [status] => 0
            [created] => 0000-00-00 00:00:00
            [path] => 0 / 0
            [level] => 1
            [Menu.id] => 1
            [Menu.title] => Main Menu
            [Menu.css] => 
            [Menu.status] => 1
            [Menu.secure] => 1
            [Menu.created] => 2024-06-24 14:44:07
            [Route.id] => 18
            [Route.route] => content/article
            [Route.redirect] => 
            [Route.component] => Content
            [Route.option] => Articles
            [Route.template] => 
            [Route.html] => 1
            [Route.json] => 0
            [Route.xml] => 0
            [Route.csv] => 0
            [Route.pdf] => 0
            [Route.zip] => 0
            [Route.sitemap] => 0
            [Route.get] => 1
            [Route.post] => 0
            [Route.email] => 0
            [Route.secure] => 1
            [Route.cache] => 0
            [Route.status] => 1
            [Route.block] => 0
            [Route.created] => 2024-06-20 15:40:52
*/

$sortable->addCol('status', 'Status', ['fa-solid', 'fa-circle-check']);
$sortable->addCol('title', 'Menu Item', ['main'], true);
$sortable->addCol('url', 'URL');
$sortable->addCol('menuTitle', 'Menu');
$sortable->addCol('id', 'ID');

echo $sortable->getBuffer();
