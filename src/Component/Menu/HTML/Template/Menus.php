<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form;
use Adept\Document\HTML\Elements\P;
use Adept\Document\HTML\Elements\Select\Filter\Status;
use Adept\Document\HTML\Elements\Select\Route\Component;
use Adept\Document\HTML\Elements\Select\Route\Option;
use Adept\Document\HTML\Elements\Select\Route\Template;
use Adept\Document\HTML\Elements\Table\Sortable;

// Shortcuts
$app      = Application::getInstance();
$head     = $app->html->head;
$list     = $this->items->getList();
$request  = $app->session->request;
$get      = $request->data->get;
$path     = $request->url->path;
$sort     = $get->getString('sort', '');
$dir      = $get->getString('dir', 'asc');

$head->css->addFile('form.table.css');
$head->css->addFile('form.filter.css');
$head->javascript->addFile('form.table.js');


$sortable = new Sortable([], $list);

$sortable->addCol('id', 'ID');
$sortable->addCol('status', 'Status', ['fa-solid', 'fa-circle-check']);
$sortable->addCol('title', 'Menu', ['main'], true);
$sortable->addCol('created', 'Created');

echo $sortable->getBuffer();
