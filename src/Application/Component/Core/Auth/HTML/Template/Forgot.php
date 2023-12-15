<?php

use \Adept\Document\HTML\Elements\A;
use \Adept\Document\HTML\Elements\Div;
use \Adept\Document\HTML\Elements\Form;
use \Adept\Document\HTML\Elements\Form\Row;
use \Adept\Document\HTML\Elements\Input;
use \Adept\Document\HTML\Elements\Input\Password;
use \Adept\Document\HTML\Elements\Input\Submit;

$html = '';

$form = new Form([
  'method' => 'post'
]);

$form->children[] = $this->status;

$form->children[] = new Row(
  [],
  [
    new Input([
      'name' => 'email',
      'placeholder' => 'Username',
      'required' => true
    ])
  ],
  true
);

$form->children[] = new Row(
  [],
  [
    new Password([
      'name' => 'password',
      'placeholder' => 'Password',
      'required' => true
    ])
  ],
  true
);

$form->children[] = new Div([], [new Submit(['value' => 'Login'])]);

$form->children[] = new Div(
  ['css' => ['links']],
  [
    new A(['href' => '/signup', 'html' => 'Signup']),
    new A(['href' => '/forgot', 'html' => 'Forgot']),
  ]
);

$html .= $form->getBuffer();

echo $html;
