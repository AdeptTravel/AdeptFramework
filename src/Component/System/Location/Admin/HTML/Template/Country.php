<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form;
use Adept\Document\HTML\Elements\Form\Row\DropDown;
use \Adept\Document\HTML\Elements\Form\Row\DropDown\Content;
use \Adept\Document\HTML\Elements\Form\Row\DropDown\Location\Currency;
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
  'label' => 'Country',
  'name' => 'country',
  'value' => (isset($item->country)) ? $item->country : '',
  'placeholder' => 'Country',
  'required' => true
]);

$form->children[] = new Dropdown([
  'label'        => 'Status',
  'name'         => 'status',
  'value'        => (string)$item->status,
  'values'       => Arrays::ValueToArray(['Active', 'Inactive', 'Trash'])
]);

$form->children[] = new Input([
  'label' => 'ISO2',
  'name' => 'iso2',
  'value' => (isset($item->iso2)) ? $item->iso2 : '',
  'placeholder' => 'ISO2',
  'required' => true
]);

$form->children[] = new Input([
  'label' => 'ISO3',
  'name' => 'iso3',
  'value' => (isset($item->iso3)) ? $item->iso3 : '',
  'placeholder' => 'ISO3',
  'required' => true
]);

/*
  `country`         VARCHAR(52) UNIQUE,
  `iso2`            CHAR(2) UNIQUE,
  `iso3`            CHAR(3) UNIQUE,
  `phoneCode`       VARCHAR(19),
  `region`          VARCHAR(9),
  `subregion`       VARCHAR(25),
  `currencyId`      VARCHAR(64),
  `contentId`       INT UNSIGNED,
*/

$form->children[] = new Input([
  'label' => 'Phone Code',
  'name' => 'phoneCode',
  'value' => (isset($item->phoneCode)) ? $item->phoneCode : '',
  'placeholder' => 'Phone Code',
  'required' => true
]);

$form->children[] = new Input([
  'label' => 'Region',
  'name' => 'region',
  'value' => (isset($item->region)) ? $item->region : '',
  'placeholder' => 'Region',
  'required' => true
]);

$form->children[] = new Input([
  'label' => 'Sub-Region',
  'name' => 'subegion',
  'value' => (isset($item->subegion)) ? $item->subegion : '',
  'placeholder' => 'Sub-Region',
  'required' => true
]);


$form->children[] = new Currency([
  'label' => "Currency",
  'name' => 'currencyId',
  'value' => (isset($item->currencyId)) ? $item->currencyId : '',
  'placeholder' => 'Currency',
  'required' => true
]);


$form->children[] = new Content([
  'label' => "Linked Content",
  'name' => 'contentId',
  'value' => (isset($item->contentId)) ? $item->contentId : '',
  'placeholder' => 'Linked Content',
  'required' => true
]);


echo $form->getBuffer();
