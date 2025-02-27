<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form;
use Adept\Document\HTML\Elements\Form\Row\DropDown;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Menu;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Menu\Item;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Route;
use Adept\Document\HTML\Elements\Form\Row\Input;
use Adept\Document\HTML\Elements\Form\Row\Input\DateTime;
use Adept\Document\HTML\Elements\Input\Hidden;
use Adept\Helper\Arrays;

// Shortcuts
$app  = Application::getInstance();
$get  = $app->session->request->data->get;
$post = $app->session->request->data->post;
$head = $app->html->head;
$item = $this->getItem($get->getInt('id'));

$head->javascript->addAsset('Core/Form/Conditional');

$form = new Form([
  'method' => 'post',
]);

if ($item->id > 0) {
  $form->children[] = new Hidden([
    'name' => 'id',
    'value' => $item->id,
  ]);
}


$form->children[] = new Input([
  'required' => true,
  'label' => 'Title',
  'name' => 'title',
  'value' => (empty($item->title)) ? '' : $item->title,
  'placeholder' => 'Title'
]);

$form->children[] = new Dropdown([
  'label'        => 'Status',
  'name'         => 'status',
  'value'        => (empty($item->status)) ? '' : (string)$item->status,
  'values'       => Arrays::ValueToArray(['Active', 'Block', 'Inactive', 'Trash'])
]);

$form->children[] = new Menu([
  'required'    => true,
  'name'        => 'menuId',
  'value'       => ((isset($item->menuId)) ? $item->menuId : 0),
  'placeholder' => 'Menu'
]);

$form->children[] = new Item([
  'label'       => 'Parent',
  'name'        => 'parentId',
  'value'       => ((isset($item->parentId) ? (string)$item->parentId : 0)),
  'placeholder' => 'Parent'
]);

$form->children[] = new DropDown([
  'label'      => 'Link Type',
  'name'       => 'type',
  'value'      => (!empty($item->type)) ? $item->type : '',
  'allowEmpty' => false,
  'filter'     => false,
  'values'     => Arrays::ValueToArray(['Heading', 'Route', 'External Url', 'Internal Url', 'Spacer'])
]);

$form->children[] = new Route([
  'required'    => true,
  'label'       => 'Route',
  'showOn'      => ['type=Route'],
  'name'        => 'routeId',
  'value'       => ((isset($item->routeId)) ? $item->routeId : 0),
  'placeholder' => 'Route'
]);

$form->children[] = new Input([
  'required'    => true,
  'label'       => 'URL',
  'showOn'      => ['type=Url'],
  'name'        => 'url',
  'value'       => (!empty($item->type) && $item->type == 'Url') ? $item->urlId : '',
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
    'value'    => (!empty($item->createdAt)) ? $item->createdAt->format('Y-m-d\TH:i:s') : '0000-00-00T00:00:00',
    'readonly' => true,
  ]
);

echo $form->getBuffer();
