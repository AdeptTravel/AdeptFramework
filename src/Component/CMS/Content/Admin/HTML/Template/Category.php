<?php


use Adept\Application;
use Adept\Document\HTML\Elements\Form;
use Adept\Document\HTML\Elements\Form\Row\DropDown;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Content\Author;
use Adept\Document\HTML\Elements\Form\Row\DropDown\Content\Category;
use Adept\Document\HTML\Elements\Form\Row\Image;
use Adept\Document\HTML\Elements\Form\Row\Input;
use Adept\Document\HTML\Elements\Form\Row\Input\DateTime;
use Adept\Document\HTML\Elements\Form\Row\TextArea;
use Adept\Document\HTML\Elements\Form\Row\WYSIWYG;
use Adept\Document\HTML\Elements\H3;
use Adept\Document\HTML\Elements\Input\Hidden;
use Adept\Document\HTML\Elements\Tab;
use Adept\Document\HTML\Elements\Tabs;
use Adept\Helper\Arrays;

// Shortcuts
$app  = Application::getInstance();
$head = $app->html->head;
$item = $this->getItem();
$tabs = new Tabs();

$head->javascript->addAsset('Core/Form/Conditional');

$form = new Form([
  'method' => 'post',
]);

$form->children[] = new Hidden([
  'name'  => 'id',
  'value' => $item->id
]);

$form->children[] = new Hidden([
  'name'  => 'type',
  'value' => 'Category'
]);

$form->children[] = new Input([
  'required'   => true,
  'label'       => 'Title',
  'name'        => 'title',
  'value'       => (isset($item->title)) ? $item->title : '',
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
  'value' => (isset($item->summary)) ? $item->summary : '',
  'placeholder' => 'The article content',
]);

$tabContent->children[] = new WYSIWYG([
  'name' => 'content',
  'label' => 'Content',
  'value' => (isset($item->content)) ? $item->content : '',
  'placeholder' => 'The article content',
  'font' => true,
  'fontsize' => true,
  'color' => true,
  'background' => true,
  'subscript' => true,
  'superscript' => true,
  'h1' => true,
  'h2' => true,
  'h3' => true,
  'h4' => true,
  'h5' => false,
  'blockquote' => true,
  'codeblock' => true,
  'list' => true,
  'indent' => true,
  'align ' => true,
  'link' => true,
  'image' => true,
  'video' => true,
  'clean' => true
]);

$tabs->children[] = $tabContent;

$tabInfo = new Tab([
  'title' => 'Info'
]);

$tabInfo->children[] = new Dropdown([
  'label'        => 'Status',
  'name'         => 'status',
  'value'        => (!empty($item->status)) ? $item->status : '',
  'values'       => Arrays::ValueToArray(['Active', 'Archive', 'Inactive', 'Trash'])
]);

$tabInfo->children[] = new Category([
  'label'       => 'Parent',
  'name'        => 'parentId',
  'value'       => (isset($item->parentId)) ? $item->parentId : 0,
  'placeholder' => 'Category'
]);

$tabInfo->children[] = new Input([
  'required'    => true,
  'label'       => 'Route',
  'name'        => 'route',
  'value'       => $item->getRoute()->route,
  'placeholder' => 'Route'
]);

$tabInfo->children[] = new Author([
  'label' => 'Author',
  'name'  => 'authorId',
  'value' => (isset($item->authorId)) ? $item->authorId : Application::getInstance()->session->auth->user->id
]);

$tabs->children[] = $tabInfo;

/**
 * Images
 */

$tabImages = new Tab([
  'title' => 'Images'
]);

$tabImages->children[] = new Image([
  'name' => 'imageId',
  'label' => 'Content',
  'value' => (!empty($item->imageId)) ? $item->imageId : 0
]);
$tabImages->children[] = new Image([
  'name' => 'ogImageId',
  'label' => 'Open Graph Image',
  'value' => (!empty($item->ogImageId)) ? $item->ogImageId : 0
]);

