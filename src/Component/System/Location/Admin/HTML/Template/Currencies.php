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
use Adept\Document\HTML\Elements\Form\DropDown\Route\Area;
use Adept\Document\HTML\Elements\Form\DropDown\Route\Component;
use Adept\Document\HTML\Elements\Form\DropDown\Route\Host;
use Adept\Document\HTML\Elements\Form\DropDown\Route\Template;
use Adept\Document\HTML\Elements\Form\DropDown\Route\Type;
use Adept\Document\HTML\Elements\Form\DropDown\Route\View;
use Adept\Document\HTML\Elements\Form\Filter;
use Adept\Document\HTML\Elements\Form\Textbox\Search;
use Adept\Document\HTML\Elements\P;
use Adept\Document\HTML\Elements\Table\Sortable;
use Adept\Helper\Arrays;

// Shortcuts
$app = Application::getInstance();
$get = Application::getInstance()->session->request->data->get;

// Data
$table = $this->getTable();

// Filter Element
$filter = new Filter();

$filter->children[] = new Search([
  'name'        => 'currency',
  'placeholder' => 'Search',
  'value'       => $get->getString('currency', '')
]);


echo $filter->getBuffer();

$sortable = new Sortable([], $table->getData());

$sortable->addCol('id', 'ID');
$sortable->addCol('status', 'Status', ['fa-solid', 'fa-circle-check']);

$sortable->addCol('currency', 'Currency', ['main'], true);
$sortable->addCol('code', 'Code');
$sortable->addCol('symbol', 'Symbol');
$sortable->addCol('subunit', 'Subunit');
$sortable->addCol('subunitRatio', 'Subunit Ratio');

echo $sortable->getBuffer();
