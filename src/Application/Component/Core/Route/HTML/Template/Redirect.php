<?php

use \Adept\Document\HTML\Elements\Div;
use \Adept\Document\HTML\Elements\Form;
use \Adept\Document\HTML\Elements\Form\Row;
use \Adept\Document\HTML\Elements\I;
use \Adept\Document\HTML\Elements\Input;
use \Adept\Document\HTML\Elements\Input\Hidden;
use \Adept\Document\HTML\Elements\Input\Submit;


$this->doc->head->css->addFile('/css/form.ajax.css');
$this->doc->head->css->addFile('/css/tabs.css');
$this->doc->head->javascript->addFile('/js/form.ajax.js');
$this->doc->head->javascript->addFile('/js/form.repeat.js');


$html = '';

$children = [];

for ($i = 0; $i < count($this->data); $i++) {
  $children[] = new row(
    ['css' => ['twocol', 'repeat']],
    [
      new Input([
        'name' => 'route:' . $this->data[$i]->id,
        'placeholder' => 'Route',
        'value' => $this->data[$i]->route
      ]),
      //<i class="fa-solid fa-arrow-right"></i>
      new I(['css' => ['fa-solid', 'fa-arrow-right']]),

      new Input([
        'name' => 'redirect:' . $this->data[$i]->id,
        'placeholder' => 'Redirect',
        'value' => $this->data[$i]->redirect
      ]),
      new Div([
        'css' => ['controls']
      ], [
        new I([
          'css' => [
            'fa-solid',
            'fa-floppy-disk',
            'save'
          ]
        ]),

        new I([
          'css' => [
            'fa-solid',
            'fa-trash',
            'del',
            'active'
          ]
        ]),

        new I([
          'css' => [
            'fa-solid',
            'fa-circle-plus',
            'add'
          ]
        ]),

      ])
    ]
  );
}

$form = new Form([
  'method' => 'post',
  'action' => '/system/redirect',
  'css' => ['ajax', 'full']
], $children);

echo $form->getBuffer();
