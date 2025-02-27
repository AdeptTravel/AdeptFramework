<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Div;
use Adept\Document\HTML\Elements\Form\DropDown;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Block;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Email;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Get;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Post;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Secure;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Sitemap;
use Adept\Document\HTML\Elements\Form\DropDown\Route\Component;
use Adept\Document\HTML\Elements\Form\DropDown\Route\Template;
use Adept\Document\HTML\Elements\Form\DropDown\Route\View;
use Adept\Document\HTML\Elements\Form\Filter;
use Adept\Document\HTML\Elements\Form\Textbox\Search;
use Adept\Document\HTML\Elements\P;
use Adept\Document\HTML\Elements\Table\Sortable;
use Adept\Helper\Arrays;

// Shortcuts
$get = Application::getInstance()->session->request->data->get;

// Data
$table = $this->getTable();

// Filter Element
$filter = new Filter();

$filter->children[] = new Search([
  'name'        => 'search',
  'placeholder' => 'Search',
  'value'       => $get->getString('search', '')
]);

$filter->children[] = new Dropdown([
  'name'         => 'status',
  'placeholder'  => 'Status',
  'value'        => (string)(($get->exists('status')) ? $get->getString('status', 0) : ''),
  'values'       => Arrays::ValueToArray(['Active', 'Block', 'Inactive', 'Locked']),
  'allowEmpty'   => true,
  'emptyDisplay' => '-- Status --',
]);


echo $filter->getBuffer();

$data = $table->getData();
for ($i = 0; $i < count($data); $i++) {
  $data[$i]->display = strtoupper($data[$i]->lastName . ', ' . $data[$i]->firstName . ' ' . $data[$i]->middleName);
}


$sortable = new Sortable([], $data);
$sortable->addCol('id', 'ID');
$sortable->addCol('status', 'Status');
$sortable->addCol('display', 'Full Name', ['main'], true);
$sortable->addCol('firstName', 'First Name');
$sortable->addCol('middleName', 'Middle Name');
$sortable->addCol('lastName', 'Last Name');
$sortable->addCol('username', 'Username');
$sortable->addCol('createdAt', 'Created', ['datetime']);
$sortable->addCol('verifiedOn', 'Verified', ['datetime']);
$sortable->addCol('validatedOn', 'Validated', ['datetime']);

echo $sortable->getBuffer();