$tabImages->children[] = new Image([
  'name' => 'xImageId',
  'label' => 'X Image',
  'value' => (!empty($item->xImageId)) ? $item->xImageId : 0
]);


$tabs->children[] = $tabImages;

/**
 * SEO
 */

$tabSEO = new Tab([
  'title' => 'SEO'
]);

$tabSEO->children[] = new h3([
  'text' => 'Schema',
]);

$tabSEO->children[] = new DropDown([
  'label'      => 'Content Type',
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

$tabSEO->children[] = new h3([
  'text' => 'Meta',
]);

$tabSEO->children[] = new Input([
  'name' => 'metaTitle',
  'value' => (!empty($item->metaTitle)) ? $item->metaTitle : '',
  'label' => 'Browser Title',
  'placeholder' => 'Browser Title'
]);

$tabSEO->children[] = new TextArea([
  'name' => 'metaDescription',
  'value' => (!empty($item->metaDescription)) ? $item->metaDescription : '',
  'label' => 'Meta Description',
  'placeholder' => 'Meta Description'
]);


$tabSEO->children[] = new h3([
  'text' => 'OpenGraph',
]);

$tabSEO->children[] = new Input([
  'name' => 'ogTitle',
  'value' => (!empty($item->ogTitle)) ? $item->ogTitle : '',
  'label' => 'Title',
  'placeholder' => 'OpenGraph Title'
]);

$tabSEO->children[] = new TextArea([
  'name' => 'ogDescription',
  'value' => (!empty($item->ogDescription)) ? $item->ogDescription : '',
  'label' => 'Description',
  'placeholder' => 'Description'
]);

$tabSEO->children[] = new h3([
  'text' => 'Twitter',
]);

$tabSEO->children[] = new Input([
  'name' => 'xTitle',
  'value' => (!empty($item->xTitle)) ? $item->xTitle : '',
  'label' => 'Title',
  'placeholder' => 'Twitter Title'
]);

$tabSEO->children[] = new TextArea([
  'name' => 'xDescription',
  'label' => 'Description',
  'value' => (!empty($item->xDescription)) ? $item->xDescription : '',
  'placeholder' => 'Description'
]);

$tabSEO->children[] = new Dropdown([
  'label'        => 'xCardType',
  'name'         => 'xCardType',
  'value'        => (!empty($item->xCardType)) ? $item->xCardType : '',
  'values'       => [
    'summary' => 'Summary',
    'summary_large_image' => 'Summary - Large Image',
    'app' => 'App',
    'player' => 'Player'
  ]
]);

$tabs->children[] = $tabSEO;

/**
 * Dates
 */

$tabDates = new Tab([
  'title' => 'Dates'
]);

$tabDates->children[] = new DateTime([
  'name'  => 'activeOn',
  'label' => 'Publish On',
  'value' => (!empty($item->activeOn)) ? $item->activeOn->format('Y-m-d\TH:i:s') : date("Y-m-d H:i:s"),

]);

$tabDates->children[] = new DateTime([
  'name'  => 'archiveOn',
  'label' => 'Archive On',
  'value' => (!empty($item->archiveOn)) ? $item->archiveOn->format('Y-m-d\TH:i:s') : '0000-00-00 00:00:00',
]);

$tabDates->children[] = new DateTime([
  'name'  => 'createdAt',
  'label' => 'Created',
  'value' => (!empty($item->createdAt)) ? $item->createdAt->format('Y-m-d\TH:i:s') : date("Y-m-d H:i:s")
]);

$tabDates->children[] = new DateTime([
  'name'     => 'updatedAt',
  'label'    => 'Modified',
  'value'    => (!empty($item->updatedAt)) ? $item->updatedAt->format('Y-m-d\TH:i:s') : date("Y-m-d H:i:s"),
  'disabled' => true
]);

$tabs->children[] = $tabDates;

$form->children[] = $tabs;

echo $form->getBuffer();
