<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form;
use Adept\Document\HTML\Elements\Form\Image;
use Adept\Document\HTML\Elements\Form\Row;
use Adept\Document\HTML\Elements\Form\Row\DropDown;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Menu;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Menu\Item;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Route;
use Adept\Document\HTML\Elements\Form\Row\Input;
use Adept\Document\HTML\Elements\Form\Row\Input\DateTime;
use Adept\Document\HTML\Elements\Input\Toggle;
use Adept\Helper\Arrays;

// Shortcuts
$app  = Application::getInstance();
$get  = $app->session->request->data->get;
$post = $app->session->request->data->post;
$head = $app->html->head;
$item = $this->getItem($get->getInt('id'));

$head->javascript->addFile('form.conditional.js');

$form = new Form([
  'method' => 'post',
]);

$form->children[] = new Input([
  'required' => true,
  'label' => 'Title',
  'name' => 'title',
  'value' => $item->title,
  'placeholder' => 'Title'
]);

$form->children[] = new Dropdown([
  'label'        => 'Status',
  'name'         => 'status',
  'value'        => (string)$item->status,
  'values'       => Arrays::ValueToArray(['Active', 'Block', 'Inactive', 'Trash'])
]);

$form->children[] = new Menu([
  'required'    => true,
  'name'        => 'menu',
  'value'       => ((isset($item->menu)) ? $item->menu : 0),
  'placeholder' => 'Menu'
]);

$form->children[] = new Item([
  'label'       => 'Parent',
  'name'        => 'parent',
  'value'       => ((isset($item->parent)) ? (string)$item->parent : "0"),
  'placeholder' => 'Parent'
]);

$form->children[] = new DropDown([
  'label'      => 'Link Type',
  'name'       => 'linktype',
  'value'      => $item->type,
  'allowEmpty' => false,
  'filter'     => false,
  'values'     => Arrays::ValueToArray(['Route', 'External Url', 'Internal URL', 'Heading'])
]);

$form->children[] = new Route([
  'required'    => true,
  'label'       => 'Route',
  'showOn'      => ['linktype=Route'],
  'name'        => 'route',
  'value'       => ((isset($item->route)) ? $item->route : 0),
  'placeholder' => 'Route'
]);

$form->children[] = new Input([
  'required'    => true,
  'label'       => 'URL',
  'showOn'      => ['linktype=Url'],
  'name'        => 'url',
  'value'       => ($item->type == 'Url') ? $item->urlId : '',
  'placeholder' => 'Url'
]);

// TODO - Add in image support

$form->children[] = new Input([
  'required'    => true,
  'label'       => 'CSS Class',
  'name'        => 'css',
  'value'       => (!empty($item->css)) ? $item->css : '',
  'placeholder' => 'CSS Class'
]);


$form->children[] = new DateTime(
  [
    'label'    => 'Created',
    'name'     => 'created',
    'value'    => (!empty($item->created)) ? $item->created : '0000-00-00 00:00:00',
    'readonly' => true,
  ]
);

echo $form->getBuffer();
