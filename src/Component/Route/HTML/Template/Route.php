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
use Adept\Document\HTML\Elements\Form\Row\Input\DateTime;
use Adept\Document\HTML\Elements\H3;
use Adept\Document\HTML\Elements\Hr;

use Adept\Document\HTML\Elements\Input\Hidden;
use Adept\Document\HTML\Elements\Input\Toggle;
use Adept\Document\HTML\Elements\Tab;
use Adept\Document\HTML\Elements\Tabs;

// Shortcuts
$app  = Application::getInstance();
$get  = $app->session->request->data->get;
$post = $app->session->request->data->post;
$head = $app->html->head;
$item = $this->getItem($get->getInt('id'));

$head->javascript->addFile('form.conditional.js');

$tabs = new Tabs();

$form = new Form([
  'method' => 'post',
]);

$form->children[] = new Hidden(['name' => 'id', 'value' => $item->id]);

$form->children[] = new Input([
  'label' => ' Route',
  'name' => 'route',
  'value' => $item->route,
  'placeholder' => 'Route',
  'required' => true
]);

$tabDetails = new Tab([
  'title' => 'Details'
]);

$tabDetails->children[] = new Status([
  'label' => 'Status',
  'name'  => 'status',
  'value' => (string)$item->status
]);

$tabDetails->children[] = new Component([
  'required' => true,
  'label' => 'Component',
  'name' => 'component',
  'value' => $item->component
]);


$tabDetails->children[] = new Option([
  'required' => true,
  'label' => 'Option',
  'name' => 'option',
  'value' => $item->option,
]);

$tabDetails->children[] = new Template([
  'required' => true,
  'label' => 'Template',
  'name' => 'template',
  'value' => $item->template
]);

$form->children[] = new DateTime([
  'name'     => 'created',
  'label'    => 'Created',
  'value'    => $item->created,
  'readonly' => true
]);



$tabDetails->children[] = new Row(['label' => 'Include in Sitemap'], [new Toggle(['name' => 'sitemap', 'checked' => $item->sitemap])]);

$tabs->children[] = $tabDetails;

$tabFormat = new Tab([
  'title' => 'Formats'
]);

$tabFormat->children[] = new Row(['label' => 'Allow HTML'], [new Toggle(['name' => 'html', 'checked' => $item->html])]);
$tabFormat->children[] = new Row(['label' => 'Allow JSON'], [new Toggle(['name' => 'json', 'checked' => $item->json])]);
$tabFormat->children[] = new Row(['label' => 'Allow CSV'], [new Toggle(['name' => 'csv', 'checked' => $item->csv])]);
$tabFormat->children[] = new Row(['label' => 'Allow PDF'], [new Toggle(['name' => 'pdf', 'checked' => $item->pdf])]);
$tabFormat->children[] = new Row(['label' => 'Allow ZIP'], [new Toggle(['name' => 'zip', 'checked' => $item->zip])]);

$tabs->children[] = $tabFormat;

$tabSecurity = new Tab([
  'title' => 'Security'
]);

$tabSecurity->children[] = new Row(['label' => 'Allow Get'], [new Toggle(['name' => 'get', 'checked' => $item->get])]);
$tabSecurity->children[] = new Row(['label' => 'Allow Post'], [new Toggle(['name' => 'post', 'checked' => $item->post])]);
$tabSecurity->children[] = new Row(['label' => 'Allow Email'], [new Toggle(['name' => 'email', 'checked' => $item->email])]);
$tabSecurity->children[] = new Row(['label' => 'Is Secure'], [new Toggle(['name' => 'secure', 'checked' => $item->secure])]);
$tabSecurity->children[] = new Row(['label' => 'Block Route'], [new Toggle(['name' => 'block', 'checked' => $item->block])]);

$tabs->children[] = $tabSecurity;

$form->children[] = $tabs;

echo $form->getBuffer();

//echo '<pre>' . print_r($item, true) . '</pre>';
