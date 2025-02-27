<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form\Filter;
use Adept\Document\HTML\Elements\Form\Textbox\Search;
use Adept\Document\HTML\Elements\Table\Sortable;


// Shortcuts
$app = Application::getInstance();
$get = Application::getInstance()->session->request->data->get;

// Data
$table = $this->getTable();

// Filter Element
$filter = new Filter();

$filter->children[] = new Search([
  'name'        => 'country',
  'placeholder' => 'Search',
  'value'       => $get->getString('country', '')
]);


echo $filter->getBuffer();

$sortable = new Sortable([], $table->getData());

$sortable->addCol('id', 'ID');
$sortable->addCol('status', 'Status', ['fa-solid', 'fa-circle-check']);

$sortable->addCol('country', 'Country', ['main'], true);
$sortable->addCol('iso2', 'ISO2');
$sortable->addCol('iso3', 'ISO3');
$sortable->addCol('region', 'Region');
$sortable->addCol('subregion', 'Sub-Region');
$sortable->addCol('phoneCode', 'Phone Code');

echo $sortable->getBuffer();
