<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form;
use Adept\Document\HTML\Elements\Form\Row;
use Adept\Document\HTML\Elements\Form\Row\DropDown;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Route\Area;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Route\Component;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Route\Host;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Route\Template;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Route\Type;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Route\View;
use Adept\Document\HTML\Elements\Form\Row\Input;
use Adept\Document\HTML\Elements\Form\Row\Input\DateTime;
use Adept\Document\HTML\Elements\Input\Hidden;
use Adept\Document\HTML\Elements\Input\Toggle;
use Adept\Document\HTML\Elements\Tab;
use Adept\Document\HTML\Elements\Tabs;
use Adept\Helper\Arrays;

// Shortcuts
$app  = Application::getInstance();
$get  = $app->session->request->data->get;
$post = $app->session->request->data->post;

$item = $this->getItem($get->getInt('id'));

$tabs = new Tabs();

$form = new Form([
  'method' => 'post',
]);

$form->children[] = new Hidden(['name' => 'id', 'value' => $item->id]);

$form->children[] = new Input([
  'label' => ' Route',
  'name' => 'route',
  'value' => (isset($item->route)) ? $item->route : '',
  'placeholder' => 'Route',
  'required' => true
]);

$tabDetails = new Tab([
  'title' => 'Details'
]);

$tabDetails->children[] = new Dropdown([
  'label'        => 'Status',
  'name'         => 'status',
  'value'        => (string)$item->status,
  'values'       => Arrays::ValueToArray(['Active', 'Block', 'Inactive', 'Trash'])
]);
$tabDetails->children[] = new Host([
  'label'        => 'Host',
  'name'         => 'host',
  'value'        => (isset($item->host)) ? $item->host : '',
]);

$tabDetails->children[] = new Type([
  'label'        => 'Type',
  'name'         => 'type',
  'fromFS'       => true,
  'value'        => ((!empty($item->type)) ? $item->type : '')
]);

$tabDetails->children[] = new Component([
  'required'    => true,
  'label'       => 'Component',
  'name'        => 'component',
  'value'       => (!empty($item->component)) ? $item->component : ''
]);

$tabDetails->children[] = new Area([
  'label'        => 'Area',
  'name'         => 'area',
  'fromFS'       => true,
  'value'        => (!empty($item->area)) ? $item->area : ''
]);




$tabDetails->children[] = new View([
  'required' => true,
  'label' => 'View',
  'name' => 'view',
  'value' => (!empty($item->view)) ? $item->view : '',
]);

$tabDetails->children[] = new Template([
  'required' => true,
  'label' => 'Template',
  'name' => 'template',
  'value' => (!empty($item->template)) ? $item->template : ''
]);

$tabDetails->children[] = new DateTime([
  'name'     => 'createdAt',
  'label'    => 'Created',
  'value'    => (isset($item->createdAt)) ? $item->createdAt->format('Y-m-d\TH:i:s') : '',
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

$tabSecurity->children[] = new Row(['label' => 'Allow Get'], [new Toggle(['name' => 'allowGet', 'checked' => $item->allowGet])]);
$tabSecurity->children[] = new Row(['label' => 'Allow Post'], [new Toggle(['name' => 'allowPost', 'checked' => $item->allowPost])]);
$tabSecurity->children[] = new Row(['label' => 'Allow Email'], [new Toggle(['name' => 'allowEmail', 'checked' => $item->allowEmail])]);
$tabSecurity->children[] = new Row(['label' => 'Is Secure'], [new Toggle(['name' => 'isSecure', 'checked' => $item->isSecure])]);


$tabs->children[] = $tabSecurity;

$form->children[] = $tabs;

echo $form->getBuffer();
