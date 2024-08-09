<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form;
use Adept\Document\HTML\Elements\Form\Filter;
use Adept\Document\HTML\Elements\P;
use Adept\Document\HTML\Elements\Div;
use Adept\Document\HTML\Elements\Form\DropDown\Filter\Status;

// Shortcuts
$app      = Application::getInstance();
$head     = $app->html->head;
$request  = $app->session->request;
$get      = $request->data->get;
$path     = $request->url->path;
// Vars
$sort     = $get->getString('sort', '');
$dir      = $get->getString('dir', 'asc');

$list     = $this->items->getList();

$head->css->addFile('form.table.css');
$head->javascript->addFile('table.reorder.js');

$filter = new Filter();

$filter->children[] = new Status([
  'name'        => 'status',
  'placeholder' => 'Status',
  'value'       => (string)(($get->exists('status')) ? $get->getInt('status', 0) : '')
]);

echo $filter->getBuffer();

$sortable = new \Adept\Document\HTML\Elements\Table\Sortable(['reorder' => true], $list);
$sortable->reorder = true;

/*
DROP TABLE IF EXISTS `Content`;
CREATE TABLE `Content` (
  `id`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent`   INT UNSIGNED DEFAULT 0,
  `route`    INT UNSIGNED DEFAULT 0,
  `version`  INT UNSIGNED DEFAULT 0,
  `type`     ENUM('Article', 'Category', 'Component', 'Tag'),
  `subtype`  ENUM('', 'Blog', 'News', 'Video') DEFAULT '',
  `title`    VARCHAR(128) NOT NULL,
  `summary`  TEXT DEFAULT '',
  `content`  TEXT DEFAULT '',
  `seo`      TEXT DEFAULT '{}',
  `media`    TEXT DEFAULT '{}',
  `params`   TEXT DEFAULT '{}',
  `status`   TINYINT DEFAULT 1,
  `publish`  DATETIME DEFAULT NOW(),
  `archive`  DATETIME DEFAULT NOW(),
  `created`  DATETIME DEFAULT NOW(),
  `modified` DATETIME DEFAULT NOW(),
  `order`    INT DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;
*/

$sortable->addCol('status', 'Status', ['fa-solid', 'fa-circle-check']);
$sortable->addCol('title', 'Menu Item', ['main'], true);
$sortable->addCol('created', 'Created');
$sortable->addCol('id', 'ID');

echo $sortable->getBuffer();
