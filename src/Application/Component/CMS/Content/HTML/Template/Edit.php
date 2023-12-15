<?php

use Component\Content\Controller\Category;
use \Adept\Document\HTML\Elements\Div;
use Adept\Document\HTML\Elements\Fieldset;
use \Adept\Document\HTML\Elements\Form;
use \Adept\Document\HTML\Elements\Form\Address;
use \Adept\Document\HTML\Elements\Form\Date;
use \Adept\Document\HTML\Elements\Form\Name;
use \Adept\Document\HTML\Elements\Form\Row;
use \Adept\Document\HTML\Elements\H3;
use \Adept\Document\HTML\Elements\Hr;
use \Adept\Document\HTML\Elements\Input;
use \Adept\Document\HTML\Elements\Input\DateTime;
use \Adept\Document\HTML\Elements\Input\Hidden;
use \Adept\Document\HTML\Elements\Input\Password;
use \Adept\Document\HTML\Elements\Input\Submit;
use \Adept\Document\HTML\Elements\Tab;
use \Adept\Document\HTML\Elements\Tabs;
use \Adept\Document\HTML\Elements\TextArea;

$this->doc->head->css->addFile('/css/form.ajax.css');
$this->doc->head->css->addFile('/css/tabs.css');
$this->doc->head->javascript->addFile('/js/form.ajax.js');
$this->doc->head->javascript->addFile('/js/tabs.js');

$html = '';

$form = new Form([
  'method' => 'post',
  'action' => '/content/edit',
  'css' => ['ajax', 'full']
], [

  //
  // Heading
  //

  // Title
  new row(
    ['css' => ['half']],
    [
      new Input([
        'name' => 'title',
        'placeholder' => 'Title',
      ])
    ]
  ),

  // Route
  new row(
    ['css' => ['half']],
    [
      new Input([
        'name' => 'route',
        'placeholder' => 'Route',

      ])
    ]
  ),
]);

$tabs = new Tabs(
  ['css' => ['full']],
);

$tabContent = new Tab(['title' => 'Content']);

$tabContent->children[] = new Row(
  [],
  []
);

$tabs->children[] = $tabContent;

$tabSEO = new Tab(['title' => 'SEO']);

$tabSEO->children[] = new Fieldset([
  'legend' => 'OpenGraph',
  'css' => ['quarter']
], [
  new Input([
    'name' => 'seo_opengraph_title',
    'placeholder' => 'Title',
  ]),
  new TextArea([
    'name' => 'seo_opengraph_desc',
    'placeholder' => 'Description',
  ])
]);


$tabSEO->children[] = new Fieldset([
  'legend' => 'Twitter',
  'css' => ['quarter']
], [
  new Input([
    'name' => 'seo_twitterh_title',
    'placeholder' => 'Title',
  ]),
  new TextArea([
    'name' => 'seo_twitter_desc',
    'placeholder' => 'Description',
  ])
]);


$tabs->children[] = $tabSEO;



$tabs->children[] = new Tab(['title' => 'Information'], [
  new DateTime(['name' => 'published']),
  new DateTime(['name' => 'created']),
  new DateTime(['name' => 'modified'])
]);

$form->children[] = $tabs;

$form->children[] = new row(
  ['css' => ['half']],
  [
    new Input([
      'name' => 'title',
      'placeholder' => 'Title',
    ])
  ]
);

$form->children[] = new row(
  ['css' => ['half']],
  [
    new Input([
      'name' => 'title',
      'placeholder' => 'Title',
    ])
  ]
);

$form->children[] = clone $tabs;

//echo '<article class="full">';
echo $form->getBuffer();
//echo '</article>';





echo $html;
