<?php

use Adept\Application;
use Adept\Document\HTML\Elements\Form;
use Adept\Document\HTML\Elements\Form\Row\DropDown;
use Adept\Document\HTML\Elements\Form\Row\Input;
use Adept\Document\HTML\Elements\Form\Row\Input\Password;
use Adept\Document\HTML\Elements\Form\Row\Input\Date;
use Adept\Document\HTML\Elements\Form\Row\Input\DateTime;
use Adept\Document\HTML\Elements\Form\Row\Input\Email;
use Adept\Document\HTML\Elements\Hr;
use Adept\Document\HTML\Elements\Input\Hidden;
use Adept\Helper\Arrays;

// Shortcuts
$app  = Application::getInstance();
$get  = $app->session->request->data->get;
$post = $app->session->request->data->post;
$head = $app->html->head;
$item = $this->getItem($get->getInt('id'));

$form = new Form([
  'method' => 'post',
]);

$form->children[] = new Hidden(['name' => 'id', 'value' => $item->id]);

$form->children[] = new Email([
  'label' => 'Username',
  'name' => 'email',
  'value' => (isset($item->username)) ? $item->username : '',
  'placeholder' => 'Username (Email Address)',
  'required' => true
]);

$form->children[] = new Password([
  'label' => 'Password',
  'name' => 'password0',
  'value' => '',
  'placeholder' => 'New Password',
  'required' => false,
  'autocomplete' => true
]);

$form->children[] = new Password([
  'label' => 'Password',
  'name' => 'password1',
  'value' => '',
  'placeholder' => 'Retype New Password',
  'required' => false,
  'autocomplete' => false
]);

$form->children[] = new Hr();

$form->children[] = new Dropdown([
  'label'        => 'Status',
  'name'         => 'status',
  'value'        => (empty($item->status)) ? '' : (string)$item->status,
  'values'       => Arrays::ValueToArray(['Active', 'Block', 'Inactive', 'Locked'])
]);

$form->children[] = new Hr();

$form->children[] = new Input([
  'label' => 'First Name',
  'name' => 'firstName',
  'value' => (isset($item->firstName)) ? $item->firstName : '',
  'placeholder' => 'First Name',
  'required' => true
]);

$form->children[] = new Input([
  'label' => 'Middle Name',
  'name' => 'middleName',
  'value' => (isset($item->middleName)) ? $item->middleName : '',
  'placeholder' => 'Middle Name',
  'required' => false
]);

$form->children[] = new Input([
  'label' => 'Last Name',
  'name' => 'lastName',
  'value' => (isset($item->lastName)) ? $item->lastName : '',
  'placeholder' => 'Last Name',
  'required' => true
]);

$form->children[] = new Hr();

$form->children[] = new Date([
  'label' => 'Date of Birth',
  'name' => 'dob',
  'value' => (isset($item->dob)) ? $item->dob->format('Y-m-d') : '',
  'required' => true
]);

$form->children[] = new Hr();

$form->children[] = new DateTime([
  'label' => 'Created',
  'name' => 'createdAt',
  'value' => (isset($item->createdAt)) ? $item->createdAt->format('Y-m-d\TH:i:s') : '',
  'readonly' => true
]);

$form->children[] = new DateTime([
  'label' => 'Updated',
  'name' => 'updatedAt',
  'value' => (isset($item->updatedAt)) ? $item->updatedAt->format('Y-m-d\TH:i:s') : '',
  'disabled' => true
]);

$form->children[] = new DateTime([
  'label' => 'Verified',
  'name' => 'verifiedOn',
  'value' => (isset($item->verifiedOn)) ? $item->verifiedOn->format('Y-m-d\TH:i:s') : '',
  'disabled' => true
]);

$form->children[] = new DateTime([
  'label' => 'Validated',
  'name' => 'validatedOn',
  'value' => (isset($item->validatedOn)) ? $item->validatedOn->format('Y-m-d\TH:i:s') : '',
  'disabled' => true
]);

echo $form->getBuffer();
