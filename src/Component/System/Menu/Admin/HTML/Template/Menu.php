<?php

use Adept\Abstract\Configuration\App;
use Adept\Application;
use Adept\Document\HTML\Elements\Form;
use Adept\Document\HTML\Elements\Form\Row;
use Adept\Document\HTML\Elements\Form\Row\DropDown;
use Adept\Document\HTML\Elements\Form\Row\Input;
use Adept\Document\HTML\Elements\Form\Row\Input\DateTime;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Route\Host;
use Adept\Document\HTML\Elements\Input\Hidden;
use Adept\Document\HTML\Elements\Input\Toggle;
use Adept\Helper\Arrays;

// Shortcuts
$get = Application::getInstance()->session->request->data->get;
$item = $this->getItem($get->getInt('id'));

$form = new Form([
  'method' => 'post',
]);

$form->children[] = new Hidden(['name' => 'id', 'value' => $item->id]);

$form->children[] = new Host([
  'label' => 'Host',
  'name' => 'host',
  'value' => (isset($item->host)) ? $item->host : Application::getInstance()->conf->site->host[0],
  'placeholder' => 'Host',
]);


$form->children[] = new Input([
  'label' => 'Title',
  'name' => 'title',
  'value' => (isset($item->title)) ? $item->title : '',
  'placeholder' => 'Title',
  'required' => true
]);

$form->children[] = new Input([
  'label' => 'CSS Class',
  'name' => 'css',
  'value' => (!empty($item->css)) ? $item->css : '',
  'placeholder' => 'CSS Class',
  'required' => true
]);

$form->children[] = new Dropdown([
  'label'        => 'Status',
  'name'         => 'status',
  'value'        => (string)$item->status,
  'values'       => Arrays::ValueToArray(['Active', 'Inactive', 'Trash'])
]);

$form->children[] = new Row(['label' => 'Is Secure'], [new Toggle(['name' => 'secure', 'checked' => (isset($item->isSecure)) ? $item->isSecure : false])]);

$form->children[] = new DateTime([
  'name'     => 'created',
  'label'    => 'Created',
  'value'    => isset($item->createdAt) ? $item->createdAt->format('Y-m-d\TH:i:s') : '',
  'readonly' => true
]);

echo $form->getBuffer();
