<?php

use Adept\Document\HTML\Elements\Form;
use Adept\Document\HTML\Elements\Form\Row;
use Adept\Document\HTML\Elements\Input\Toggle;
use Adept\Document\HTML\Elements\Form\Image;
use Adept\Document\HTML\Elements\Form\Row\Input;
use Adept\Document\HTML\Elements\Form\Row\Input\DateTime;
use Adept\Document\HTML\Elements\Form\Row\DropDown;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Menu;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Menu\Item;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Route;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Status;


$form = new Form([
  'method' => 'post',
]);

$item = $this->getItem();

$form->children[] = new Status([
  'value' => $item->status
]);

//$form->children[] = new Row(['label' => 'Status'], [new Toggle([
//  'name' => 'status',
//  'checked' => ($item->status == 1)
//])]);

$form->children[] = new Input([
  'required' => true,
  'label' => 'Title',
  'name' => 'title',
  'value' => $item->title,
  'placeholder' => 'Title'
]);

$form->children[] = new Menu($this->app->db, [
  'required'    => true,
  'name'        => 'menu',
  'value'       => ((isset($item->menu)) ? $item->menu : 0),
  'placeholder' => 'Menu'
]);

$form->children[] = new Item($this->app->db, [
  'label'       => 'Parent',
  'name'        => 'parent',
  'value'       => ((isset($item->parent)) ? $item->parent : 0),
  'placeholder' => 'Parent'
]);

$form->children[] = new DropDown([
  'label'      => 'Link Type',
  'name'       => 'linktype',
  'value'      => (!empty($item->url)) ? 'Url' : 'Route',
  'allowEmpty' => false,
  'filter'     => false,
  'values'     => [
    'Route'    => 'Route',
    'Url'      => 'Url',
    'Title'    => 'Title'
  ]
]);

$form->children[] = new Route($this->app->db, [
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
  'value'       => $item->url,
  'placeholder' => 'Url'
]);

$form->children[] = new Row([
  'required' => true,
  'label'    => ' Image'
], [new Image(
  [
    'name'  => 'image',
    'value' => $item->image,
  ]
)]);

$form->children[] = new Input([
  'required'    => true,
  'label'       => 'Image Alt',
  'name'        => 'imageAlt',
  'value'       => $item->imageAlt,
  'placeholder' => 'Image Alt'
]);

$form->children[] = new Input([
  'required'    => true,
  'label'       => 'Font Awesome',
  'name'        => 'fa',
  'value'       => $item->fa,
  'placeholder' => 'Font Awesome'
]);

$form->children[] = new Input([
  'required'    => true,
  'label'       => 'CSS Class',
  'name'        => 'css',
  'value'       => $item->css,
  'placeholder' => 'CSS Class'
]);


$form->children[] = new DateTime(
  [
    'label'    => 'Created',
    'name'     => 'created',
    'value'    => (!empty($item->created)) ? $item->created : '0000-00-00 00:00:00',
    'disabled' => true,
  ]
);

echo $form->getBuffer();
