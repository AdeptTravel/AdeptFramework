<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form;
use Adept\Document\HTML\Elements\Input\Hidden;
use Adept\Document\HTML\Elements\Form\Row\Input\DateTime;
use Adept\Document\HTML\Elements\Form\Row\Input;
use Adept\Document\HTML\Elements\Form\Row\DropDown;
use Adept\Document\HTML\Elements\Form\Row\TextArea;
use Adept\Helper\Arrays;

// Shortcuts
$app  = Application::getInstance();
$get  = $app->session->request->data->get;
$post = $app->session->request->data->post;
$head = $app->html->head;
$item = $this->getItem($get->getInt('id'));

$form = new Form([
  'method' => 'post',
]);

$form->children[] = new Hidden(['name' => 'id', 'value' => $item->id]);

$form->children[] = new Hidden([
  'name' => 'duration',
  'value' => 0
]);

$form->children[] = new Dropdown([
  'label'        => 'Status',
  'name'         => 'status',
  'value'        => (empty($item->status)) ? '' : (string)$item->status,
  'values'       => Arrays::ValueToArray(['Active', 'Archive', 'Inactive', 'Trash'])
]);

$form->children[] = new Input([
  'label' => 'Title',
  'name' => 'title',
  'value' => $item->title
]);

$form->children[] = new Input([
  'label' => 'Alias',
  'name' => 'alias',
  'value' => $item->alias
]);

$form->children[] = new TextArea([
  'label' => 'Alt',
  'name'  => 'summary',
  'value' => (isset($item->summary)) ? $item->summary : ''
]);

$form->children[] = new TextArea([
  'label' => 'Caption',
  'name'  => 'caption',
  'value' => (isset($item->caption)) ? $item->caption : ''
]);

$form->children[] = new Input([
  'label'    => 'Mime Type',
  'readonly' => true,
  'name' => 'mime',
  'value' => $item->mime
]);

$form->children[] = new Input([
  'label'    => 'Path',
  'readonly' => true,
  'name' => 'path',
  'value' => $item->path
]);

$form->children[] = new Input([
  'label'    => 'File',
  'readonly' => true,
  'name' => 'file',
  'value' => $item->file
]);

$form->children[] = new Input([
  'label'    => 'Extension',
  'readonly' => true,
  'name' => 'extension',
  'value' => $item->extension
]);

$form->children[] = new Input([
  'label'    => 'Image Width',
  'readonly' => true,
  'name' => 'width',
  'value' => (isset($item->width)) ? $item->width : 0
]);

$form->children[] = new Input([
  'label'    => 'Image Height',
  'readonly' => true,
  'name' => 'height',
  'value' => (isset($item->height)) ? $item->height : 0
]);

$form->children[] = new Input([
  'label'    => 'File Size',
  'readonly' => true,
  'name'     => 'size',
  'value'    => $item->size
]);

$form->children[] = new DateTime(
  [
    'label'    => 'Updated',
    'name'     => 'updatedAt',
    'value'    => (!empty($item->updatedAt)) ? $item->updatedAt : '0000-00-00 00:00:00',
    'readonly' => true,
  ]
);

$form->children[] = new DateTime(
  [
    'label'    => 'Created',
    'name'     => 'createdAt',
    'value'    => (!empty($item->createdAt)) ? $item->createdAt : '0000-00-00 00:00:00',
    'readonly' => true,
  ]
);

echo $form->getBuffer();
