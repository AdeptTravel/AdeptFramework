<?php

use \Adept\Document\HTML\Elements\A;
use \Adept\Document\HTML\Elements\Div;
use \Adept\Document\HTML\Elements\Form;
use \Adept\Document\HTML\Elements\Form\Row;
use \Adept\Document\HTML\Elements\Input;
use \Adept\Document\HTML\Elements\Input\Hidden;
use \Adept\Document\HTML\Elements\Input\Password;
use \Adept\Document\HTML\Elements\Input\Submit;
use \Adept\Document\HTML\Elements\P;


$app = \Adept\Application::getInstance();

$html = '';

$form = new Form([
  'method' => 'post'
]);

if (!$app->session->auth->status) {
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

  $form->children[] = new Row(
    [],
    [new Submit(['value' => 'Login'])]
  );

  $form->children[] = new Div(
    ['css' => ['links']],
    [
      new A(['href' => '/signup', 'html' => 'Signup']),
      new A(['href' => '/forgot', 'html' => 'Forgot']),
    ]
  );
} else {
  $form->children[] = new Row(
    [],
    [
      new P([
        'text' => 'You are currently logged in.'

      ])
    ],
    false
  );
  $form->children[] = new Hidden(
    [
      'name' => 'action',
      'value' => 'logout'
    ]
  );

  $form->children[] = new Div([], [new Submit(['value' => 'Logout'])]);
}

$html .= $form->getBuffer();

echo $html;
