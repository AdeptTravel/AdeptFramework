<?php

use Adept\Application;
use Adept\Document\HTML\Elements\A;
use Adept\Document\HTML\Elements\Div;
use Adept\Document\HTML\Elements\Form;
use Adept\Document\HTML\Elements\Form\Row;
use Adept\Document\HTML\Elements\Form\Row\Select;
use Adept\Document\HTML\Elements\H3;
use Adept\Document\HTML\Elements\Hr;
use Adept\Document\HTML\Elements\Input\Hidden;
use Adept\Document\HTML\Elements\Input\Submit;
use Adept\Document\HTML\Elements\Input\Toggle;
use Adept\Document\HTML\Elements\Form\Image;
use Adept\Document\HTML\Elements\Form\Row\Input;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Status;
use Adept\Document\HTML\Elements\Form\Row\TextArea;

// Shortcuts
$app  =  Application::getInstance();
$head = $app->html->head;
$head->javascript->addFile('form.conditional.js');

$form = new Form([
  'method' => 'post',
]);

$item = $this->getItem();

$form->children[] = new Status([
  'name'    => 'status',
  'label'   => 'Status',
  'archive' => true,
  'trash'   => true,
  'lost'    => true,
  'value'   => $item->status
]);

$form->children[] = new Hidden([
  'name' => 'type',
  'value' => 'Image'
]);

$form->children[] = new Hidden([
  'name' => 'mime',
  'value' => $item->mime
]);

$form->children[] = new Hidden([
  'name' => 'path',
  'value' => $item->path
]);

$form->children[] = new Hidden([
  'name' => 'file',
  'value' => $item->file
]);

$form->children[] = new Hidden([
  'name' => 'extension',
  'value' => $item->extension
]);

$form->children[] = new Hidden([
  'name' => 'width',
  'value' => $item->width
]);

$form->children[] = new Hidden([
  'name' => 'height',
  'value' => $item->height
]);

$form->children[] = new Hidden([
  'name' => 'duration',
  'value' => $item->duration
]);

$form->children[] = new Hidden([
  'name' => 'size',
  'value' => $item->size
]);

$form->children[] = new Hidden([
  'name' => 'created',
  'value' => $item->created
]);

$form->children[] = new Hidden([
  'name' => 'modified',
  'value' => $item->modified
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
  'value' => $item->summary
]);

$form->children[] = new TextArea([
  'label' => 'Caption',
  'name'  => 'caption',
  'value' => $item->caption
]);

echo $form->getBuffer();
