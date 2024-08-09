<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form;
use Adept\Document\HTML\Elements\H3;
use Adept\Document\HTML\Elements\Input\Hidden;
//use Adept\Document\HTML\Elements\Form\Image;
use Adept\Document\HTML\Elements\Form\Row\Input;
use Adept\Document\HTML\Elements\Form\Row\Input\DateTime;
use Adept\Document\HTML\Elements\Form\Row\DropDown;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Content\Category;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Status;
use Adept\Document\HTML\Elements\Form\Row\Image;
use Adept\Document\HTML\Elements\Form\Row\TextArea;
use Adept\Document\HTML\Elements\Form\Row\WYSIWYG;
use Adept\Document\HTML\Elements\Tab;
use Adept\Document\HTML\Elements\Tabs;

// Shortcuts
$app  = Application::getInstance();
$head = $app->html->head;
$item = $this->getItem();
$tabs = new Tabs();

$head->javascript->addFile('form.conditional.js');

///////
//$image = new \Adept\Document\HTML\Elements\Form\Image(['value' => 1]);
//$image->getBuffer();
///////

$form = new Form([
  'method' => 'post',
]);

$form->children[] = new Hidden([
  'name'  => 'id',
  'value' => $item->id
]);

$form->children[] = new Hidden([
  'name'  => 'type',
  'value' => 'Article'
]);

$form->children[] = new Input([
  'required'   => true,
  'label'       => 'Title',
  'name'        => 'title',
  'value'       => $item->title,
  'placeholder' => 'Title',
  'css'         => ['stack']
]);

/**
 * Content
 */

$tabContent = new Tab([
  'title' => 'Content'
]);

$tabContent->children[] = new WYSIWYG([
  'name' => 'summary',
  'label' => 'Summary',
  //'value' => '',
  'placeholder' => 'The article content',
  'font' => false,
  'fontsize' => false,
  'bold' => true,
  'italic' => true,
  'underline' => true,
  'strike' => true,
  'color' => false,
  'background' => false,
  'subscript' => false,
  'superscript' => false,
  'h1' => false,
  'h2' => false,
  'h3' => false,
  'h4' => false,
  'h5' => false,
  'blockquote' => false,
  'codeblock' => false,
  'list' => false,
  'indent' => false,
  'align ' => false,
  'link' => true,
  'image' => false,
  'video' => false,
  'clean' => true
]);

$tabContent->children[] = new WYSIWYG([
  'name' => 'content',
  'label' => 'Content',
  //'value' => '',
  'placeholder' => 'The article content'
]);

$tabs->children[] = $tabContent;

$tabInfo = new Tab([
  'title' => 'Info'
]);

$tabInfo->children[] = new Category([
  'label'       => 'Category',
  'name'        => 'category',
  'value'       => $item->parent,
  'placeholder' => 'Category'
]);

$tabInfo->children[] = new Input([
  'required'    => true,
  'label'       => 'Route',
  'name'        => 'route',
  'value'       => ($item->route > 0) ? $route->route : '',
  'placeholder' => 'Route'
]);

$tabInfo->children[] = new DropDown([
  'label'      => 'Article Type',
  'name'       => 'subtype',
  'value'      => (!empty($item->subtype)) ? $item->subtype : '',
  'allowEmpty' => false,
  'filter'     => false,
  'values'     => [
    ''         => 'Article',
    'Blog'     => 'Blog',
    'News'     => 'News',
    'Video'    => 'Video'
  ]
]);

$tabInfo->children[] = new Status([
  'label'   => 'Status',
  'name'    => 'status',
  'archive' => true,
  'trash'   => true,
  'value'   => $item->status
]);


$tabs->children[] = $tabInfo;


/**
 * Images
 */

$tabImages = new Tab([
  'title' => 'Images'
]);

$tabImages->children[] = new Image([
  'name' => 'image',
  'value' => 1
]);

$tabs->children[] = $tabImages;

/**
 * SEO
 */

$tabSEO = new Tab([
  'title' => 'SEO'
]);

$tabSEO->children[] = new h3([
  'text' => 'Meta',
]);

$tabSEO->children[] = new Input([
  'name' => 'seo_meta_title',
  'value' => '',
  'label' => 'Browser Title',
  'placeholder' => 'Browser Title'
]);

$tabSEO->children[] = new TextArea([
  'name' => 'seo_meta_desc',
  'value' => '',
  'label' => 'Meta Description',
  'placeholder' => 'Meta Description'
]);


$tabSEO->children[] = new h3([
  'text' => 'OpenGraph',
]);

$tabSEO->children[] = new Input([
  'name' => 'seo_opengraph_title',
  'value' => '',
  'label' => 'Title',
  'placeholder' => 'OpenGraph Title'
]);

$tabSEO->children[] = new TextArea([
  'name' => 'seo_opengraph_desc',
  'value' => '',
  'label' => 'Description',
  'placeholder' => 'Description'
]);

$tabSEO->children[] = new h3([
  'text' => 'Twitter',
]);

$tabSEO->children[] = new Input([
  'name' => 'seo_twitter_title',
  'value' => '',
  'label' => 'Title',
  'placeholder' => 'Twitter Title'
]);

$tabSEO->children[] = new TextArea([
  'name' => 'seo_twotter_desc',
  'label' => 'Description',
  'value' => '',
  'placeholder' => 'Description'
]);


$tabs->children[] = $tabSEO;


/**
 * Dates
 */

$tabDates = new Tab([
  'title' => 'Dates'
]);

$tabDates->children[] = new DateTime([
  'name'  => 'publish',
  'label' => 'Publish On',
  'value' =>  date("Y-m-d H:i:s"),
]);

$tabDates->children[] = new DateTime([
  'name'  => 'archive',
  'label' => 'Archive On',
  'value' => '0000-00-00 00:00:00',
]);

$tabDates->children[] = new DateTime([
  'name'  => 'created',
  'label' => 'Created',
  'value' => date("Y-m-d H:i:s")
]);

$tabDates->children[] = new DateTime([
  'name'  => 'modified',
  'label' => 'Modified',
  'value' =>  date("Y-m-d H:i:s"),
]);

$tabs->children[] = $tabDates;

$form->children[] = $tabs;

/*

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
*/

echo $form->getBuffer();
