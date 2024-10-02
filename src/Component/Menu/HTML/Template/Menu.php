<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form;
use Adept\Document\HTML\Elements\Form\Row;
use Adept\Document\HTML\Elements\Form\Row\DropDown;
use Adept\Document\HTML\Elements\Form\Row\Input;
use Adept\Document\HTML\Elements\Form\Row\Input\DateTime;
use Adept\Document\HTML\Elements\Input\Hidden;
use Adept\Document\HTML\Elements\Input\Toggle;
use Adept\Helper\Arrays;

// Shortcuts
$app  = Application::getInstance();
$get  = $app->session->request->data->get;
$head = $app->html->head;
$item = $this->getItem($get->getInt('id'));

$form = new Form([
  'method' => 'post',
]);

$form->children[] = new Hidden(['name' => 'id', 'value' => $item->id]);

$form->children[] = new Input([
  'label' => 'Title',
  'name' => 'title',
  'value' => $item->title,
  'placeholder' => 'Title',
  'required' => true
]);

$form->children[] = new Input([
  'label' => 'CSS Class',
  'name' => 'css',
  'value' => (empty($item->css)) ? '' : $item->css,
  'placeholder' => 'CSS Class',
  'required' => true
]);

$form->children[] = new Dropdown([
  'label'        => 'Status',
  'name'         => 'status',
  'value'        => (string)$item->status,
  'values'       => Arrays::ValueToArray(['Active', 'Inactive', 'Trash'])
]);

$form->children[] = new Row(['label' => 'Is Secure'], [new Toggle(['name' => 'secure', 'checked' => $item->isSecure])]);

$form->children[] = new DateTime([
  'name'     => 'created',
  'label'    => 'Created',
  'value'    => $item->createdOn,
  'readonly' => true
]);

echo $form->getBuffer();
