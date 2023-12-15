<?php

use \Adept\Document\HTML\Elements\A;
use \Adept\Document\HTML\Elements\Div;
use \Adept\Document\HTML\Elements\Form;
use \Adept\Document\HTML\Elements\Form\Date;
use \Adept\Document\HTML\Elements\Form\Row;
use \Adept\Document\HTML\Elements\H1;
use \Adept\Document\HTML\Elements\Hr;
use \Adept\Document\HTML\Elements\Input;
use \Adept\Document\HTML\Elements\Input\Password;
use \Adept\Document\HTML\Elements\Input\Submit;
use \Adept\Document\HTML\Elements\Input\Hidden;
use \Adept\Document\HTML\Elements\P;

$form = new Form([
  'method' => 'post',
  'action' => '/verify'
]);

$form->children[] = new H1([
  'text' => 'Verify'
]);

$form->children[] = $this->status;

if ($this->action == 'verify' || $this->action == 'error') {
  if ($this->action == 'error') {
    $form->children[] = new P([
      'text' => 'Fill out the form to have a verification token resent to you.'
    ]);
  }

  $form->children[] = new Hr();
  $form->children[] = new Row(
    [],
    [
      new Input([
        'name' => 'email',
        'placeholder' => 'Email Address',
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
  /*
  $form->children[] = new Hr();

  $form->children[] = new Date([
    'label' => 'Date of Birth',
    'name' => 'dob',
    'required' => true
  ]);
  */
  $form->children[] = new Hr();

  if ($this->action != 'error') {
    $form->children[] = new Hidden([
      'name' => 'token',
      'value' => $this->token
    ]);
  }

  if ($this->action == 'error') {
    $form->children[] = new Hidden([
      'name' => 'action',
      'value' => 'resend'
    ]);
    $form->children[] = new Div([], [new Submit(['value' => 'Resend'])]);
  } else if ($this->action == 'verify') {
    $form->children[] = new Hidden([
      'name' => 'action',
      'value' => 'verify'
    ]);

    $form->children[] = new Div([], [new Submit(['value' => 'Verify'])]);
  }
} else if ($this->action == 'success') {
  $form->children[] = new P([
    'text' => 'Your email address has been verified and you are now logged in.  <a href="/">Click Here</a> to continue to the main website.'
  ]);
} else {
  $form->children[] = new P([
    'text' => 'Verification token has been sent, check your email for the next steps..'
  ]);
}

echo $form->getBuffer();
