<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form;
use Adept\Document\HTML\Elements\Form\Row\DropDown;
use \Adept\Document\HTML\Elements\Form\Row\DropDown\Content;

use \Adept\Document\HTML\Elements\Form\Row\DropDown\Location\Country;
use \Adept\Document\HTML\Elements\Form\Row\DropDown\Location\State;
use \Adept\Document\HTML\Elements\Form\Row\DropDown\Location\County;
use \Adept\Document\HTML\Elements\Form\Row\DropDown\Location\City;
use \Adept\Document\HTML\Elements\Form\Row\DropDown\Location\PostalCode;

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

$form->children[] = new Dropdown([
  'label'        => 'Status',
  'name'         => 'status',
  'value'        => (string)$item->status,
  'values'       => Arrays::ValueToArray(['Active', 'Inactive', 'Trash'])
]);

$form->children[] = new Country([
  'label' => "Country",
  'name' => 'country',
  'value' => (isset($item->country)) ? $item->country : '',
  'placeholder' => 'Country',
  'required' => true
]);

$form->children[] = new State([
  'label' => "State",
  'name' => 'state',
  'value' => (isset($item->state)) ? $item->state : '',
  'placeholder' => 'State',
  'required' => true
]);

$form->children[] = new County([
  'label' => "County",
  'name' => 'county',
  'value' => (isset($item->county)) ? $item->county : '',
  'placeholder' => 'County',
  'required' => true
]);

$form->children[] = new City([
  'label' => "City",
  'name' => 'city',
  'value' => (isset($item->city)) ? $item->city : '',
  'placeholder' => 'City',
  'required' => true
]);

$form->children[] = new PostalCode([
  'label' => "PostalCode",
  'name' => 'postalCode',
  'value' => (isset($item->postalCode)) ? $item->postalCode : '',
  'placeholder' => 'PostalCode',
  'required' => true
]);

echo $form->getBuffer();
