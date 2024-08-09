<?php

use Adept\Application;
use Adept\Document\HTML\Elements\A;
use Adept\Document\HTML\Elements\Div;
use Adept\Document\HTML\Elements\Form;
use Adept\Document\HTML\Elements\Form\Row;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Route\Component;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Route\Option;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Route\Template;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Status;
use Adept\Document\HTML\Elements\Form\Row\Input;
use Adept\Document\HTML\Elements\H3;
use Adept\Document\HTML\Elements\Hr;
use Adept\Document\HTML\Elements\Input\Toggle;


// Shortcuts
$app  = Application::getInstance();
$head = $app->html->head;

$head->javascript->addFile('form.conditional.js');

$form = new Form([
  'method' => 'post',
]);

$form->children[] = new Hr();

$form->children[] = new Status([
  'label' => 'Status',
  'name'  => 'status',
  'value' => $this->item->status
]);

$form->children[] = new Input([
  'label' => ' Route',
  'name' => 'route',
  'value' => $this->item->route,
  'placeholder' => 'Route',
  'required' => true
]);


$form->children[] = new Component([
  'required' => true,
  'label' => 'Component',
  'name' => 'component',
  'value' => $this->item->component
]);


$form->children[] = new Option([
  'required' => true,
  'label' => 'Option',
  'name' => 'option',
  'value' => $this->item->option,
]);

$form->children[] = new Template([
  'required' => true,
  'label' => 'Template',
  'name' => 'template',
  'value' => $this->item->template
]);


$form->children[] = new Hr();
$form->children[] = new H3(['text' => 'Sitemap']);
$form->children[] = new Row(['label' => 'Include in Sitemap'], [new Toggle(['name' => 'sitemap', 'checked' => $this->item->sitemap])]);

$form->children[] = new Hr();
$form->children[] = new H3(['text' => 'Formats']);
$form->children[] = new Row(['label' => 'Allow HTML'], [new Toggle(['name' => 'html', 'checked' => $this->item->html])]);
$form->children[] = new Row(['label' => 'Allow JSON'], [new Toggle(['name' => 'json', 'checked' => $this->item->json])]);
$form->children[] = new Row(['label' => 'Allow CSV'], [new Toggle(['name' => 'csv', 'checked' => $this->item->csv])]);
$form->children[] = new Row(['label' => 'Allow PDF'], [new Toggle(['name' => 'pdf', 'checked' => $this->item->pdf])]);
$form->children[] = new Row(['label' => 'Allow ZIP'], [new Toggle(['name' => 'zip', 'checked' => $this->item->zip])]);



$form->children[] = new Hr();
$form->children[] = new H3(['text' => 'Security']);
$form->children[] = new Row(['label' => 'Allow Get'], [new Toggle(['name' => 'get', 'checked' => $this->item->get])]);
$form->children[] = new Row(['label' => 'Allow Post'], [new Toggle(['name' => 'post', 'checked' => $this->item->post])]);
$form->children[] = new Row(['label' => 'Allow Email'], [new Toggle(['name' => 'email', 'checked' => $this->item->email])]);
$form->children[] = new Row(['label' => 'Is Secure'], [new Toggle(['name' => 'secure', 'checked' => $this->item->secure])]);
$form->children[] = new Row(['label' => 'Block Route'], [new Toggle(['name' => 'block', 'checked' => $this->item->block])]);

echo $form->getBuffer();

//echo '<pre>' . print_r($this->item, true) . '</pre>';
