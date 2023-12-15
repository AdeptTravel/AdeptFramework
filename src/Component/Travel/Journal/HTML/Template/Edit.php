<?php

use \Adept\Document\HTML\Elements\Div;
use \Adept\Document\HTML\Elements\Form;
use \Adept\Document\HTML\Elements\Form\Address;
use \Adept\Document\HTML\Elements\Form\Date;
use \Adept\Document\HTML\Elements\Form\Name;
use \Adept\Document\HTML\Elements\Form\Row;
use \Adept\Document\HTML\Elements\H3;
use \Adept\Document\HTML\Elements\Hr;
use \Adept\Document\HTML\Elements\Input;
use \Adept\Document\HTML\Elements\Input\Hidden;
use \Adept\Document\HTML\Elements\Input\Password;
use \Adept\Document\HTML\Elements\Input\Submit;


//$post = &$app->session->request->data->post;
$user = &$this->app->session->auth->user;
$addr = $user->getLinked('location', "\\Adept\Data\\Item\\Location");

$this->doc->head->css->addFile('/css/form.ajax.css');
$this->doc->head->javascript->addFile('/js/form.ajax.js');

$html = '';

$general = new Form([
  'method' => 'post',
  'action' => '/user/edit',
  'css' => ['ajax']
]);

$general->children[] = new H3(['text' => 'General']);

$general->children[] = $this->status;
$general->children[] = new Row(
  ['css' => ['edit']],
  [
    new Input([
      'name' => 'email',
      'placeholder' => 'Username',
      'disabled' => true,
      'value' => $user->username
    ])
  ],
  true
);

$general->children[] = new Row(
  ['css' => ['edit'], 'required' => true],
  [
    new Password(
      [
        'name' => 'password0',
        'placeholder' => 'Password',
        'disabled' => true
      ]
    )
  ],
  true
);

$general->children[] = new Hr();

$general->children[] = new Name([
  'css' => ['edit'],
  'disabled' => true,
  'data' => [
    'firstname' => $user->firstname,
    'middlename' => $user->middlename,
    'lastname' => $user->lastname,
  ]
]);

$general->children[] = new Hr();

$general->children[] = new Date([
  'css' => ['edit'],
  'label' => 'Date of Birth',
  'name' => 'dob',
  'disabled' => true,

]);

$general->children[] = new Hidden(['name' => 'id', 'value' => $user->id]);

//$general->children[] = new Div([], [new Submit(['value' => 'Signup'])]);

echo '<article class="half">';
echo $general->getBuffer();
echo '</article>';


$address = new Form([
  'method' => 'post',
  'action' => '/user/address',
  'css' => ['ajax']
]);

$address->children[] = new H3(['text' => 'Address']);
$address->children[] = $this->status;
//die('<pre>' . print_r($addr, true));
for ($i = 0; $i < count($addr); $i++) {
  $address->children[] = new Address([
    'app' => $this->app,
    'name' => '',
    'css' => ['edit'],
    'required' => true,
    'data' => [
      'street0' => $addr[$i]->street0,
      'street1' => $addr[$i]->street1,
      'city' => $addr[$i]->area->city,
      'county' => $addr[$i]->area->county,
      'state' => $addr[$i]->area->state,
      'postalcode' => $addr[$i]->area->postalcode,
      'country' => 'US'
    ]
  ]);

  if ($i > 0) {
    $address->children[] = new Hr();
  }
}

echo '<article class="half">';
echo $address->getBuffer();
echo '</article>';

$phone = new Form([
  'method' => 'post',
  'action' => '/user/address',
  'css' => ['ajax']
]);

$phone->children[] = new H3(['text' => 'Phone']);
$phone->children[] = $this->status;

echo '<article class="half">';
echo $phone->getBuffer();
echo '</article>';





echo $html;
