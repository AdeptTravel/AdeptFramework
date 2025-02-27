<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form;
use Adept\Document\HTML\Elements\Form\Row\DropDown;
use Adept\Document\HTML\Elements\Form\Row\Input;
use Adept\Document\HTML\Elements\Form\Row\Input\DateTime;
use Adept\Document\HTML\Elements\Input\Hidden;
use Adept\Helper\Arrays;

// Shortcuts
$app  = Application::getInstance();
$get  = $app->session->request->data->get;
$post = $app->session->request->data->post;

$item = $this->getItem($get->getInt('id'));

$form = new Form([
  'method' => 'post',
]);

$form->children[] = new Hidden(['name' => 'id', 'value' => $item->id]);

$form->children[] = new Input([
  'label' => 'Currency',
  'name' => 'currency',
  'value' => (isset($item->currency)) ? $item->currency : '',
  'placeholder' => 'Currency',
  'required' => true
]);

$form->children[] = new Dropdown([
  'label'        => 'Status',
  'name'         => 'status',
  'value'        => (string)$item->status,
  'values'       => Arrays::ValueToArray(['Active', 'Inactive', 'Trash'])
]);

$form->children[] = new Input([
  'label' => 'Currency Code',
  'name' => 'code',
  'value' => (isset($item->code)) ? $item->code : '',
  'placeholder' => 'Currency Code',
  'required' => true
]);

$form->children[] = new Input([
  'label' => 'Currency Symbol',
  'name' => 'symbol',
  'value' => (isset($item->symbol)) ? $item->symbol : '',
  'placeholder' => 'Currency Symbol',
  'required' => true
]);

$form->children[] = new Input([
  'label' => 'Subunit Ratio',
  'name' => 'subunitRatio',
  'value' => (isset($item->subunitRatio)) ? $item->subunitRatio : '',
  'placeholder' => 'Currency Subunit Ratio',
  'required' => true
]);

$form->children[] = new Input([
  'label' => 'Central Bank',
  'name' => 'centralBank',
  'value' => (isset($item->centralBank)) ? $item->centralBank : '',
  'placeholder' => 'Central Bank',
  'required' => true
]);



echo $form->getBuffer();
