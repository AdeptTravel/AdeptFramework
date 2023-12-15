<?php

use \Adept\Document\HTML\Elements\A;
use \Adept\Document\HTML\Elements\Div;
use \Adept\Document\HTML\Elements\Form;
use \Adept\Document\HTML\Elements\Form\Address;
use \Adept\Document\HTML\Elements\Form\Date;
use \Adept\Document\HTML\Elements\Form\Name;
use \Adept\Document\HTML\Elements\Form\Row;
use \Adept\Document\HTML\Elements\Hr;
use \Adept\Document\HTML\Elements\Input;
use \Adept\Document\HTML\Elements\Input\Tel;
use \Adept\Document\HTML\Elements\Input\Password;
use \Adept\Document\HTML\Elements\Input\Submit;

$html = '';

$form = new Form(['method' => 'post']);
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
  ['required' => true],
  [
    new Password(
      [
        'name' => 'password0',
        'placeholder' => 'Password',
        'required' => true
      ]
    )
  ],
  true
);

$form->children[] = new Row(
  ['required' => true],
  [
    new Password(
      [
        'name' => 'password1',
        'placeholder' => 'Retype Password',
        'required' => true
      ]
    )
  ],
  true
);

$form->children[] = new Hr();

$form->children[] = new Name(['required' => true]);

$form->children[] = new Date([
  'label' => 'Date of Birth',
  'name' => 'dob',
  'required' => true
]);

$form->children[] = new Hr();

$form->children[] = new Address([
  'app' => $this->app,
  'name' => '',
  'required' => true,
  'country' => 'US'
]);

$form->children[] = new Hr();

$form->children[] = new Row(
  ['required' => true],
  [new Tel([
    'app' => $this->app,
    'name' => 'tel',
    'required' => true,
    'placeholder' => 'Phone Number',
  ])],
  true
);

$form->children[] = new Div([], [new Submit(['value' => 'Signup'])]);

$form->children[] = new Div(
  ['css' => ['links']],
  [
    new A(['href' => '/signup', 'html' => 'Signup']),
    new A(['href' => '/forgot', 'html' => 'Forgot']),
  ]
);

$html .= $form->getBuffer();

echo $html;
