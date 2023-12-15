<?php

use \Adept\Document\HTML\Elements\Article;
use \Adept\Document\HTML\Elements\Div;
use \Adept\Document\HTML\Elements\Form;
use \Adept\Document\HTML\Elements\Form\Row;
use \Adept\Document\HTML\Elements\H1;
use \Adept\Document\HTML\Elements\H3;
use \Adept\Document\HTML\Elements\Input;
use \Adept\Document\HTML\Elements\Input\Hidden;
use \Adept\Document\HTML\Elements\Tab;
use \Adept\Document\HTML\Elements\Tabs;

$this->doc->head->css->addFile('/css/form.ajax.css');
$this->doc->head->css->addFile('/css/tabs.css');
$this->doc->head->javascript->addFile('/js/form.ajax.js');
$this->doc->head->javascript->addFile('/js/tabs.js');


die('<pre>' . print_r($this->data->items, true));
/*
$article = new Article(
  ['css' => ['full', 'medialist']],
  [
    [
      new H1(['text' => 'Images'])
    ]
  ]
);

$tabs = new Tabs(['css' => ['full']]);

$tabImages = new Tab(['title' => 'Images']);
$tabConfig = new Tab(['title' => 'Configuration']);

$tabImages->children[] = new Form([
  'css' => ['search', 'full']
], [

  // Title
  new row(
    ['css' => ['full']],
    [
      new Input([
        'name' => 'Search',
        'placeholder' => 'Search',
      ])
    ]
  )
]);

$images = '';

for ($i = 0; $i < count($this->data->items); $i++) {
  $images .= '<div>';
  $images .= '<img src="">';
  $images .= '<div class="controls">';
  $images .= '</div>';
  $images .= '</div>';
}




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
//echo '</article>';


echo '<article>';
echo '<h1>Image Library</h1>';

echo $form->getBuffer();

echo '<section>';

echo '</section>';
echo '</article>';
*/