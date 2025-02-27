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

$data = $table->getData();

for ($i = 0; $i < count($data); $i++) {
}

$sortable = new Sortable([], $data);

$sortable->addCol('id', 'ID');
$sortable->addCol('status', 'Status', ['fa-solid', 'fa-circle-check']);

$sortable->addCol('country', 'Country');
$sortable->addCol('state', 'State');
$sortable->addCol('county', 'County');
$sortable->addCol('city', 'City');
$sortable->addCol('postalcode', 'Postal Code');
$sortable->addCol('timezone', 'Time Zone');

echo $sortable->getBuffer();
